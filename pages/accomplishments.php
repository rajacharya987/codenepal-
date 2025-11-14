<?php
/**
 * Accomplishments Page
 * Display user achievements, badges, and certificates
 */

$pageTitle = 'Accomplishments';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get user stats
$sql = "SELECT 
        (SELECT COUNT(*) FROM completed_lessons WHERE user_id = ?) as total_lessons,
        (SELECT COUNT(*) FROM completed_exercises WHERE user_id = ?) as total_exercises,
        (SELECT COALESCE(SUM(e.points), 0) FROM completed_exercises ce 
         JOIN exercises e ON ce.exercise_id = e.id 
         WHERE ce.user_id = ?) as total_points,
        (SELECT COUNT(DISTINCT c.id) FROM courses c
         JOIN user_progress up ON c.id = up.course_id
         WHERE up.user_id = ?) as enrolled_courses";
$result = executeQuery($conn, $sql, [$userId, $userId, $userId, $userId], "ssss");
$stats = fetchOne($result);

// Get certificates
$sql = "SELECT cert.*, c.title as course_title, c.language
        FROM certificates cert
        JOIN courses c ON cert.course_id = c.id
        WHERE cert.user_id = ?
        ORDER BY cert.issued_at DESC";
$result = executeQuery($conn, $sql, [$userId], "s");
$certificates = fetchAll($result);

// Define badges
$badges = [
    [
        'id' => 'first_steps',
        'name' => 'First Steps',
        'description' => 'Complete your first lesson',
        'icon' => '🎯',
        'earned' => $stats['total_lessons'] >= 1
    ],
    [
        'id' => 'problem_solver',
        'name' => 'Problem Solver',
        'description' => 'Complete 10 exercises',
        'icon' => '🧩',
        'earned' => $stats['total_exercises'] >= 10
    ],
    [
        'id' => 'dedicated_learner',
        'name' => 'Dedicated Learner',
        'description' => 'Complete 25 lessons',
        'icon' => '📚',
        'earned' => $stats['total_lessons'] >= 25
    ],
    [
        'id' => 'code_master',
        'name' => 'Code Master',
        'description' => 'Complete 50 exercises',
        'icon' => '💻',
        'earned' => $stats['total_exercises'] >= 50
    ],
    [
        'id' => 'point_collector',
        'name' => 'Point Collector',
        'description' => 'Earn 500 points',
        'icon' => '⭐',
        'earned' => $stats['total_points'] >= 500
    ],
    [
        'id' => 'course_conqueror',
        'name' => 'Course Conqueror',
        'description' => 'Complete any course',
        'icon' => '🏆',
        'earned' => count($certificates) >= 1
    ]
];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Your Accomplishments</h1>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $stats['total_lessons']; ?></div>
            <div class="text-gray-600">Lessons Completed</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-4xl font-bold text-green-600 mb-2"><?php echo $stats['total_exercises']; ?></div>
            <div class="text-gray-600">Exercises Solved</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-4xl font-bold text-purple-600 mb-2"><?php echo $stats['total_points']; ?></div>
            <div class="text-gray-600">Total Points</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-4xl font-bold text-orange-600 mb-2"><?php echo count($certificates); ?></div>
            <div class="text-gray-600">Certificates Earned</div>
        </div>
    </div>
    
    <!-- Badges -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-900">Badges</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($badges as $badge): ?>
                <div class="border border-gray-200 rounded-lg p-6 text-center <?php echo $badge['earned'] ? 'bg-gradient-to-br from-yellow-50 to-orange-50' : 'bg-gray-50 opacity-60'; ?>">
                    <div class="text-6xl mb-3"><?php echo $badge['icon']; ?></div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo $badge['name']; ?></h3>
                    <p class="text-sm text-gray-600 mb-3"><?php echo $badge['description']; ?></p>
                    <?php if ($badge['earned']): ?>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                            ✓ Earned
                        </span>
                    <?php else: ?>
                        <span class="inline-block px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded-full font-medium">
                            Locked
                        </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Certificates -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-900">Certificates</h2>
        </div>
        <div class="p-6">
            <?php if (empty($certificates)): ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No certificates yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Complete a course to earn your first certificate</p>
                    <div class="mt-6">
                        <a href="/pages/courses" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Browse Courses
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($certificates as $cert): ?>
                    <div class="border-2 border-yellow-400 rounded-lg p-6 bg-gradient-to-br from-yellow-50 to-orange-50">
                        <div class="text-center mb-4">
                            <div class="text-5xl mb-2">🏆</div>
                            <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($cert['course_title']); ?></h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo ucfirst($cert['language']); ?> Programming
                            </p>
                        </div>
                        <div class="border-t border-yellow-300 pt-4 mt-4">
                            <p class="text-sm text-gray-600 text-center mb-3">
                                Issued on <?php echo formatDate($cert['issued_at']); ?>
                            </p>
                            <p class="text-xs text-gray-500 text-center mb-3">
                                Verification Code: <code class="bg-white px-2 py-1 rounded"><?php echo htmlspecialchars($cert['verification_code']); ?></code>
                            </p>
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                                Download Certificate
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
