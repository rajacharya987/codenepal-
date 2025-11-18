<?php
/**
 * Authentication Functions
 * Handles user registration, login, logout, and session management
 */

// Ensure database functions are loaded
if (!function_exists('getDBConnection')) {
    require_once __DIR__ . '/../config/database.php';
}

/**
 * Generate a unique UUID v4
 * @return string
 */
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * Register a new user
 * @param string $email
 * @param string $password
 * @param string $name
 * @return array ['success' => bool, 'message' => string, 'user_id' => string]
 */
function register($email, $password, $name) {
    $conn = getDBConnection();
    
    // Validate input
    if (empty($email) || empty($password) || empty($name)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    // Validate password strength (minimum 8 characters)
    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $result = executeQuery($conn, $sql, [$email], "s");
    
    if ($result && $result->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    
    // Generate user ID
    $userId = generateUUID();
    
    // Insert user
    $sql = "INSERT INTO users (id, email, password_hash, name, role) VALUES (?, ?, ?, ?, 'user')";
    $result = executeQuery($conn, $sql, [$userId, $email, $passwordHash, $name], "ssss");
    
    if ($result) {
        return ['success' => true, 'message' => 'Registration successful', 'user_id' => $userId];
    } else {
        return ['success' => false, 'message' => 'Registration failed. Please try again'];
    }
}

/**
 * Login user
 * @param string $email
 * @param string $password
 * @return array|false User data array on success, false on failure
 */
function login($email, $password) {
    $conn = getDBConnection();
    
    // Validate input
    if (empty($email) || empty($password)) {
        return false;
    }
    
    // Get user by email
    $sql = "SELECT id, email, password_hash, name, role, avatar_url FROM users WHERE email = ?";
    $result = executeQuery($conn, $sql, [$email], "s");
    
    if (!$result || $result->num_rows === 0) {
        return false;
    }
    
    $user = fetchOne($result);
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID for security (only if headers not sent)
    if (!headers_sent()) {
        session_regenerate_id(true);
    }
    
    // Remove password hash from user data
    unset($user['password_hash']);
    
    return $user;
}

/**
 * Logout user
 */
function logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity'])) {
        $elapsed = time() - $_SESSION['last_activity'];
        if ($elapsed > SESSION_LIFETIME) {
            logout();
            return false;
        }
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Require user to be logged in (redirect if not)
 * @param string $redirectTo URL to redirect to if not logged in
 */
function requireLogin($redirectTo = '/pages/login') {
    if (!isLoggedIn()) {
        // Flush output buffer before redirect
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Check if current user is admin
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require user to be admin (redirect if not)
 * @param string $redirectTo URL to redirect to if not admin
 */
function requireAdmin($redirectTo = '/pages/dashboard') {
    if (!isAdmin()) {
        // Flush output buffer before redirect
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Get current logged in user data
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'name' => $_SESSION['name'] ?? null,
        'role' => $_SESSION['role'] ?? 'user',
        'avatar_url' => $_SESSION['avatar_url'] ?? null
    ];
}

/**
 * Get user by ID
 * @param string $userId
 * @return array|null
 */
function getUserById($userId) {
    $conn = getDBConnection();
    
    $sql = "SELECT id, email, name, role, avatar_url, created_at FROM users WHERE id = ?";
    $result = executeQuery($conn, $sql, [$userId], "s");
    
    if (!$result || $result->num_rows === 0) {
        return null;
    }
    
    return fetchOne($result);
}

/**
 * Update user profile
 * @param string $userId
 * @param array $data
 * @return bool
 */
function updateUserProfile($userId, $data) {
    $conn = getDBConnection();
    
    $updates = [];
    $params = [];
    $types = "";
    
    if (isset($data['name'])) {
        $updates[] = "name = ?";
        $params[] = $data['name'];
        $types .= "s";
    }
    
    if (isset($data['avatar_url'])) {
        $updates[] = "avatar_url = ?";
        $params[] = $data['avatar_url'];
        $types .= "s";
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $params[] = $userId;
    $types .= "s";
    
    $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
    $result = executeQuery($conn, $sql, $params, $types);
    
    // Update session if current user
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $userId) {
        if (isset($data['name'])) {
            $_SESSION['name'] = $data['name'];
        }
        if (isset($data['avatar_url'])) {
            $_SESSION['avatar_url'] = $data['avatar_url'];
        }
    }
    
    return $result !== false;
}

/**
 * Change user password
 * @param string $userId
 * @param string $currentPassword
 * @param string $newPassword
 * @return array ['success' => bool, 'message' => string]
 */
function changePassword($userId, $currentPassword, $newPassword) {
    $conn = getDBConnection();
    
    // Validate new password
    if (strlen($newPassword) < 8) {
        return ['success' => false, 'message' => 'New password must be at least 8 characters'];
    }
    
    // Get current password hash
    $sql = "SELECT password_hash FROM users WHERE id = ?";
    $result = executeQuery($conn, $sql, [$userId], "s");
    
    if (!$result || $result->num_rows === 0) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    $user = fetchOne($result);
    
    // Verify current password
    if (!password_verify($currentPassword, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Current password is incorrect'];
    }
    
    // Hash new password
    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    
    // Update password
    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $result = executeQuery($conn, $sql, [$newPasswordHash, $userId], "ss");
    
    if ($result) {
        return ['success' => true, 'message' => 'Password changed successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to change password'];
    }
}
