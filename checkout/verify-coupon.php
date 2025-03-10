<?php
session_start();

// Database connection
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

// Get the POST data
$input = json_decode(file_get_contents('php://input'), true);
$coupon_code = $input['coupon_code'] ?? '';

$response = [
    'valid' => false,
    'message' => 'Invalid coupon code'
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Check if the coupon exists and is valid
    $stmt = $pdo->prepare('
        SELECT * FROM discounts 
        WHERE coupon_code = ? 
        AND start_date <= NOW() 
        AND end_date >= NOW()
        AND (limit_uses IS NULL OR uses < limit_uses)
    ');
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();
    
    if ($coupon) {
        // Get the cart total
        $user_id = $_SESSION['user_id'] ?? 0;
        $cartStmt = $pdo->prepare('
            SELECT c.id as cart_id, SUM(ci.quantity * p.price) as subtotal
            FROM cart c
            JOIN cart_items ci ON c.id = ci.cart_id
            JOIN products p ON ci.product_id = p.id
            WHERE c.user_id = ?
            GROUP BY c.id
        ');
        $cartStmt->execute([$user_id]);
        $cart = $cartStmt->fetch();
        
        if (!$cart) {
            $response['message'] = 'No items in cart';
            echo json_encode($response);
            exit;
        }
        
        $subtotal = $cart['subtotal'];
        $tax = $subtotal * 0.10;
        $shipping = 4.99;
        $total = $subtotal + $tax + $shipping;
        
        // Calculate discount
        $discount_amount = 0;
        if ($coupon['discount_type'] == 'percentage') {
            $discount_amount = $subtotal * ($coupon['discount_percentage'] / 100);
        } else {
            $discount_amount = $coupon['discount_amount'];
        }
        
        // Apply discount
        $new_total = $total - $discount_amount;
        
        // Store the discount in session
        $_SESSION['coupon_discount'] = $discount_amount;
        $_SESSION['coupon_code'] = $coupon_code;
        $_SESSION['coupon_id'] = $coupon['id'];
        
        $response = [
            'valid' => true,
            'message' => 'Coupon applied successfully',
            'discount_amount' => $discount_amount,
            'discount_percentage' => $coupon['discount_percentage'],
            'new_total' => $new_total
        ];
        
        // Increment coupon use count if needed
        if ($coupon['limit_uses'] !== null) {
            $updateStmt = $pdo->prepare('UPDATE discounts SET uses = uses + 1 WHERE id = ?');
            $updateStmt->execute([$coupon['id']]);
        }
    }
} catch (PDOException $e) {
    $response = [
        'valid' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);