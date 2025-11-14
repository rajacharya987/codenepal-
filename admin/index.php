<?php
/**
 * Admin Dashboard
 */

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

$conn = getDBConnection();

// Get statistics
$sql = "SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM users WHERE role = 'admin') as total_admins,
        (SELECT COUNT(*) FROM courses) as total_courses,
        (SELECT COUNT(*) FROM courses WHERE is_published = 1) as published_courses,
        (SELECT COUNT(*) FROM lessons) as total_lessons,
        (SELECT COUNT(*) FROM exercises) as total_exercises,
        (SELECT COUNT(*) FROM user_progress) as total_enrollments,
        (SELECT COUNT(*) FROM completed_lessons) as total_completed_lessons,
        (SELECT COUNT(*) FROM completed_exercises) as total_completed_exercises";
$result = executeQuery($conn, $sql);
$stats = fetchOne($result);

// Get recent users
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$result = executeQuery($conn, $sql);
$recentUsers = fetchAll($result);

// Get course enrollment stats
$sql = "SELECT c.title, c.language, COUNT(up.id) as enrollment_count
        FROM courses c
        LEFT JOIN user_progress up ON c.id = up.course_id
        GROUP BY c.id
        ORDER BY enrollment_count DESC
        LIMIT 5";
$result = executeQuery($conn, $sql);
$popularCourses = fetchAll($result);

// Get recent activity
$sql = "SELECT 'enrollment' as type, u.name, c.title as item, up.enrolled_at as date
        FROM user_progress up
        JOIN users u ON up.user_id = u.id
        JOIN courses c ON up.course_id = c.id
        ORDER BY up.enrolled_at DESC
        LIMIT 10";
$result = executeQuery($conn, $sql);
$recentActivity = fetchAll($result);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your learning platform</p>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="/admin/courses" class="bg-blue-600 text-white rounded-lg p-6 hover:bg-blue-700 transition">
            <div class="text-3xl mb-2">📚</div>
            <div class="font-semibold">Manage Courses</div>
        </a>
        <a href="/admin/lessons" class="bg-green-600 text-white rounded-lg p-6 hover:bg-green-700 transition">
            <div class="text-3xl mb-2">📖</div>
            <div class="font-semibold">Manage Lessons</div>
        </a>
        <a href="/admin/exercises" class="bg-purple-600 text-white rounded-lg p-6 hover:bg-purple-700 transition">
            <div class="text-3xl mb-2">💻</div>
            <div class="font-semibold">Manage Exercises</div>
        </a>
        <a href="/admin/users" class="bg-orange-600 text-white rounded-lg p-6 hover:bg-orange-700 transition">
            <div class="text-3xl mb-2">👥</div>
            <div class="font-semibold">Manage Users</div>
        </a>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_users']; ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Courses</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_courses']; ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?php echo $stats['published_courses']; ?> published</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Lessons</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_lessons']; ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Exercises</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_exercises']; ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Popular Courses -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Popular Courses</h2>
            </div>
            <div class="p-6">
                <?php if (empty($popularCourses)): ?>
                    <p class="text-gray-500 text-center py-4">No courses yet</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($popularCourses as $course): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo ucfirst($course['language']); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-blue-600"><?php echo $course['enrollment_count']; ?></span>
                                <p class="text-xs text-gray-500">enrollments</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Recent Users</h2>
            </div>
            <div class="p-6">
                <?php if (empty($recentUsers)): ?>
                    <p class="text-gray-500 text-center py-4">No users yet</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentUsers as $user): ?>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <p class="text-xs text-gray-500 mt-1"><?php echo timeAgo($user['created_at']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
        </div>
        <div class="p-6">
            <?php if (empty($recentActivity)): ?>
                <p class="text-gray-500 text-center py-4">No activity yet</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentActivity as $activity): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2 mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium"><?php echo htmlspecialchars($activity['name']); ?></span>
                                enrolled in
                                <span class="font-medium"><?php echo htmlspecialchars($activity['item']); ?></span>
                            </p>
                            <p class="text-xs text-gray-500"><?php echo timeAgo($activity['date']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
