<?php
// Database configuration
$db_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'watch_store',
    'charset' => 'utf8mb4'
];

// Connect to database using PDO
function connectDB() {
    global $db_config;
    
    try {
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['database']};charset={$db_config['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        return new PDO($dsn, $db_config['username'], $db_config['password'], $options);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Start session if not already started
function initSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in, if not, create a temporary cart
    if (!isset($_SESSION['user_id'])) {
        if (!isset($_SESSION['temp_cart'])) {
            $_SESSION['temp_cart'] = [];
        }
    }
}

// Tax rate constant
define('TAX_RATE', 0.10); // 10% tax rate
?>