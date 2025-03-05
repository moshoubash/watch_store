<?php
session_start();
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db);
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $messages = $userController->register($_POST);
    } elseif (isset($_POST['login'])) {
        $messages = $userController->login($_POST);
    }
}

include 'views/signup_login.php';
?>