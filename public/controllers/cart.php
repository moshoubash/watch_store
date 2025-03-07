<?php
require_once '../config/cart_con.php';
require_once '../models/Cart.php';

// Initialize session
initSession();

// Connect to database
$conn = connectDB();

// Create cart instance
$cart = new Cart($conn);

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
            $cart->updateQuantity($product_id, $quantity);
            break;
            
        case 'remove':
            $product_id = $_POST['product_id'];
            $cart->removeFromCart($product_id);
            break;
            
        case 'checkout':
            // Process checkout (redirect to checkout page)
            header("Location: checkout.php");
            exit;
            
        case 'empty':
            $cart->emptyCart();
            break;
    }
    
    // Redirect back to cart page to prevent form resubmission
    header("Location: cart.php");
    exit;
}
?>