<?php
session_start();
include './config.php';

// For debugging
error_log("add_selected_to_cart.php accessed");
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select']) && is_array($_POST['select'])) {
    $user_id = $_SESSION['user_id'] ?? 0;
    
    if ($user_id === 0) {
        $_SESSION['message'] = "Please log in to add items to your cart.";
        header("Location: login.php");
        exit();
    }
    
    $products = $_POST['select'];
    $success = 0;
    
    error_log("Adding " . count($products) . " selected products to cart for user ID: $user_id");
    
    foreach ($products as $product_id) {
        // Sanitize the input
        $product_id = (int)$product_id;
        
        // Add to cart query
        $query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        
        if ($stmt->execute()) {
            $success++;
            error_log("Successfully added product ID: $product_id to cart");
        } else {
            error_log("Failed to add product ID: $product_id to cart: " . $conn->error);
        }
    }
    
    if ($success > 0) {
        $_SESSION['message'] = "$success item(s) added to your cart.";
    } else {
        $_SESSION['message'] = "Failed to add items to cart.";
    }
    
    header("Location: wishlist.php"); // Redirect back to wishlist page instead of cart
    exit();
} else {
    $_SESSION['message'] = "No items selected or invalid request.";
    error_log("Invalid request to add_selected_to_cart.php");
    header("Location: wishlist.php");
    exit();
}