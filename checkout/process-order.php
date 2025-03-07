<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

function processOrder($pdo, $user_id, $payment_method) {
    try {
    
        $pdo->beginTransaction();
        
      
        $cartIdStmt = $pdo->prepare('SELECT id FROM cart WHERE user_id = ?');
        $cartIdStmt->execute([$user_id]);
        $cart = $cartIdStmt->fetch();
        
        if (!$cart) {
            throw new Exception("No cart found for this user");
        }
        
        $cart_id = $cart['id'];
        
       
        $cartItemsStmt = $pdo->prepare('
            SELECT ci.product_id, ci.quantity, p.price, p.stock
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ');
        $cartItemsStmt->execute([$cart_id]);
        $cartItems = $cartItemsStmt->fetchAll();
        
        
        if (empty($cartItems)) {
            throw new Exception("Your cart is empty");
        }
        
       
        foreach ($cartItems as $item) {
            if ($item['stock'] < $item['quantity']) {
                throw new Exception("Not enough stock for product ID: " . $item['product_id']);
            }
        }
        
    
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $tax = $subtotal * 0.10; 
        $shipping = 4.99;
        $total = $subtotal + $tax + $shipping;
        
        
        $discountStmt = $pdo->prepare('
            SELECT d.* 
            FROM discounts d
            JOIN cart_items ci ON d.product_id = ci.product_id
            WHERE ci.cart_id = ?
            AND d.start_date <= NOW() 
            AND d.end_date >= NOW()
        ');
        $discountStmt->execute([$cart_id]);
        $discounts = $discountStmt->fetchAll();
        
        $discount_amount = 0;
        foreach ($discounts as $discount) {
            foreach ($cartItems as $item) {
                if ($item['product_id'] == $discount['product_id']) {
                    $discount_amount += ($item['price'] * $item['quantity']) * ($discount['discount_percentage'] / 100);
                }
            }
        }
        
       
        if (isset($_SESSION['coupon_discount'])) {
            $discount_amount += $_SESSION['coupon_discount'];
            unset($_SESSION['coupon_discount']);
        }
        
       
        $total -= $discount_amount;
        
       
        $orderStmt = $pdo->prepare('
            INSERT INTO orders (user_id, total_price, status, created_at) 
            VALUES (?, ?, ?, NOW())
        ');
        $orderStmt->execute([$user_id, $total, 'pending']);
        $order_id = $pdo->lastInsertId();
        
       
        $orderItemStmt = $pdo->prepare('
            INSERT INTO order_items (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ');
        
        
        $updateStockStmt = $pdo->prepare('
            UPDATE products 
            SET stock = stock - ? 
            WHERE id = ?
        ');
        
        foreach ($cartItems as $item) {
            
            $orderItemStmt->execute([
                $order_id, 
                $item['product_id'], 
                $item['quantity'], 
                $item['price']
            ]);
            
            
            $updateStockStmt->execute([
                $item['quantity'], 
                $item['product_id']
            ]);
        }
        
        
        $clearCartStmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?');
        $clearCartStmt->execute([$cart_id]);
        
       
        $pdo->commit();
        
        return [
            'success' => true,
            'order_id' => $order_id,
            'message' => 'Order processed successfully'
        ];
        
    } catch (Exception $e) {
        
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
   
    $host = 'localhost';
    $dbname = 'watch_store';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';
    
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    
   
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    try {
        
        $pdo = new PDO($dsn, $username, $password, $options);
        
      
        $payment_method = $_POST['paymentMethod'] ?? 'credit_card';
        
        
        $result = processOrder($pdo, $user_id, $payment_method);
        
        if ($result['success']) {
           
            $_SESSION['last_order_id'] = $result['order_id'];
            
           
            header('Location: order-confirmation.php');
            exit;
        } else {
            
            $error_message = $result['message'];
        }
        
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>