<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['valid' => false, 'message' => 'User not logged in']);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!isset($data['coupon_code']) || empty($data['coupon_code'])) {
    header('Content-Type: application/json');
    echo json_encode(['valid' => false, 'message' => 'No coupon code provided']);
    exit;
}
$coupon_code = $data['coupon_code'];
$user_id = $_SESSION['user_id'];

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
    
    $couponStmt = $pdo->prepare('
        SELECT * FROM discounts 
        WHERE name = ? 
        AND start_date <= NOW() 
        AND end_date >= NOW()
        AND limit > 0
    ');
    $couponStmt->execute([$coupon_code]);
    $coupon = $couponStmt->fetch();
    
    if (!$coupon) {
        header('Content-Type: application/json');
        echo json_encode(['valid' => false, 'message' => 'Invalid or expired coupon']);
        exit;
    }
    
    $cartStmt = $pdo->prepare('
        SELECT c.id as cart_id, ci.product_id, ci.quantity, p.price
        FROM cart c
        JOIN cart_items ci ON c.id = ci.cart_id
        JOIN products p ON ci.product_id = p.id
        WHERE c.user_id = ?
    ');
    $cartStmt->execute([$user_id]);
    $cartItems = $cartStmt->fetchAll();
    
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $tax = $subtotal * 0.10; 
    $shipping = 4.99;
    $total = $subtotal + $tax + $shipping;
    
    $discount_amount = 0;

    if ($coupon['product_id']) {
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $coupon['product_id']) {
                $discount_amount += ($item['price'] * $item['quantity']) * ($coupon['discount_percentage'] / 100);
            }
        }
    } else {
    
        $discount_amount = $subtotal * ($coupon['discount_percentage'] / 100);
    }
    
    
    $new_total = $total - $discount_amount;
    
    
    if ($coupon['limit'] > 0) {
        $updateCouponStmt = $pdo->prepare('
            UPDATE discounts
            SET limit = limit - 1
            WHERE name = ?
        ');
        $updateCouponStmt->execute([$coupon_code]);
    }
    

    header('Content-Type: application/json');
    echo json_encode([
        'valid' => true,
        'message' => 'Coupon applied successfully',
        'discount_percentage' => $coupon['discount_percentage'],
        'discount_amount' => $discount_amount,
        'original_total' => $total,
        'new_total' => $new_total
    ]);
    
} catch (PDOException $e) {
    
    header('Content-Type: application/json');
    echo json_encode([
        'valid' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>