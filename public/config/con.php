<?php


$host = 'localhost';
$db = 'watch_store';
$user = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully!"; to test if con right

    
} catch (PDOException $e) { // <-- Catching the right exception
    echo "Connection failed: " . $e->getMessage();
}
?>

