<?php
session_start();
include './config.php';

// For debugging
error_log("add_to_cart.php accessed");
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'] ?? 0;
    
    if ($user_id === 0) {
        $_SESSION['message'] = "Please log in to add items to your cart.";
        header("Location: login.php");
        exit();
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Make sure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    error_log("Adding product ID: $product_id, Quantity: $quantity to cart for user ID: $user_id");
    
    // Add to cart query with ON DUPLICATE KEY UPDATE to handle if the product is already in cart
    $query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE quantity = quantity + ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added to your cart!";
        error_log("Product successfully added to cart");
    } else {
        $_SESSION['message'] = "Failed to add product to cart: " . $conn->error;
        error_log("Failed to add product to cart: " . $conn->error);
    }
    
    header("Location: wishlist.php"); // Redirect back to wishlist page instead of cart
    exit();
} else {
    $_SESSION['message'] = "Invalid request";
    error_log("Invalid request to add_to_cart.php");
    header("Location: wishlist.php");
    exit();
}