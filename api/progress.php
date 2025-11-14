<?php
/**
 * Progress Tracking API
 * Handles lesson and exercise completion
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

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
$userId = $_SESSION['user_id'];
$conn = getDBConnection();

switch ($action) {
    case 'complete_lesson':
        $lessonId = $input['lesson_id'] ?? '';
        if (empty($lessonId)) {
            echo json_encode(['success' => false, 'message' => 'Lesson ID required']);
            exit;
        }
        
        // Check if already completed
        $sql = "SELECT id FROM completed_lessons WHERE user_id = ? AND lesson_id = ?";
        $result = executeQuery($conn, $sql, [$userId, $lessonId], "ss");
        
        if ($result && $result->num_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Already completed']);
            exit;
        }
        
        // Mark as completed
        $sql = "INSERT INTO completed_lessons (user_id, lesson_id) VALUES (?, ?)";
        $result = executeQuery($conn, $sql, [$userId, $lessonId], "ss");
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Lesson marked as complete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to mark lesson as complete']);
        }
        break;
        
    case 'complete_exercise':
        $exerciseId = $input['exercise_id'] ?? '';
        $score = $input['score'] ?? 100;
        
        if (empty($exerciseId)) {
            echo json_encode(['success' => false, 'message' => 'Exercise ID required']);
            exit;
        }
        
        // Check if already completed
        $sql = "SELECT id FROM completed_exercises WHERE user_id = ? AND exercise_id = ?";
        $result = executeQuery($conn, $sql, [$userId, $exerciseId], "ss");
        
        if ($result && $result->num_rows > 0) {
            // Update score if better
            $sql = "UPDATE completed_exercises SET score = ? WHERE user_id = ? AND exercise_id = ? AND score < ?";
            executeQuery($conn, $sql, [$score, $userId, $exerciseId, $score], "issi");
            echo json_encode(['success' => true, 'message' => 'Score updated']);
            exit;
        }
        
        // Mark as completed
        $sql = "INSERT INTO completed_exercises (user_id, exercise_id, score) VALUES (?, ?, ?)";
        $result = executeQuery($conn, $sql, [$userId, $exerciseId, $score], "ssi");
        
        if ($result) {
            // Check if all exercises in lesson are completed
            $sql = "SELECT l.id as lesson_id
                    FROM exercises e
                    JOIN lessons l ON e.lesson_id = l.id
                    WHERE e.id = ?";
            $lessonResult = executeQuery($conn, $sql, [$exerciseId], "s");
            $lessonData = fetchOne($lessonResult);
            
            if ($lessonData) {
                $lessonId = $lessonData['lesson_id'];
                
                // Check if all exercises completed
                $sql = "SELECT COUNT(*) as total FROM exercises WHERE lesson_id = ?";
                $totalResult = executeQuery($conn, $sql, [$lessonId], "s");
                $totalData = fetchOne($totalResult);
                
                $sql = "SELECT COUNT(*) as completed 
                        FROM completed_exercises ce
                        JOIN exercises e ON ce.exercise_id = e.id
                        WHERE e.lesson_id = ? AND ce.user_id = ?";
                $completedResult = executeQuery($conn, $sql, [$lessonId, $userId], "ss");
                $completedData = fetchOne($completedResult);
                
                if ($totalData['total'] == $completedData['completed']) {
                    // Mark lesson as complete
                    $sql = "INSERT IGNORE INTO completed_lessons (user_id, lesson_id) VALUES (?, ?)";
                    executeQuery($conn, $sql, [$userId, $lessonId], "ss");
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Exercise marked as complete']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to mark exercise as complete']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
