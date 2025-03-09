<?php
require_once '../config/cart_con.php';
require '../models/Cart.php';
require '../models/Product.php';

// Initialize session
initSession();

// Connect to database
$conn = connectDB();

// Get the user ID if logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Create cart instance with user_id
$cart = new Cart($conn, $user_id);
$product = new Product($conn);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'add':
            $product_id = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Get product stock
            $product_info = $product->getProduct($product_id);
            $max_stock = $product_info['stock'] ?? 0;
            
            // Check current cart quantity
            $cart_items = $cart->getCartItems();
            $current_quantity = 0;
            
            foreach ($cart_items as $item) {
                if (($item['product_id'] ?? $item['id']) == $product_id) {
                    $current_quantity = $item['quantity'];
                    break;
                }
            }
            
            // Calculate how many more can be added
            $available_add = $max_stock - $current_quantity;
            
            // Limit quantity to available stock
            if ($quantity > $available_add) {
                $quantity = $available_add;
            }
            
            if ($quantity > 0) {
                $cart->addToCart($product_id, $quantity);
            }
            break;
            
        case 'update':
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Get product stock
            $product_info = $product->getProduct($product_id);
            $max_stock = $product_info['stock'] ?? 0;
            
            // Limit quantity to available stock
            if ($quantity > $max_stock) {
                $quantity = $max_stock;
            }
            
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