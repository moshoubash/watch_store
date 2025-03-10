<?php
session_start();
include './config.php';

// For debugging
error_log("remove_from_wishlist.php accessed");
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'] ?? 0;
    
    if ($user_id === 0) {
        $_SESSION['message'] = "Please log in to manage your wishlist.";
        header("Location: login.php");
        exit();
    }
    
    $product_id = (int)$_POST['product_id'];
    error_log("Removing product ID: $product_id from wishlist for user ID: $user_id");
    
    // Delete from wishlist
    $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Item removed from your wishlist.";
        error_log("Item successfully removed from wishlist");
    } else {
        $_SESSION['message'] = "Failed to remove item from wishlist: " . $conn->error;
        error_log("Failed to remove item from wishlist: " . $conn->error);
    }
    
    header("Location: wishlist.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request";
    error_log("Invalid request to remove_from_wishlist.php");
    header("Location: wishlist.php");
    exit();
}