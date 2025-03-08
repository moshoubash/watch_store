<?php
require_once '../config/cart_con.php';
require_once '../models/Cart.php';

// Initialize session
initSession();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // For AJAX requests, return JSON response
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
    
    // For regular requests, redirect to login
    header('Location: signup_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = connectDB();

// Create cart instance
$cart = new Cart($conn, $user_id);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
    switch ($action) {
        case 'add':
            $product_id = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $cart->addToCart($product_id, $quantity);
            
            if ($is_ajax) {
                $cart_items = $cart->getCartItems();
                $unique_items_count = count($cart_items);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Item added to cart',
                    'unique_items_count' => $unique_items_count
                ]);
                exit;
            }
            break;
            
        case 'update':
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            $cart->updateQuantity($product_id, $quantity);
            
            if ($is_ajax) {
                $cart_items = $cart->getCartItems();
                $unique_items_count = count($cart_items);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Cart updated',
                    'unique_items_count' => $unique_items_count,
                    'subtotal' => $cart->getSubtotal(),
                    'tax' => $cart->calculateTax($cart->getSubtotal()),
                    'total' => $cart->getTotal($cart->getSubtotal(), $cart->calculateTax($cart->getSubtotal()))
                ]);
                exit;
            }
            break;
            
        case 'remove':
            $product_id = $_POST['product_id'];
            $cart->removeFromCart($product_id);
            
            if ($is_ajax) {
                $cart_items = $cart->getCartItems();
                $unique_items_count = count($cart_items);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Item removed from cart',
                    'unique_items_count' => $unique_items_count
                ]);
                exit;
            }
            break;
            
        case 'empty':
            $cart->emptyCart();
            
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Cart emptied',
                    'unique_items_count' => 0
                ]);
                exit;
            }
            break;
    }
    
    // Set success message in session
    $_SESSION['message'] = getActionMessage($action);
    
    // Redirect back to cart page for non-AJAX requests
    header("Location: cart.php");
    exit;
}

function getActionMessage($action) {
    switch ($action) {
        case 'add':
            return 'Item added to cart.';
        case 'update':
            return 'Cart updated.';
        case 'remove':
            return 'Item removed from cart.';
        case 'empty':
            return 'Cart emptied.';
        default:
            return 'Action completed.';
    }
}