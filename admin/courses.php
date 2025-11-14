<?php
/**
 * Admin Course Management
 */

$pageTitle = 'Manage Courses';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

$conn = getDBConnection();
$error = '';
$success = '';

// Handle course creation
if (isPost() && post('action') === 'create') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        $title = post('title');
        $description = post('description');
        $language = post('language');
        $category = post('category');
        $duration = post('duration');
        $isPublished = post('is_published') ? 1 : 0;
        $isFree = post('is_free') ? 1 : 0;
        
        if (empty($id) || empty($title) || empty($language) || empty($category)) {
            $error = 'ID, title, language, and category are required';
        } else {
            // Check if ID already exists
            $sql = "SELECT id FROM courses WHERE id = ?";
            $result = executeQuery($conn, $sql, [$id], "s");
            
            if ($result && $result->num_rows > 0) {
                $error = 'Course ID already exists';
            } else {
                $sql = "INSERT INTO courses (id, title, description, language, category, duration, is_published, is_free) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $result = executeQuery($conn, $sql, [$id, $title, $description, $language, $category, $duration, $isPublished, $isFree], "ssssssii");
                
                if ($result) {
                    $success = 'Course created successfully';
                } else {
                    $error = 'Failed to create course';
                }
            }
        }
    }
}

// Handle course update
if (isPost() && post('action') === 'update') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        $title = post('title');
        $description = post('description');
        $language = post('language');
        $category = post('category');
        $duration = post('duration');
        $isPublished = post('is_published') ? 1 : 0;
        $isFree = post('is_free') ? 1 : 0;
        
        $sql = "UPDATE courses SET title = ?, description = ?, language = ?, category = ?, duration = ?, is_published = ?, is_free = ? 
                WHERE id = ?";
        $result = executeQuery($conn, $sql, [$title, $description, $language, $category, $duration, $isPublished, $isFree, $id], "sssssiss");
        
        if ($result) {
            $success = 'Course updated successfully';
        } else {
            $error = 'Failed to update course';
        }
    }
}

// Handle course deletion
if (isPost() && post('action') === 'delete') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        
        $sql = "DELETE FROM courses WHERE id = ?";
        $result = executeQuery($conn, $sql, [$id], "s");
        
        if ($result) {
            $success = 'Course deleted successfully';
        } else {
            $error = 'Failed to delete course';
        }
    }
}

// Get all courses
$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
        (SELECT COUNT(*) FROM user_progress WHERE course_id = c.id) as enrollment_count
        FROM courses c
        ORDER BY c.created_at DESC";
$result = executeQuery($conn, $sql);
$courses = fetchAll($result);

// Get course for editing if edit parameter is set
$editCourse = null;
if (get('edit')) {
    $editId = get('edit');
    $sql = "SELECT * FROM courses WHERE id = ?";
    $result = executeQuery($conn, $sql, [$editId], "s");
    $editCourse = fetchOne($result);
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Courses</h1>
            <p class="text-gray-600 mt-2">Create and manage courses</p>
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
                <?php echo $editCourse ? 'Edit Course' : 'Create New Course'; ?>
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="<?php echo $editCourse ? 'update' : 'create'; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">Course ID *</label>
                        <input type="text" id="id" name="id" required
                               value="<?php echo $editCourse ? htmlspecialchars($editCourse['id']) : ''; ?>"
                               <?php echo $editCourse ? 'readonly' : ''; ?>
                               placeholder="e.g., python-basics"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?php echo $editCourse ? 'bg-gray-100' : ''; ?>">
                        <p class="text-xs text-gray-500 mt-1">Unique identifier (lowercase, hyphens only)</p>
                    </div>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="title" name="title" required
                               value="<?php echo $editCourse ? htmlspecialchars($editCourse['title']) : ''; ?>"
                               placeholder="e.g., Python Basics"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="Course description..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo $editCourse ? htmlspecialchars($editCourse['description']) : ''; ?></textarea>
                    </div>
                    
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language *</label>
                        <select id="language" name="language" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select language</option>
                            <option value="python" <?php echo ($editCourse && $editCourse['language'] === 'python') ? 'selected' : ''; ?>>Python</option>
                            <option value="javascript" <?php echo ($editCourse && $editCourse['language'] === 'javascript') ? 'selected' : ''; ?>>JavaScript</option>
                            <option value="cpp" <?php echo ($editCourse && $editCourse['language'] === 'cpp') ? 'selected' : ''; ?>>C++</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select category</option>
                            <option value="beginner" <?php echo ($editCourse && $editCourse['category'] === 'beginner') ? 'selected' : ''; ?>>Beginner</option>
                            <option value="intermediate" <?php echo ($editCourse && $editCourse['category'] === 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="advanced" <?php echo ($editCourse && $editCourse['category'] === 'advanced') ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                        <input type="text" id="duration" name="duration"
                               value="<?php echo $editCourse ? htmlspecialchars($editCourse['duration']) : ''; ?>"
                               placeholder="e.g., 4 weeks"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1"
                                   <?php echo ($editCourse && $editCourse['is_published']) ? 'checked' : ''; ?>
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Published</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_free" value="1"
                                   <?php echo (!$editCourse || $editCourse['is_free']) ? 'checked' : ''; ?>
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Free</span>
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        <?php echo $editCourse ? 'Update Course' : 'Create Course'; ?>
                    </button>
                    <?php if ($editCourse): ?>
                    <a href="/admin/courses" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                        Cancel
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Courses List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">All Courses</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lessons</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No courses yet</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($course['title']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($course['id']); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo ucfirst($course['language']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo ucfirst($course['category']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $course['lesson_count']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $course['enrollment_count']; ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded <?php echo $course['is_published'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?>">
                                    <?php echo $course['is_published'] ? 'Published' : 'Draft'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?edit=<?php echo urlencode($course['id']); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="" class="inline" onsubmit="return confirm('Delete this course? All lessons and exercises will be deleted.');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($course['id']); ?>">
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
