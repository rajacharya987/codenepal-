<?php
/**
 * Admin Exercise Management
 */

$pageTitle = 'Manage Exercises';
require_once __DIR__ . '/../includes/header.php';

requireAdmin();

$conn = getDBConnection();
$error = '';
$success = '';

// Handle exercise creation
if (isPost() && post('action') === 'create') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        $lessonId = post('lesson_id');
        $title = post('title');
        $description = post('description');
        $starterCode = post('starter_code');
        $solution = post('solution');
        $difficulty = post('difficulty');
        $points = post('points');
        
        if (empty($id) || empty($lessonId) || empty($title)) {
            $error = 'ID, lesson, and title are required';
        } else {
            $sql = "INSERT INTO exercises (id, lesson_id, title, description, starter_code, solution, difficulty, points) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result = executeQuery($conn, $sql, [$id, $lessonId, $title, $description, $starterCode, $solution, $difficulty, $points], "sssssssi");
            
            if ($result) {
                // Handle test cases
                $testInputs = post('test_input', []);
                $testOutputs = post('test_output', []);
                $testHidden = post('test_hidden', []);
                
                foreach ($testOutputs as $index => $output) {
                    if (!empty($output)) {
                        $input = $testInputs[$index] ?? '';
                        $hidden = isset($testHidden[$index]) ? 1 : 0;
                        
                        $sql = "INSERT INTO test_cases (exercise_id, input, expected_output, is_hidden, order_index) 
                                VALUES (?, ?, ?, ?, ?)";
                        executeQuery($conn, $sql, [$id, $input, $output, $hidden, $index + 1], "sssii");
                    }
                }
                
                $success = 'Exercise created successfully';
            } else {
                $error = 'Failed to create exercise';
            }
        }
    }
}

// Handle exercise deletion
if (isPost() && post('action') === 'delete') {
    $csrfToken = post('csrf_token');
    
    if (!validateCSRFToken($csrfToken)) {
        $error = 'Invalid request';
    } else {
        $id = post('id');
        
        $sql = "DELETE FROM exercises WHERE id = ?";
        $result = executeQuery($conn, $sql, [$id], "s");
        
        if ($result) {
            $success = 'Exercise deleted successfully';
        } else {
            $error = 'Failed to delete exercise';
        }
    }
}

// Get all lessons for dropdown
$sql = "SELECT l.id, l.title, c.title as course_title 
        FROM lessons l
        JOIN courses c ON l.course_id = c.id
        ORDER BY c.title, l.order_index";
$result = executeQuery($conn, $sql);
$lessons = fetchAll($result);

// Get filter
$filterLesson = get('lesson', '');

// Get all exercises
$sql = "SELECT e.*, l.title as lesson_title, c.title as course_title,
        (SELECT COUNT(*) FROM test_cases WHERE exercise_id = e.id) as test_count
        FROM exercises e
        JOIN lessons l ON e.lesson_id = l.id
        JOIN courses c ON l.course_id = c.id";

if ($filterLesson) {
    $sql .= " WHERE e.lesson_id = ?";
    $result = executeQuery($conn, $sql, [$filterLesson], "s");
} else {
    $result = executeQuery($conn, $sql);
}

$exercises = fetchAll($result);

// Get exercise for viewing
$viewExercise = null;
$viewTestCases = [];
if (get('view')) {
    $viewId = get('view');
    $sql = "SELECT * FROM exercises WHERE id = ?";
    $result = executeQuery($conn, $sql, [$viewId], "s");
    $viewExercise = fetchOne($result);
    
    if ($viewExercise) {
        $sql = "SELECT * FROM test_cases WHERE exercise_id = ? ORDER BY order_index";
        $result = executeQuery($conn, $sql, [$viewId], "s");
        $viewTestCases = fetchAll($result);
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Exercises</h1>
            <p class="text-gray-600 mt-2">Create and manage coding exercises</p>
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
    
    <?php if ($viewExercise): ?>
    <!-- View Exercise Details -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Exercise Details</h2>
            <a href="/admin/exercises" class="text-blue-600 hover:text-blue-800">← Back to List</a>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">ID</p>
                    <p class="font-medium"><?php echo htmlspecialchars($viewExercise['id']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Title</p>
                    <p class="font-medium"><?php echo htmlspecialchars($viewExercise['title']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Difficulty</p>
                    <p class="font-medium"><?php echo ucfirst($viewExercise['difficulty']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Points</p>
                    <p class="font-medium"><?php echo $viewExercise['points']; ?></p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Description</p>
                <p class="text-gray-900"><?php echo nl2br(htmlspecialchars($viewExercise['description'])); ?></p>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Starter Code</p>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto"><code><?php echo htmlspecialchars($viewExercise['starter_code']); ?></code></pre>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Solution</p>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto"><code><?php echo htmlspecialchars($viewExercise['solution']); ?></code></pre>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-2">Test Cases (<?php echo count($viewTestCases); ?>)</p>
                <?php if (empty($viewTestCases)): ?>
                    <p class="text-gray-500">No test cases</p>
                <?php else: ?>
                    <div class="space-y-2">
                        <?php foreach ($viewTestCases as $tc): ?>
                        <div class="bg-gray-50 p-3 rounded">
                            <?php if ($tc['input']): ?>
                            <p class="text-sm"><span class="font-medium">Input:</span> <code><?php echo htmlspecialchars($tc['input']); ?></code></p>
                            <?php endif; ?>
                            <p class="text-sm"><span class="font-medium">Expected:</span> <code><?php echo htmlspecialchars($tc['expected_output']); ?></code></p>
                            <?php if ($tc['is_hidden']): ?>
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Hidden</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Create New Exercise</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">Exercise ID *</label>
                        <input type="text" id="id" name="id" required
                               placeholder="e.g., py-ex-1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-1">Lesson *</label>
                        <select id="lesson_id" name="lesson_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select lesson</option>
                            <?php foreach ($lessons as $lesson): ?>
                            <option value="<?php echo htmlspecialchars($lesson['id']); ?>">
                                <?php echo htmlspecialchars($lesson['course_title'] . ' - ' . $lesson['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="title" name="title" required
                               placeholder="e.g., Print Hello World"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea id="description" name="description" rows="3" required
                                  placeholder="Exercise description..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="starter_code" class="block text-sm font-medium text-gray-700 mb-1">Starter Code *</label>
                        <textarea id="starter_code" name="starter_code" rows="6" required
                                  placeholder="# Write your code here"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="solution" class="block text-sm font-medium text-gray-700 mb-1">Solution *</label>
                        <textarea id="solution" name="solution" rows="6" required
                                  placeholder="print('Hello, World!')"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"></textarea>
                    </div>
                    
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty *</label>
                        <select id="difficulty" name="difficulty" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points *</label>
                        <input type="number" id="points" name="points" required min="1" value="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <!-- Test Cases -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Cases</h3>
                    <div id="test-cases-container" class="space-y-4">
                        <div class="test-case border border-gray-300 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Input (optional)</label>
                                    <input type="text" name="test_input[]" placeholder="Input value"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Output *</label>
                                    <input type="text" name="test_output[]" required placeholder="Expected output"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <label class="flex items-center mt-2">
                                <input type="checkbox" name="test_hidden[]" value="1" class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Hidden test case</span>
                            </label>
                        </div>
                    </div>
                    <button type="button" onclick="addTestCase()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        + Add Test Case
                    </button>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        Create Exercise
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="filter_lesson" class="block text-sm font-medium text-gray-700 mb-1">Filter by Lesson</label>
                <select id="filter_lesson" name="lesson"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Lessons</option>
                    <?php foreach ($lessons as $lesson): ?>
                    <option value="<?php echo htmlspecialchars($lesson['id']); ?>"
                            <?php echo $filterLesson === $lesson['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lesson['course_title'] . ' - ' . $lesson['title']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
            <?php if ($filterLesson): ?>
            <a href="/admin/exercises" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Clear
            </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Exercises List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">All Exercises</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exercise</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lesson</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($exercises)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No exercises yet</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($exercises as $exercise): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($exercise['title']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($exercise['id']); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($exercise['lesson_title']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($exercise['course_title']); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded <?php 
                                    echo $exercise['difficulty'] === 'easy' ? 'bg-green-100 text-green-700' : 
                                        ($exercise['difficulty'] === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'); 
                                ?>">
                                    <?php echo ucfirst($exercise['difficulty']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $exercise['points']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo $exercise['test_count']; ?></td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?view=<?php echo urlencode($exercise['id']); ?>" class="text-blue-600 hover:text-blue-800">View</a>
                                <form method="POST" action="" class="inline" onsubmit="return confirm('Delete this exercise?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($exercise['id']); ?>">
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

<script>
function addTestCase() {
    const container = document.getElementById('test-cases-container');
    const testCase = document.createElement('div');
    testCase.className = 'test-case border border-gray-300 rounded-lg p-4';
    testCase.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Input (optional)</label>
                <input type="text" name="test_input[]" placeholder="Input value"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Output *</label>
                <input type="text" name="test_output[]" required placeholder="Expected output"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
        <label class="flex items-center mt-2">
            <input type="checkbox" name="test_hidden[]" value="1" class="rounded border-gray-300">
            <span class="ml-2 text-sm text-gray-700">Hidden test case</span>
        </label>
    `;
    container.appendChild(testCase);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
