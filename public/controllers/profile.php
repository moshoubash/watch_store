<?php
include_once '../config/connectt.php';
include_once '../models/profile.php';
include_once '../models/user_payment_history.php';

$userId = 1; // Replace with actual user ID from your authentication system

$userModel = new User($pdo);
$user = $userModel->getUserById($userId);

if (!$user) {
    die("User not found");
}

$paymentModel = new Payment($pdo);
$payments = $paymentModel->getPaymentsByUserId($userId);

// include '../views/user_profile.php';
?>