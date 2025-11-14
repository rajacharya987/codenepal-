<?php
/**
 * Lesson Page with Exercises
 */

require_once __DIR__ . '/../includes/header.php';

requireLogin();

$lessonId = get('id');
if (!$lessonId) {
    redirect('/pages/courses');
}

$conn = getDBConnection();
$userId = $currentUser['id'];

// Get lesson details with course info
$sql = "SELECT l.*, c.id as course_id, c.title as course_title, c.language,
        (SELECT COUNT(*) FROM completed_lessons WHERE lesson_id = l.id AND user_id = ?) as is_completed
        FROM lessons l
        JOIN courses c ON l.course_id = c.id
        WHERE l.id = ?";
$result = executeQuery($conn, $sql, [$userId, $lessonId], "ss");
$lesson = fetchOne($result);

if (!$lesson) {
    setFlashMessage('error', 'Lesson not found');
    redirect('/pages/courses');
}

$pageTitle = $lesson['title'];

// Get exercises for this lesson
$sql = "SELECT e.*, 
        (SELECT COUNT(*) FROM completed_exercises WHERE exercise_id = e.id AND user_id = ?) as is_completed
        FROM exercises e
        WHERE e.lesson_id = ?
        ORDER BY e.id";
$result = executeQuery($conn, $sql, [$userId, $lessonId], "ss");
$exercises = fetchAll($result);

// Get test cases for exercises (only visible ones for display)
$exerciseTestCases = [];
if (!empty($exercises)) {
    $exerciseIds = array_column($exercises, 'id');
    $placeholders = implode(',', array_fill(0, count($exerciseIds), '?'));
    $sql = "SELECT * FROM test_cases WHERE exercise_id IN ($placeholders) AND is_hidden = 0 ORDER BY order_index";
    $result = executeQuery($conn, $sql, $exerciseIds, str_repeat('s', count($exerciseIds)));
    $testCases = fetchAll($result);
    
    foreach ($testCases as $tc) {
        $exerciseTestCases[$tc['exercise_id']][] = $tc;
    }
}

// Get hints for exercises
$exerciseHints = [];
if (!empty($exercises)) {
    $exerciseIds = array_column($exercises, 'id');
    $placeholders = implode(',', array_fill(0, count($exerciseIds), '?'));
    $sql = "SELECT * FROM hints WHERE exercise_id IN ($placeholders) ORDER BY order_index";
    $result = executeQuery($conn, $sql, $exerciseIds, str_repeat('s', count($exerciseIds)));
    $hints = fetchAll($result);
    
    foreach ($hints as $hint) {
        $exerciseHints[$hint['exercise_id']][] = $hint;
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/pages/courses" class="hover:text-blue-600">Courses</a></li>
            <li>/</li>
            <li><a href="/pages/course?id=<?php echo urlencode($lesson['course_id']); ?>" class="hover:text-blue-600">
                <?php echo htmlspecialchars($lesson['course_title']); ?>
            </a></li>
            <li>/</li>
            <li class="text-gray-900 font-medium"><?php echo htmlspecialchars($lesson['title']); ?></li>
        </ol>
    </nav>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Lesson Content -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6"><?php echo htmlspecialchars($lesson['title']); ?></h1>
            
            <div class="prose max-w-none lesson-content">
                <?php 
                // Enhanced content rendering with HTML support
                $content = $lesson['content'];
                
                // Check if content contains HTML tags
                if (strip_tags($content) !== $content) {
                    // Content has HTML, render it directly (with sanitization for safety)
                    echo $content;
                } else {
                    // Simple markdown-like rendering for plain text
                    $content = preg_replace('/^# (.+)$/m', '<h1 class="text-2xl font-bold mt-6 mb-4">$1</h1>', $content);
                    $content = preg_replace('/^## (.+)$/m', '<h2 class="text-xl font-bold mt-4 mb-3">$1</h2>', $content);
                    $content = preg_replace('/^### (.+)$/m', '<h3 class="text-lg font-semibold mt-3 mb-2">$1</h3>', $content);
                    $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
                    $content = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $content);
                    $content = preg_replace('/```(\w+)?\n(.*?)\n```/s', '<pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto"><code>$2</code></pre>', $content);
                    $content = preg_replace('/`(.+?)`/', '<code class="bg-gray-100 px-2 py-1 rounded text-sm">$1</code>', $content);
                    $content = nl2br($content);
                    echo $content;
                }
                ?>
            </div>
            
            <?php if ($lesson['is_completed']): ?>
            <div class="mt-6 p-4 bg-green-100 border border-green-300 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium">Lesson Completed!</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Exercises -->
        <div class="space-y-6">
            <?php if (empty($exercises)): ?>
                <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                    No exercises available for this lesson
                </div>
            <?php else: ?>
                <?php foreach ($exercises as $index => $exercise): ?>
                <div class="bg-white rounded-lg shadow-md p-6" id="exercise-<?php echo $exercise['id']; ?>">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                Exercise <?php echo $index + 1; ?>: <?php echo htmlspecialchars($exercise['title']); ?>
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-<?php echo $exercise['difficulty'] === 'easy' ? 'green' : ($exercise['difficulty'] === 'medium' ? 'yellow' : 'red'); ?>-100 text-<?php echo $exercise['difficulty'] === 'easy' ? 'green' : ($exercise['difficulty'] === 'medium' ? 'yellow' : 'red'); ?>-700 text-xs rounded">
                                    <?php echo ucfirst($exercise['difficulty']); ?>
                                </span>
                                <span class="text-sm text-gray-600">⭐ <?php echo $exercise['points']; ?> points</span>
                                <?php if ($exercise['is_completed']): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">✓ Completed</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($exercise['description'])); ?></p>
                    
                    <!-- Test Cases -->
                    <?php if (isset($exerciseTestCases[$exercise['id']])): ?>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Test Cases:</h4>
                        <div class="space-y-2">
                            <?php foreach ($exerciseTestCases[$exercise['id']] as $tc): ?>
                            <div class="bg-gray-50 p-3 rounded text-sm" 
                                 data-test-input="<?php echo htmlspecialchars($tc['input'] ?? ''); ?>"
                                 data-test-expected="<?php echo htmlspecialchars($tc['expected_output']); ?>">
                                <?php if ($tc['input']): ?>
                                <div><span class="font-medium">Input:</span> <code><?php echo htmlspecialchars($tc['input']); ?></code></div>
                                <?php endif; ?>
                                <div><span class="font-medium">Expected Output:</span> <code><?php echo htmlspecialchars($tc['expected_output']); ?></code></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Execution Mode Selector -->
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-900 mb-2">Execution Mode:</label>
                        <div class="flex gap-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="exec-mode-<?php echo $exercise['id']; ?>" value="browser" checked
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm">🌐 Browser (Fast)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="exec-mode-<?php echo $exercise['id']; ?>" value="ai"
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm">🤖 AI Validate (Smart)</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Browser: Runs code directly. AI: Validates logic and output.</p>
                    </div>
                    
                    <!-- Code Editor -->
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-900 mb-2">Your Code:</label>
                        <textarea id="code-editor-<?php echo $exercise['id']; ?>" 
                                  class="code-editor w-full h-64 p-4 border border-gray-300 rounded-lg font-mono text-sm"
                                  data-language="<?php echo $lesson['language']; ?>"><?php echo htmlspecialchars($exercise['starter_code']); ?></textarea>
                    </div>
                    
                    <!-- Hints -->
                    <?php if (isset($exerciseHints[$exercise['id']])): ?>
                    <div class="mb-4">
                        <button type="button" 
                                onclick="toggleHints('<?php echo $exercise['id']; ?>')"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            💡 Show Hints (<?php echo count($exerciseHints[$exercise['id']]); ?>)
                        </button>
                        <div id="hints-<?php echo $exercise['id']; ?>" class="hidden mt-2 space-y-2">
                            <?php foreach ($exerciseHints[$exercise['id']] as $hintIndex => $hint): ?>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 text-sm">
                                <strong>Hint <?php echo $hintIndex + 1; ?>:</strong> <?php echo htmlspecialchars($hint['hint_text']); ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- AI Helper -->
                    <div class="mb-4 flex gap-2">
                        <button type="button" 
                                class="ai-hint-btn text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center gap-1"
                                data-exercise-id="<?php echo $exercise['id']; ?>"
                                data-exercise-title="<?php echo htmlspecialchars($exercise['title']); ?>"
                                data-exercise-description="<?php echo htmlspecialchars($exercise['description']); ?>"
                                data-language="<?php echo $lesson['language']; ?>">
                            🤖 AI Hint
                        </button>
                        <button type="button" 
                                class="ai-improve-btn text-green-600 hover:text-green-800 text-sm font-medium flex items-center gap-1"
                                data-exercise-id="<?php echo $exercise['id']; ?>"
                                data-exercise-description="<?php echo htmlspecialchars($exercise['description']); ?>"
                                data-language="<?php echo $lesson['language']; ?>">
                            ✨ Improve Code
                        </button>
                    </div>
                    
                    <!-- AI Response Area -->
                    <div id="ai-response-<?php echo $exercise['id']; ?>" class="hidden mb-4"></div>
                    
                    <!-- Submit Button -->
                    <button type="button" 
                            onclick="runCode('<?php echo $exercise['id']; ?>', '<?php echo $lesson['language']; ?>')"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Run Code
                    </button>
                    
                    <!-- Results -->
                    <div id="results-<?php echo $exercise['id']; ?>" class="mt-4 hidden"></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- CodeMirror JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>

<script src="/assets/js/editor.js?v=<?php echo time(); ?>"></script>

<script>
function toggleHints(exerciseId) {
    const hintsDiv = document.getElementById('hints-' + exerciseId);
    hintsDiv.classList.toggle('hidden');
}

// Verify editor.js is loaded
console.log('Editor.js loaded:', typeof executeCodeClient !== 'undefined');
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
