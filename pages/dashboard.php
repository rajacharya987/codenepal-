<?php
/**
 * User Dashboard
 */

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get enrolled courses with progress
$sql = "SELECT c.*, up.enrolled_at, up.last_accessed,
        (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons,
        (SELECT COUNT(*) FROM completed_lessons cl 
         JOIN lessons l ON cl.lesson_id = l.id 
         WHERE l.course_id = c.id AND cl.user_id = ?) as completed_lessons
        FROM courses c
        JOIN user_progress up ON c.id = up.course_id
        WHERE up.user_id = ?
        ORDER BY up.last_accessed DESC";
$result = executeQuery($conn, $sql, [$userId, $userId], "ss");
$enrolledCourses = fetchAll($result);

// Get total stats
$sql = "SELECT 
        (SELECT COUNT(*) FROM completed_lessons WHERE user_id = ?) as total_completed_lessons,
        (SELECT COUNT(*) FROM completed_exercises WHERE user_id = ?) as total_completed_exercises,
        (SELECT COALESCE(SUM(points), 0) FROM completed_exercises ce 
         JOIN exercises e ON ce.exercise_id = e.id 
         WHERE ce.user_id = ?) as total_points";
$result = executeQuery($conn, $sql, [$userId, $userId, $userId], "sss");
$stats = fetchOne($result);

// Get recent activity
$sql = "SELECT 'lesson' as type, l.title, cl.completed_at as date, c.title as course_title
        FROM completed_lessons cl
        JOIN lessons l ON cl.lesson_id = l.id
        JOIN courses c ON l.course_id = c.id
        WHERE cl.user_id = ?
        UNION ALL
        SELECT 'exercise' as type, e.title, ce.completed_at as date, c.title as course_title
        FROM completed_exercises ce
        JOIN exercises e ON ce.exercise_id = e.id
        JOIN lessons l ON e.lesson_id = l.id
        JOIN courses c ON l.course_id = c.id
        WHERE ce.user_id = ?
        ORDER BY date DESC
        LIMIT 5";
$result = executeQuery($conn, $sql, [$userId, $userId], "ss");
$recentActivity = fetchAll($result);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, <?php echo htmlspecialchars($currentUser['name']); ?>! 👋</h1>
        <p class="text-gray-600 mt-2">Continue your learning journey</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lessons Completed</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_completed_lessons']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Exercises Solved</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_completed_exercises']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Points</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_points']; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Enrolled Courses -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">My Courses</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($enrolledCourses)): ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No courses yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by enrolling in a course</p>
                            <div class="mt-6">
                                <a href="/pages/courses" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Browse Courses
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($enrolledCourses as $course): 
                                $progress = $course['total_lessons'] > 0 ? 
                                    calculatePercentage($course['completed_lessons'], $course['total_lessons']) : 0;
                            ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo truncateText($course['description'], 100); ?></p>
                                        
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-sm mb-1">
                                                <span class="text-gray-600">Progress</span>
                                                <span class="font-semibold text-gray-900"><?php echo $progress; ?>%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?php echo $course['completed_lessons']; ?> of <?php echo $course['total_lessons']; ?> lessons completed
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <a href="/pages/course?id=<?php echo urlencode($course['id']); ?>" 
                                       class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Continue
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($recentActivity)): ?>
                        <p class="text-sm text-gray-500 text-center py-4">No activity yet</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentActivity as $activity): ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <?php if ($activity['type'] === 'lesson'): ?>
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($activity['course_title']); ?></p>
                                    <p class="text-xs text-gray-400 mt-1"><?php echo timeAgo($activity['date']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Quick Actions</h2>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="/pages/courses" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Browse Courses
                    </a>
                    <a href="/pages/accomplishments" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        View Achievements
                    </a>
                    <a href="/pages/settings" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Account Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
