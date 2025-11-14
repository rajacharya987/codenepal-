<?php
/**
 * Admin Lesson Management
 */

$pageTitle = 'Manage Lessons';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

$conn = getDBConnection();
$error = '';
$success = '';

// Handle lesson creation
if (isPost() && post('action') === 'create') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        $courseId = post('course_id');
        $title = post('title');
        $content = post('content');
        $orderIndex = post('order_index');
        $isLocked = post('is_locked') ? 1 : 0;
        
        if (empty($id) || empty($courseId) || empty($title) || empty($content)) {
            $error = 'ID, course, title, and content are required';
        } else {
            $sql = "INSERT INTO lessons (id, course_id, title, content, order_index, is_locked) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $result = executeQuery($conn, $sql, [$id, $courseId, $title, $content, $orderIndex, $isLocked], "ssssis");
            
            if ($result) {
                $success = 'Lesson created successfully';
            } else {
                $error = 'Failed to create lesson';
            }
        }
    }
}

// Handle lesson update
if (isPost() && post('action') === 'update') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        $courseId = post('course_id');
        $title = post('title');
        $content = post('content');
        $orderIndex = post('order_index');
        $isLocked = post('is_locked') ? 1 : 0;
        
        $sql = "UPDATE lessons SET course_id = ?, title = ?, content = ?, order_index = ?, is_locked = ? 
                WHERE id = ?";
        $result = executeQuery($conn, $sql, [$courseId, $title, $content, $orderIndex, $isLocked, $id], "sssiss");
        
        if ($result) {
            $success = 'Lesson updated successfully';
        } else {
            $error = 'Failed to update lesson';
        }
    }
}

// Handle lesson deletion
if (isPost() && post('action') === 'delete') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        
        $sql = "DELETE FROM lessons WHERE id = ?";
        $result = executeQuery($conn, $sql, [$id], "s");
        
        if ($result) {
            $success = 'Lesson deleted successfully';
        } else {
            $error = 'Failed to delete lesson';
        }
    }
}

// Get all courses for dropdown
$sql = "SELECT id, title FROM courses ORDER BY title";
$result = executeQuery($conn, $sql);
$courses = fetchAll($result);

// Get filter
$filterCourse = get('course', '');

// Get all lessons
$sql = "SELECT l.*, c.title as course_title,
        (SELECT COUNT(*) FROM exercises WHERE lesson_id = l.id) as exercise_count
        FROM lessons l
        JOIN courses c ON l.course_id = c.id";

if ($filterCourse) {
    $sql .= " WHERE l.course_id = ?";
    $result = executeQuery($conn, $sql, [$filterCourse], "s");
} else {
    $result = executeQuery($conn, $sql);
}

$lessons = fetchAll($result);

// Get lesson for editing
$editLesson = null;
if (get('edit')) {
    $editId = get('edit');
    $sql = "SELECT * FROM lessons WHERE id = ?";
    $result = executeQuery($conn, $sql, [$editId], "s");
    $editLesson = fetchOne($result);
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Lessons</h1>
            <p class="text-gray-600 mt-2">Create and manage lessons</p>
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
    
    <!-- Create/Edit Form -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <?php echo $editLesson ? 'Edit Lesson' : 'Create New Lesson'; ?>
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="<?php echo $editLesson ? 'update' : 'create'; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">Lesson ID *</label>
                        <input type="text" id="id" name="id" required
                               value="<?php echo $editLesson ? htmlspecialchars($editLesson['id']) : ''; ?>"
                               <?php echo $editLesson ? 'readonly' : ''; ?>
                               placeholder="e.g., py-lesson-1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?php echo $editLesson ? 'bg-gray-100' : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course *</label>
                        <select id="course_id" name="course_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select course</option>
                            <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['id']); ?>"
                                    <?php echo ($editLesson && $editLesson['course_id'] === $course['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($course['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="title" name="title" required
                               value="<?php echo $editLesson ? htmlspecialchars($editLesson['title']) : ''; ?>"
                               placeholder="e.g., Introduction to Python"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content * (Markdown supported)</label>
                        <textarea id="content" name="content" rows="12" required
                                  placeholder="# Lesson Title&#10;&#10;Content here..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"><?php echo $editLesson ? htmlspecialchars($editLesson['content']) : ''; ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Use # for headings, ** for bold, ` for code</p>
                    </div>
                    
                    <div>
                        <label for="order_index" class="block text-sm font-medium text-gray-700 mb-1">Order *</label>
                        <input type="number" id="order_index" name="order_index" required min="1"
                               value="<?php echo $editLesson ? $editLesson['order_index'] : '1'; ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_locked" value="1"
                                   <?php echo ($editLesson && $editLesson['is_locked']) ? 'checked' : ''; ?>
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Locked (requires previous lesson completion)</span>
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        <?php echo $editLesson ? 'Update Lesson' : 'Create Lesson'; ?>
                    </button>
                    <?php if ($editLesson): ?>
                    <a href="/admin/lessons" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                        Cancel
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="filter_course" class="block text-sm font-medium text-gray-700 mb-1">Filter by Course</label>
                <select id="filter_course" name="course"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Courses</option>
                    <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['id']); ?>"
                            <?php echo $filterCourse === $course['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
            <?php if ($filterCourse): ?>
            <a href="/admin/lessons" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Clear
            </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Lessons List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">All Lessons</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lesson</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exercises</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($lessons)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No lessons yet</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($lessons as $lesson): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($lesson['title']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($lesson['id']); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($lesson['course_title']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $lesson['order_index']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $lesson['exercise_count']; ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded <?php echo $lesson['is_locked'] ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'; ?>">
                                    <?php echo $lesson['is_locked'] ? 'Locked' : 'Unlocked'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?edit=<?php echo urlencode($lesson['id']); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="" class="inline" onsubmit="return confirm('Delete this lesson? All exercises will be deleted.');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($lesson['id']); ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
