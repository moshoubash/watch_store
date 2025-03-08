<?php
require_once '../config/cart_con.php';
require '../models/Cart.php';

// Initialize session
initSession();

// Connect to database
$conn = connectDB();

// Get the user ID if logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Create cart instance with user_id
$cart = new Cart($conn, $user_id);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'add':
            $product_id = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $cart->addToCart($product_id, $quantity);
            break;
            
        case 'update':
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            if ($quantity > 0) {
                $cart->updateQuantity($product_id, $quantity);
            } else {
                $cart->removeFromCart($product_id);
            }
            break;
            
        case 'remove':
            $product_id = $_POST['product_id'];
            $cart->removeFromCart($product_id);
            break;
            
        case 'checkout':
            // Process checkout (redirect to checkout page)
            header("Location: /watch_store/checkout/checkout.php");
            exit;
            
        case 'empty':
            $cart->emptyCart();
            break;
    }
    
    // Redirect back to cart page to prevent form resubmission
    header("Location: ../views/cart_page.php");
    exit;
}
?>