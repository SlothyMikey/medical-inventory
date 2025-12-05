<?php
/**
 * Database Connection Helper
 * Provides a PDO connection to the MySQL database
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'medical_inventory');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Get a PDO database connection
 * @return PDO
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed. Please try again later.");
    }
}

/**
 * Sanitize user input
 * @param string $input
 * @return string
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a secure password hash
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify a password against a hash
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is logged in and has admin role
 * Redirects to login if not authorized
 */
function requireAdmin() {
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /medical-inventory/auth/login.php");
        exit();
    }
}

/**
 * Send JSON response
 * @param bool $success
 * @param string $message
 * @param array $data
 */
function jsonResponse($success, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}
?>
