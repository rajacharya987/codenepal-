<?php
/**
 * Database Connection and Query Functions
 */

require_once __DIR__ . '/config.php';

/**
 * Get MySQL database connection
 * @return mysqli|null
 */
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            if (DEBUG_MODE) {
                die("Database connection failed: " . $conn->connect_error);
            } else {
                die("Database connection failed. Please try again later.");
            }
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Execute a prepared statement query
 * @param mysqli $conn Database connection
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @param string $types Parameter types (i=integer, d=double, s=string, b=blob)
 * @return mysqli_result|bool
 */
function executeQuery($conn, $sql, $params = [], $types = "") {
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Query preparation failed: " . $conn->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("Query execution failed: " . $stmt->error);
        return false;
    }
    
    // For SELECT queries, return result set
    $metadata = $stmt->result_metadata();
    if ($metadata) {
        return $stmt->get_result();
    }
    
    // For INSERT/UPDATE/DELETE, return true
    return true;
}

/**
 * Fetch all rows from result set
 * @param mysqli_result $result
 * @return array
 */
function fetchAll($result) {
    if (!$result) {
        return [];
    }
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    return $rows;
}

/**
 * Fetch single row from result set
 * @param mysqli_result $result
 * @return array|null
 */
function fetchOne($result) {
    if (!$result) {
        return null;
    }
    
    return $result->fetch_assoc();
}

/**
 * Get last inserted ID
 * @param mysqli $conn
 * @return int
 */
function getLastInsertId($conn) {
    return $conn->insert_id;
}

/**
 * Get affected rows count
 * @param mysqli $conn
 * @return int
 */
function getAffectedRows($conn) {
    return $conn->affected_rows;
}

/**
 * Close database connection
 * @param mysqli $conn
 */
function closeConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
