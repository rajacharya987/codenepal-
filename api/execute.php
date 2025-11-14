<?php
/**
 * Code Execution API
 * Handles code execution requests
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

// Check authentication
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$code = $input['code'] ?? '';
$language = $input['language'] ?? '';
$exerciseId = $input['exercise_id'] ?? '';

// Validate input
if (empty($code) || empty($language)) {
    echo json_encode(['success' => false, 'message' => 'Code and language are required']);
    exit;
}

// Validate language
if (!in_array($language, ['python', 'javascript', 'cpp'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid language']);
    exit;
}

// Validate code
$validation = validateCode($code, $language);
if (!$validation['valid']) {
    echo json_encode([
        'success' => false,
        'message' => 'Code validation failed',
        'errors' => implode(', ', $validation['errors'])
    ]);
    exit;
}

// Get test cases if exercise ID provided
$testCases = [];
if ($exerciseId) {
    $conn = getDBConnection();
    $sql = "SELECT * FROM test_cases WHERE exercise_id = ? ORDER BY order_index";
    $result = executeQuery($conn, $sql, [$exerciseId], "s");
    $testCases = fetchAll($result);
}

// Execute code
try {
    $result = executeCode($code, $language, $testCases);
    echo json_encode($result);
} catch (Exception $e) {
    logError("Code execution error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Execution failed: ' . $e->getMessage()
    ]);
}

/**
 * Validate code for dangerous patterns
 */
function validateCode($code, $language) {
    $errors = [];
    
    // Check code length
    if (strlen($code) > MAX_CODE_LENGTH) {
        $errors[] = 'Code is too long (max ' . MAX_CODE_LENGTH . ' characters)';
    }
    
    // Common dangerous patterns
    $dangerousPatterns = [
        '/\bexec\s*\(/' => 'exec() function',
        '/\beval\s*\(/' => 'eval() function',
        '/\bsystem\s*\(/' => 'system() function',
        '/\bpassthru\s*\(/' => 'passthru() function',
        '/\bshell_exec\s*\(/' => 'shell_exec() function',
        '/\bpopen\s*\(/' => 'popen() function',
        '/\bproc_open\s*\(/' => 'proc_open() function',
    ];
    
    // Language-specific dangerous patterns
    if ($language === 'python') {
        $dangerousPatterns['/\b__import__\s*\(/'] = '__import__() function';
        $dangerousPatterns['/\bopen\s*\(/'] = 'open() function';
        $dangerousPatterns['/\bfile\s*\(/'] = 'file() function';
        $dangerousPatterns['/import\s+os\b/'] = 'os module';
        $dangerousPatterns['/import\s+subprocess\b/'] = 'subprocess module';
        $dangerousPatterns['/import\s+sys\b/'] = 'sys module';
    } elseif ($language === 'javascript') {
        $dangerousPatterns['/require\s*\(\s*[\'"]fs[\'"]\s*\)/'] = 'fs module';
        $dangerousPatterns['/require\s*\(\s*[\'"]child_process[\'"]\s*\)/'] = 'child_process module';
    } elseif ($language === 'cpp') {
        $dangerousPatterns['/#include\s*<fstream>/'] = 'fstream header';
        $dangerousPatterns['/#include\s*<cstdlib>/'] = 'cstdlib header';
        $dangerousPatterns['/\bsystem\s*\(/'] = 'system() function';
    }
    
    foreach ($dangerousPatterns as $pattern => $description) {
        if (preg_match($pattern, $code)) {
            $errors[] = "Dangerous pattern detected: $description";
        }
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Execute code
 */
function executeCode($code, $language, $testCases = []) {
    $startTime = microtime(true);
    
    // Create temp directory if it doesn't exist
    if (!is_dir(TEMP_DIR)) {
        mkdir(TEMP_DIR, 0755, true);
    }
    
    // Generate unique filename
    $filename = uniqid('code_') . '_' . time();
    
    try {
        switch ($language) {
            case 'python':
                $result = executePython($code, $testCases, $filename);
                break;
            case 'javascript':
                $result = executeJavaScript($code, $testCases, $filename);
                break;
            case 'cpp':
                $result = executeCpp($code, $testCases, $filename);
                break;
            default:
                throw new Exception('Unsupported language');
        }
        
        $executionTime = round(microtime(true) - $startTime, 3);
        $result['execution_time'] = $executionTime;
        
        return $result;
    } catch (Exception $e) {
        throw $e;
    }
}

/**
 * Execute Python code
 */
function executePython($code, $testCases, $filename) {
    $filepath = TEMP_DIR . $filename . '.py';
    file_put_contents($filepath, $code);
    
    $output = '';
    $errors = '';
    $testResults = [];
    
    if (empty($testCases)) {
        // Just run the code
        $command = PYTHON_PATH . ' ' . escapeshellarg($filepath) . ' 2>&1';
        exec($command, $outputLines, $returnCode);
        $output = implode("\n", $outputLines);
        
        if ($returnCode !== 0) {
            $errors = $output;
            $output = '';
        }
    } else {
        // Run with test cases
        foreach ($testCases as $testCase) {
            $input = $testCase['input'] ?? '';
            $expected = trim($testCase['expected_output']);
            
            // Create input file if needed
            if ($input) {
                $inputFile = TEMP_DIR . $filename . '_input.txt';
                file_put_contents($inputFile, $input);
                $command = PYTHON_PATH . ' ' . escapeshellarg($filepath) . ' < ' . escapeshellarg($inputFile) . ' 2>&1';
            } else {
                $command = PYTHON_PATH . ' ' . escapeshellarg($filepath) . ' 2>&1';
            }
            
            exec($command, $outputLines, $returnCode);
            $actual = trim(implode("\n", $outputLines));
            
            $testResults[] = [
                'passed' => $actual === $expected,
                'input' => $input,
                'expected' => $expected,
                'actual' => $actual
            ];
            
            $outputLines = [];
            
            // Clean up input file
            if ($input && isset($inputFile)) {
                @unlink($inputFile);
            }
        }
    }
    
    // Clean up
    @unlink($filepath);
    
    return [
        'success' => true,
        'output' => $output,
        'errors' => $errors,
        'test_results' => $testResults
    ];
}

/**
 * Execute JavaScript code
 */
function executeJavaScript($code, $testCases, $filename) {
    $filepath = TEMP_DIR . $filename . '.js';
    file_put_contents($filepath, $code);
    
    $output = '';
    $errors = '';
    $testResults = [];
    
    if (empty($testCases)) {
        $command = NODE_PATH . ' ' . escapeshellarg($filepath) . ' 2>&1';
        exec($command, $outputLines, $returnCode);
        $output = implode("\n", $outputLines);
        
        if ($returnCode !== 0) {
            $errors = $output;
            $output = '';
        }
    } else {
        foreach ($testCases as $testCase) {
            $input = $testCase['input'] ?? '';
            $expected = trim($testCase['expected_output']);
            
            if ($input) {
                $inputFile = TEMP_DIR . $filename . '_input.txt';
                file_put_contents($inputFile, $input);
                $command = NODE_PATH . ' ' . escapeshellarg($filepath) . ' < ' . escapeshellarg($inputFile) . ' 2>&1';
            } else {
                $command = NODE_PATH . ' ' . escapeshellarg($filepath) . ' 2>&1';
            }
            
            exec($command, $outputLines, $returnCode);
            $actual = trim(implode("\n", $outputLines));
            
            $testResults[] = [
                'passed' => $actual === $expected,
                'input' => $input,
                'expected' => $expected,
                'actual' => $actual
            ];
            
            $outputLines = [];
            
            if ($input && isset($inputFile)) {
                @unlink($inputFile);
            }
        }
    }
    
    @unlink($filepath);
    
    return [
        'success' => true,
        'output' => $output,
        'errors' => $errors,
        'test_results' => $testResults
    ];
}

/**
 * Execute C++ code
 */
function executeCpp($code, $testCases, $filename) {
    $sourceFile = TEMP_DIR . $filename . '.cpp';
    $binaryFile = TEMP_DIR . $filename . '.exe';
    
    file_put_contents($sourceFile, $code);
    
    // Compile
    $compileCommand = GCC_PATH . ' ' . escapeshellarg($sourceFile) . ' -o ' . escapeshellarg($binaryFile) . ' 2>&1';
    exec($compileCommand, $compileOutput, $compileReturn);
    
    if ($compileReturn !== 0) {
        @unlink($sourceFile);
        return [
            'success' => false,
            'output' => '',
            'errors' => implode("\n", $compileOutput),
            'test_results' => []
        ];
    }
    
    $output = '';
    $errors = '';
    $testResults = [];
    
    if (empty($testCases)) {
        $command = escapeshellarg($binaryFile) . ' 2>&1';
        exec($command, $outputLines, $returnCode);
        $output = implode("\n", $outputLines);
        
        if ($returnCode !== 0) {
            $errors = $output;
            $output = '';
        }
    } else {
        foreach ($testCases as $testCase) {
            $input = $testCase['input'] ?? '';
            $expected = trim($testCase['expected_output']);
            
            if ($input) {
                $inputFile = TEMP_DIR . $filename . '_input.txt';
                file_put_contents($inputFile, $input);
                $command = escapeshellarg($binaryFile) . ' < ' . escapeshellarg($inputFile) . ' 2>&1';
            } else {
                $command = escapeshellarg($binaryFile) . ' 2>&1';
            }
            
            exec($command, $outputLines, $returnCode);
            $actual = trim(implode("\n", $outputLines));
            
            $testResults[] = [
                'passed' => $actual === $expected,
                'input' => $input,
                'expected' => $expected,
                'actual' => $actual
            ];
            
            $outputLines = [];
            
            if ($input && isset($inputFile)) {
                @unlink($inputFile);
            }
        }
    }
    
    // Clean up
    @unlink($sourceFile);
    @unlink($binaryFile);
    
    return [
        'success' => true,
        'output' => $output,
        'errors' => $errors,
        'test_results' => $testResults
    ];
}
