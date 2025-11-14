<?php
/**
 * Admin User Management
 */

$pageTitle = 'Manage Users';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

$conn = getDBConnection();
$error = '';
$success = '';

// Handle user update
if (isPost() && post('action') === 'update') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $userId = post('user_id');
        $name = post('name');
        $email = post('email');
        $role = post('role');
        
        $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $result = executeQuery($conn, $sql, [$name, $email, $role, $userId], "ssss");
        
        if ($result) {
            $success = 'User updated successfully';
        } else {
            $error = 'Failed to update user';
        }
    }
}

// Handle mark all complete
if (isPost() && post('action') === 'mark_complete') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $userId = post('user_id');
        $courseId = post('course_id');
        
        // Get all lessons and exercises for the course
        $sql = "SELECT id FROM lessons WHERE course_id = ?";
        $result = executeQuery($conn, $sql, [$courseId], "s");
        $lessons = fetchAll($result);
        
        foreach ($lessons as $lesson) {
            // Mark lesson complete
            $sql = "INSERT IGNORE INTO completed_lessons (user_id, lesson_id) VALUES (?, ?)";
            executeQuery($conn, $sql, [$userId, $lesson['id']], "ss");
            
            // Get exercises for this lesson
            $sql = "SELECT id FROM exercises WHERE lesson_id = ?";
            $result = executeQuery($conn, $sql, [$lesson['id']], "s");
            $exercises = fetchAll($result);
            
            foreach ($exercises as $exercise) {
                // Mark exercise complete with full score
                $sql = "INSERT IGNORE INTO completed_exercises (user_id, exercise_id, score) VALUES (?, ?, 100)";
                executeQuery($conn, $sql, [$userId, $exercise['id']], "ss");
            }
        }
        
        $success = 'All lessons and exercises marked as complete';
    }
}

// Handle delete user
if (isPost() && post('action') === 'delete') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $userId = post('user_id');
        
        // Don't allow deleting yourself
        if ($userId === $_SESSION['user_id']) {
            $error = 'Cannot delete your own account';
        } else {
            $sql = "DELETE FROM users WHERE id = ?";
            $result = executeQuery($conn, $sql, [$userId], "s");
            
            if ($result) {
                $success = 'User deleted successfully';
            } else {
                $error = 'Failed to delete user';
            }
        }
    }
}

// Get all users with stats
$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM user_progress WHERE user_id = u.id) as enrolled_courses,
        (SELECT COUNT(*) FROM completed_lessons WHERE user_id = u.id) as completed_lessons,
        (SELECT COUNT(*) FROM completed_exercises WHERE user_id = u.id) as completed_exercises,
        (SELECT COALESCE(SUM(e.points), 0) FROM completed_exercises ce 
         JOIN exercises e ON ce.exercise_id = e.id 
         WHERE ce.user_id = u.id) as total_points
        FROM users u
        ORDER BY u.created_at DESC";
$result = executeQuery($conn, $sql);
$users = fetchAll($result);

// Get user for editing if edit parameter is set
$editUser = null;
if (get('edit')) {
    $editId = get('edit');
    $sql = "SELECT * FROM users WHERE id = ?";
    $result = executeQuery($conn, $sql, [$editId], "s");
    $editUser = fetchOne($result);
    
    // Get user's enrolled courses
    if ($editUser) {
        $sql = "SELECT c.id, c.title FROM courses c
                JOIN user_progress up ON c.id = up.course_id
                WHERE up.user_id = ?";
        $result = executeQuery($conn, $sql, [$editId], "s");
        $editUser['courses'] = fetchAll($result);
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
            <p class="text-gray-600 mt-2">View and manage user accounts</p>
        </div>
        <a href="/admin" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            ← Back to Dashboard
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($editUser): ?>
    <!-- Edit User Form -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Edit User</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($editUser['id']); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" id="name" name="name" required
                               value="<?php echo htmlspecialchars($editUser['name']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($editUser['email']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="user" <?php echo $editUser['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $editUser['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                </div>
                
                <!-- Mark Course Complete Section -->
                <?php if (!empty($editUser['courses'])): ?>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <?php foreach ($editUser['courses'] as $course): ?>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <span class="text-gray-700"><?php echo htmlspecialchars($course['title']); ?></span>
                            <button type="button" 
                                    onclick="markCourseComplete('<?php echo $editUser['id']; ?>', '<?php echo $course['id']; ?>')"
                                    class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                Mark All Complete
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        Update User
                    </button>
                    <a href="/admin/users" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Users List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">All Users (<?php echo count($users); ?>)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users yet</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $user['enrolled_courses']; ?> courses</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php echo $user['completed_lessons']; ?> lessons<br>
                                <span class="text-xs text-gray-500"><?php echo $user['completed_exercises']; ?> exercises</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $user['total_points']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo timeAgo($user['created_at']); ?></td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?edit=<?php echo urlencode($user['id']); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                <form method="POST" action="" class="inline" onsubmit="return confirm('Delete this user? All their data will be removed.');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function markCourseComplete(userId, courseId) {
    if (!confirm('Mark all lessons and exercises as complete for this course?')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="mark_complete">
        <input type="hidden" name="user_id" value="${userId}">
        <input type="hidden" name="course_id" value="${courseId}">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
