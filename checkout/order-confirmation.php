<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost/watch_store_clone/public/views/signup_login.php');
    exit;
}

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = $_GET['order_id'];
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
    
    // التحقق من أن الطلب ينتمي للمستخدم الحالي
    $orderStmt = $pdo->prepare('
        SELECT o.*, u.name, u.email 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ? AND o.user_id = ?
    ');
    $orderStmt->execute([$order_id, $user_id]);
    $order = $orderStmt->fetch();
    
    if (!$order) {
        header('Location: index.php');
        exit;
    }
    
    // جلب تفاصيل الطلب
    $orderItemsStmt = $pdo->prepare('
        SELECT oi.*, p.name as product_name, p.image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ');
    $orderItemsStmt->execute([$order_id]);
    $orderItems = $orderItemsStmt->fetchAll();
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../checkout/order-confirmation.css">
    <link rel="stylesheet" href="../public/assets/css/navbar.css">
    <link rel="stylesheet" href="../public/assets/css/footer.css">
    <title> Confirm-Order </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

    </style>
</head>
<body>
    <?php include '../public/views/components/navbar.html'; ?>
    
    <div class="confirmation-container">
        <div class="success-icon">
        <!--     <i class="fas fa-check-circle" ></i> -->
             <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTp9zCTxTTeD55Fa45aBsTOmGYMSoKLr86kCQ&s" alt="" width="100" height="100">
        </div>
        
        <div class="confirmation-header">
            <h1>Your request has been successfully received!</h1>
            <p>Thank you for shopping with us. Your order will be shipped soon.</p>
        </div>
        
        <div class="order-details">
            <h2>Order Details</h2>
            
            <div class="order-info">
                <div>
                    <p><span class="info-label">Order number:</span> #<?php echo $order_id; ?></p>
                    <p><span class="info-label">Request Date:</span> <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
                    <p><span class="info-label">Request Status:</span> <span style="color: var(--success-color);">The order was successful</span></p>
                </div>
                <div>
                    <p><span class="info-label">Customer Name:</span> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><span class="info-label">Email:</span> <?php echo htmlspecialchars($order['email']); ?></p>
                </div>
            </div>
            
            <h2>Products</h2>
            <div class="items-grid">
                <?php foreach ($orderItems as $item): ?>
                <div class="item-card">
                    <div class="item-image" style="background-image: url('<?php echo htmlspecialchars($item['image'] ?? '../public/assets/img/placeholder.jpg'); ?>')"></div>
                    <div class="item-details">
                        <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                        <div class="item-qty-price">
                            <span>quantity: <?php echo $item['quantity']; ?></span>
                            <span>price: <?php echo number_format($item['price'], 2); ?> $</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-summary">
                <div class="summary-row">
                    <div>Total Products:</div>
                    <div><?php 
                        $subtotal = array_sum(array_map(function($item) { 
                            return $item['price'] * $item['quantity']; 
                        }, $orderItems)); 
                        echo number_format($subtotal, 2); 
                    ?> $</div>
                </div>
                <div class="summary-row">
                    <div>Tax:</div>
                    <div><?php echo number_format($subtotal * 0.10, 2); ?> $</div>
                </div>
                <div class="summary-row">
                    <div>Shipping fees:</div>
                    <div>4.99 $</div>
                </div>
                <div class="summary-row">
                    <div>total:</div>
                    <div><?php echo number_format($order['total_price'], 2); ?> $</div>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="http://localhost/watch_store_clone/public/" class="home-button">
                <i class="fas fa-home"></i> Back to Home Page
            </a>
        </div>
    </div>
    
    <?php include '../public/views/components/footer.html'; ?>
    <script src="../public/assets/js/navbar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>