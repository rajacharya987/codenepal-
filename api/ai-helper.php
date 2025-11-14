<?php
/**
 * AI Helper API using Gemini
 * Provides hints, code improvement, and validation
 */

// Set headers to prevent caching and ensure JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php'

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

$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'validate_code':
            $result = validateCodeWithAI($input);
            echo json_encode($result);
            break;
            
        case 'get_hint':
            $result = getAIHint($input);
            echo json_encode($result);
            break;
            
        case 'suggest_improvement':
            $result = suggestImprovement($input);
            echo json_encode($result);
            break;
            
        case 'explain_error':
            $result = explainError($input);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    error_log('AI Helper error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'AI service error: ' . $e->getMessage()]);
}

/**
 * Validate code using AI
 */
function validateCodeWithAI($input) {
    $code = $input['user_code'] ?? '';
    $language = $input['language'] ?? 'python';
    $exerciseDescription = $input['exercise_description'] ?? '';
    $expectedOutput = $input['expected_output'] ?? '';
    
    $prompt = "You are a code validator for a programming learning platform.

Exercise Description: {$exerciseDescription}
Expected Output: {$expectedOutput}
Programming Language: {$language}

User's Code:
```{$language}
{$code}
```

Analyze this code and determine if it correctly solves the exercise. Consider:
1. Does it produce the expected output?
2. Is the logic correct?
3. Are there any errors or issues?

Respond in this EXACT format:
STATUS: [PASS or FAIL]
REASON: [Brief explanation in 1-2 sentences]
OUTPUT: [What the code would output]

Do not use bold formatting or special characters like *** or '''. Just plain text.";

    $response = callGeminiAPI($prompt);
    
    // Parse response
    $passed = stripos($response, 'STATUS: PASS') !== false;
    $lines = explode("\n", $response);
    $reason = '';
    $output = '';
    
    foreach ($lines as $line) {
        if (stripos($line, 'REASON:') !== false) {
            $reason = trim(str_replace(['REASON:', 'reason:'], '', $line));
        }
        if (stripos($line, 'OUTPUT:') !== false) {
            $output = trim(str_replace(['OUTPUT:', 'output:'], '', $line));
        }
    }
    
    // Clean up any bold markers
    $reason = cleanAIText($reason);
    $output = cleanAIText($output);
    
    return [
        'success' => true,
        'passed' => $passed,
        'reason' => $reason ?: 'Code analysis completed',
        'output' => $output ?: 'No output detected',
        'raw_response' => $response
    ];
}

/**
 * Get AI hint
 */
function getAIHint($input) {
    $exerciseTitle = $input['exercise_title'] ?? '';
    $exerciseDescription = $input['exercise_description'] ?? '';
    $userCode = $input['user_code'] ?? '';
    $language = $input['language'] ?? 'python';
    
    $prompt = "You are a helpful programming tutor.

Exercise: {$exerciseTitle}
Description: {$exerciseDescription}
Language: {$language}

User's current code:
```{$language}
{$userCode}
```

Provide a helpful hint (not the full solution) to guide the student. Keep it brief (2-3 sentences).
Do not use bold formatting or special characters like *** or '''. Just plain text.";

    $response = callGeminiAPI($prompt);
    
    return [
        'success' => true,
        'hint' => cleanAIText($response)
    ];
}

/**
 * Suggest code improvements
 */
function suggestImprovement($input) {
    $userCode = $input['user_code'] ?? '';
    $language = $input['language'] ?? 'python';
    $exerciseDescription = $input['exercise_description'] ?? '';
    
    $prompt = "You are a code review expert.

Exercise: {$exerciseDescription}
Language: {$language}

User's code:
```{$language}
{$userCode}
```

Provide 2-3 specific suggestions to improve this code (better practices, efficiency, readability).
Keep it concise and actionable.
Do not use bold formatting or special characters like *** or '''. Just plain text.";

    $response = callGeminiAPI($prompt);
    
    return [
        'success' => true,
        'suggestions' => cleanAIText($response)
    ];
}

/**
 * Explain error
 */
function explainError($input) {
    $error = $input['error'] ?? '';
    $userCode = $input['user_code'] ?? '';
    $language = $input['language'] ?? 'python';
    
    $prompt = "You are a debugging assistant.

Language: {$language}
Error: {$error}

User's code:
```{$language}
{$userCode}
```

Explain what caused this error and how to fix it. Keep it simple and clear (2-3 sentences).
Do not use bold formatting or special characters like *** or '''. Just plain text.";

    $response = callGeminiAPI($prompt);
    
    return [
        'success' => true,
        'explanation' => cleanAIText($response)
    ];
}

/**
 * Call Gemini API
 */
function callGeminiAPI($prompt) {
    $apiKey = GEMINI_API_KEY;
    $url = GEMINI_API_URL . '?key=' . $apiKey;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 500
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception('Gemini API error: ' . $response);
    }
    
    $result = json_decode($response, true);
    
    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        throw new Exception('Invalid Gemini API response');
    }
    
    return $result['candidates'][0]['content']['parts'][0]['text'];
}

/**
 * Clean AI text from bold markers and special characters
 */
function cleanAIText($text) {
    // Remove bold markers
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '$1', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/', '$1', $text);
    $text = preg_replace('/\*(.+?)\*/', '$1', $text);
    
    // Remove triple quotes
    $text = str_replace("'''", '', $text);
    $text = str_replace('```', '', $text);
    
    // Clean up extra whitespace
    $text = trim($text);
    
    return $text;
}
