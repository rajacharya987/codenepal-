<?php
/**
 * Single Course Page
 */

require_once __DIR__ . '/../includes/header.php';

requireLogin();

$courseId = get('id');
if (!$courseId) {
    redirect('/pages/courses');
}

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get course details
$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM user_progress WHERE course_id = c.id AND user_id = ?) as is_enrolled
        FROM courses c
        WHERE c.id = ? AND c.is_published = 1";
$result = executeQuery($conn, $sql, [$userId, $courseId], "ss");
$course = fetchOne($result);

if (!$course) {
    setFlashMessage('error', 'Course not found');
    redirect('/pages/courses');
}

$pageTitle = $course['title'];

// Handle enrollment
if (isPost() && post('action') === 'enroll') {
    $csrfToken = post('csrf_token');
    
    if (validateCSRFToken($csrfToken)) {
        $sql = "INSERT INTO user_progress (user_id, course_id) VALUES (?, ?)";
        $result = executeQuery($conn, $sql, [$userId, $courseId], "ss");
        
        if ($result) {
            setFlashMessage('success', 'Successfully enrolled in course!');
            redirect('/pages/course?id=' . urlencode($courseId));
        }
    }
}

// Get lessons with completion status and exercise completion
$sql = "SELECT l.*, 
        (SELECT COUNT(*) FROM completed_lessons WHERE lesson_id = l.id AND user_id = ?) as is_completed,
        (SELECT COUNT(*) FROM exercises WHERE lesson_id = l.id) as exercise_count,
        (SELECT COUNT(*) FROM completed_exercises ce 
         JOIN exercises e ON ce.exercise_id = e.id 
         WHERE e.lesson_id = l.id AND ce.user_id = ?) as completed_exercise_count
        FROM lessons l
        WHERE l.course_id = ?
        ORDER BY l.order_index ASC";
$result = executeQuery($conn, $sql, [$userId, $userId, $courseId], "sss");
$lessons = fetchAll($result);

// Calculate progress
$totalLessons = count($lessons);
$completedLessons = 0;
$totalExercises = 0;
$completedExercises = 0;

foreach ($lessons as $lesson) {
    if ($lesson['is_completed']) {
        $completedLessons++;
    }
    $totalExercises += $lesson['exercise_count'];
    $completedExercises += $lesson['completed_exercise_count'];
}

$progress = $totalLessons > 0 ? calculatePercentage($completedLessons, $totalLessons) : 0;
$courseCompleted = ($totalLessons > 0 && $completedLessons >= $totalLessons && 
                    $totalExercises > 0 && $completedExercises >= $totalExercises);

// Check if certificate exists
$hasCertificate = false;
$certificateId = null;
if ($courseCompleted) {
    $sql = "SELECT id FROM certificates WHERE user_id = ? AND course_id = ?";
    $result = executeQuery($conn, $sql, [$userId, $courseId], "ss");
    $cert = fetchOne($result);
    if ($cert) {
        $hasCertificate = true;
        $certificateId = $cert['id'];
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Course Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <?php if ($course['thumbnail_url']): ?>
            <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" 
                 alt="<?php echo htmlspecialchars($course['title']); ?>" 
                 class="w-full h-64 object-cover">
        <?php else: ?>
            <div class="w-full h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                <span class="text-white text-8xl">
                    <?php 
                    echo $course['language'] === 'python' ? '🐍' : 
                         ($course['language'] === 'javascript' ? '⚡' : '⚙️'); 
                    ?>
                </span>
            </div>
        <?php endif; ?>
        
        <div class="p-8">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-sm rounded font-medium">
                    <?php echo ucfirst($course['language']); ?>
                </span>
                <span class="px-3 py-1 bg-green-100 text-green-600 text-sm rounded font-medium">
                    <?php echo ucfirst($course['category']); ?>
                </span>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="text-lg text-gray-600 mb-6"><?php echo htmlspecialchars($course['description']); ?></p>
            
            <div class="flex items-center gap-6 text-gray-600 mb-6">
                <span>📚 <?php echo $totalLessons; ?> lessons</span>
                <span>⏱️ <?php echo htmlspecialchars($course['duration']); ?></span>
            </div>
            
            <?php if ($course['is_enrolled']): ?>
                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="font-medium text-gray-700">Your Progress</span>
                        <span class="font-semibold text-gray-900"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <?php echo $completedLessons; ?> of <?php echo $totalLessons; ?> lessons completed
                        • <?php echo $completedExercises; ?> of <?php echo $totalExercises; ?> exercises completed
                    </p>
                </div>
                
                <?php if ($courseCompleted): ?>
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-3xl mr-3">🎉</span>
                                <div>
                                    <h3 class="font-bold text-green-800">Course Completed!</h3>
                                    <p class="text-sm text-green-600">Congratulations on finishing all lessons and exercises</p>
                                </div>
                            </div>
                            <?php if ($hasCertificate): ?>
                                <a href="/pages/certificate?id=<?php echo urlencode($certificateId); ?>" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                    View Certificate
                                </a>
                            <?php else: ?>
                                <button onclick="generateCertificate('<?php echo $courseId; ?>', event)" 
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                    Get Certificate
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="enroll">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Enroll in This Course
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Lessons List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-900">Course Content</h2>
        </div>
        
        <div class="divide-y divide-gray-200">
            <?php if (empty($lessons)): ?>
                <div class="p-8 text-center text-gray-500">
                    No lessons available yet
                </div>
            <?php else: ?>
                <?php 
                $previousCompleted = true;
                foreach ($lessons as $index => $lesson): 
                    // Check if all exercises in previous lesson are completed
                    $allExercisesCompleted = ($lesson['exercise_count'] == 0) || 
                                            ($lesson['exercise_count'] > 0 && $lesson['completed_exercise_count'] >= $lesson['exercise_count']);
                    
                    // Lesson is locked if:
                    // 1. It's marked as locked AND
                    // 2. Previous lesson is not fully completed (all exercises) AND
                    // 3. Current lesson is not already completed
                    $isLocked = $lesson['is_locked'] && !$previousCompleted && !$allExercisesCompleted;
                    $canAccess = $course['is_enrolled'] && (!$isLocked || $allExercisesCompleted);
                ?>
                <div class="p-6 hover:bg-gray-50 transition <?php echo $isLocked ? 'opacity-60' : ''; ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex items-start flex-1">
                            <div class="flex-shrink-0 mr-4">
                                <?php if ($lesson['is_completed']): ?>
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                <?php elseif ($isLocked): ?>
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold"><?php echo $index + 1; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars($lesson['title']); ?>
                                    <?php if ($isLocked): ?>
                                        <span class="text-sm font-normal text-gray-500 ml-2">🔒 Locked</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <?php echo $lesson['exercise_count']; ?> exercise<?php echo $lesson['exercise_count'] != 1 ? 's' : ''; ?>
                                    <?php if ($lesson['exercise_count'] > 0): ?>
                                        - <?php echo $lesson['completed_exercise_count']; ?>/<?php echo $lesson['exercise_count']; ?> completed
                                    <?php endif; ?>
                                </p>
                                <?php if ($lesson['exercise_count'] > 0 && !$allExercisesCompleted): ?>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-blue-600 h-1.5 rounded-full" 
                                             style="width: <?php echo calculatePercentage($lesson['completed_exercise_count'], $lesson['exercise_count']); ?>%"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($canAccess): ?>
                            <a href="/pages/lesson?id=<?php echo urlencode($lesson['id']); ?>" 
                               class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                                <?php echo $lesson['is_completed'] ? 'Review' : 'Start'; ?>
                            </a>
                        <?php elseif (!$course['is_enrolled']): ?>
                            <span class="ml-4 px-4 py-2 bg-gray-200 text-gray-500 rounded-md font-medium">
                                Enroll to Access
                            </span>
                        <?php else: ?>
                            <span class="ml-4 px-4 py-2 bg-gray-200 text-gray-500 rounded-md font-medium">
                                Locked
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    // Update previous completed status - all exercises must be done
                    $previousCompleted = ($lesson['exercise_count'] == 0) || 
                                        ($lesson['exercise_count'] > 0 && $lesson['completed_exercise_count'] >= $lesson['exercise_count']);
                endforeach; 
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
async function generateCertificate(courseId, event) {
    const btn = event ? event.target : document.querySelector('button[onclick*="generateCertificate"]');
    
    try {
        // Show loading
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Generating...';
        }
        
        const response = await fetch('/api/certificate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'generate',
                course_id: courseId
            })
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            throw new Error('Server returned an error. Please check the console.');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Show success popup
            showCertificatePopup(result.certificate_id, result.verification_code);
        } else {
            alert(result.message || 'Failed to generate certificate');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Get Certificate';
            }
        }
    } catch (error) {
        console.error('Certificate generation error:', error);
        alert('Failed to generate certificate. Please check the console for details.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'Get Certificate';
        }
    }
}

function showCertificatePopup(certificateId, verificationCode) {
    const popup = document.createElement('div');
    popup.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    popup.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform animate-bounce-in">
            <div class="text-center">
                <div class="text-6xl mb-4">🎓</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Congratulations!</h2>
                <p class="text-gray-600 mb-6">Your certificate has been generated successfully!</p>
                
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-2">Verification Code</p>
                    <p class="text-2xl font-mono font-bold text-blue-600">${verificationCode}</p>
                </div>
                
                <div class="flex flex-col gap-3">
                    <a href="/pages/certificate?id=${certificateId}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        View Certificate
                    </a>
                    <button onclick="this.closest('.fixed').remove(); location.reload();" 
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(popup);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
