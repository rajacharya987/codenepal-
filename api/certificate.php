<?php
/**
 * Certificate Generation API
 * Generates certificates for completed courses
 */

// Prevent any output before JSON
ob_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

// Clear any output and set JSON header
ob_end_clean();
header('Content-Type: application/json');

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
    case 'check_eligibility':
        $courseId = $input['course_id'] ?? '';
        $result = checkCertificateEligibility($conn, $userId, $courseId);
        echo json_encode($result);
        break;
        
    case 'generate':
        $courseId = $input['course_id'] ?? '';
        $result = generateCertificate($conn, $userId, $courseId);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

/**
 * Check if user is eligible for certificate
 */
function checkCertificateEligibility($conn, $userId, $courseId) {
    // Get total lessons and exercises
    $sql = "SELECT 
            (SELECT COUNT(*) FROM lessons WHERE course_id = ?) as total_lessons,
            (SELECT COUNT(*) FROM exercises e 
             JOIN lessons l ON e.lesson_id = l.id 
             WHERE l.course_id = ?) as total_exercises,
            (SELECT COUNT(*) FROM completed_lessons cl 
             JOIN lessons l ON cl.lesson_id = l.id 
             WHERE l.course_id = ? AND cl.user_id = ?) as completed_lessons,
            (SELECT COUNT(*) FROM completed_exercises ce 
             JOIN exercises e ON ce.exercise_id = e.id 
             JOIN lessons l ON e.lesson_id = l.id 
             WHERE l.course_id = ? AND ce.user_id = ?) as completed_exercises";
    
    $result = executeQuery($conn, $sql, [
        $courseId, $courseId, $courseId, $userId, $courseId, $userId
    ], "ssssss");
    
    $data = fetchOne($result);
    
    $eligible = ($data['total_lessons'] > 0 && 
                 $data['completed_lessons'] >= $data['total_lessons'] &&
                 $data['total_exercises'] > 0 &&
                 $data['completed_exercises'] >= $data['total_exercises']);
    
    // Check if certificate already exists
    $sql = "SELECT id, verification_code FROM certificates WHERE user_id = ? AND course_id = ?";
    $result = executeQuery($conn, $sql, [$userId, $courseId], "ss");
    $existing = fetchOne($result);
    
    return [
        'success' => true,
        'eligible' => $eligible,
        'has_certificate' => $existing !== null,
        'certificate_id' => $existing['id'] ?? null,
        'verification_code' => $existing['verification_code'] ?? null,
        'stats' => $data
    ];
}

/**
 * Generate certificate
 */
function generateCertificate($conn, $userId, $courseId) {
    // Check eligibility first
    $eligibility = checkCertificateEligibility($conn, $userId, $courseId);
    
    if (!$eligibility['eligible']) {
        return [
            'success' => false,
            'message' => 'You must complete all lessons and exercises to earn a certificate'
        ];
    }
    
    // If certificate already exists, return it
    if ($eligibility['has_certificate']) {
        return [
            'success' => true,
            'message' => 'Certificate already exists',
            'certificate_id' => $eligibility['certificate_id'],
            'verification_code' => $eligibility['verification_code']
        ];
    }
    
    // Generate new certificate
    $certificateId = generateUUID();
    $verificationCode = strtoupper(substr(md5($userId . $courseId . time()), 0, 12));
    
    $sql = "INSERT INTO certificates (id, user_id, course_id, verification_code) VALUES (?, ?, ?, ?)";
    $result = executeQuery($conn, $sql, [$certificateId, $userId, $courseId, $verificationCode], "ssss");
    
    if ($result) {
        return [
            'success' => true,
            'message' => 'Certificate generated successfully',
            'certificate_id' => $certificateId,
            'verification_code' => $verificationCode
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to generate certificate'
        ];
    }
}
