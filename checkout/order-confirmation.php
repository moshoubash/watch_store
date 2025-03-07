<?php
 session_start(); 

if (!isset($_SESSION['last_order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = $_SESSION['last_order_id'];
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
    
    // Get order details
    $orderStmt = $pdo->prepare('
        SELECT o.*, u.name, u.email, u.street, u.city, u.state, u.postal_code, u.phone_number 
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
    
    // Get order items
    $orderItemsStmt = $pdo->prepare('
        SELECT oi.*, p.name as product_name, p.image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ');
    $orderItemsStmt->execute([$order_id]);
    $orderItems = $orderItemsStmt->fetchAll();
    
    // Calculate order totals
    $subtotal = 0;
    foreach ($orderItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $tax = $subtotal * 0.10;
    $shipping = 4.99;
    $discount = $subtotal + $tax + $shipping - $order['total_price'];
    $total = $order['total_price'];
    
    // Generate order number (can be customized according to your needs)
    $orderNumber = 'WS-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    
    // Format order date
    $orderDate = date('F j, Y', strtotime($order['created_at']));
    
    // Clear the session order ID to prevent refreshing the page and seeing the same order
    unset($_SESSION['last_order_id']);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./order-confirmation.css">
    <title>Order Confirmation</title>

</head>
<body>
    <header>
        <div class="logo">W A T C H</div>
        <nav>
            <a href="index.php">Categories</a>
            <a href="#">Collections</a>
            <a href="#">New Arrivals</a>
            <a href="#">About Us</a>
            <a href="#">Contact Us</a>
        </nav>
        <div class="icons">
            <span><i class="fa-solid fa-magnifying-glass"></i></span>
            <span><i class="fa-solid fa-cart-shopping"></i></span>
        </div>
    </header>
    
    <div class="container">
        <div class="confirmation-container">
            <div class="confirmation-header">
                <div class="checkmark">✓</div>
                <h1 class="confirmation-title">Thank You for Your Order!</h1>
                <p class="confirmation-subtitle">Your order has been received and is being processed.</p>
                <p class="confirmation-subtitle">A confirmation email has been sent to <?php echo htmlspecialchars($order['email']); ?></p>
            </div>
            
            <div class="order-info">
                <div class="order-info-header">
                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Order Number:</span>
                            <span><?php echo $orderNumber; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Order Date:</span>
                            <span><?php echo $orderDate; ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="detail-row">
                            <span class="detail-label">Order Status:</span>
                            <span class="order-status"><?php echo ucfirst($order['status']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span>Credit Card</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-grid">
                <div class="shipping-info">
                    <h3 class="section-title">Shipping Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span><?php echo htmlspecialchars($order['name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span><?php echo htmlspecialchars($order['street']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">City:</span>
                        <span><?php echo htmlspecialchars($order['city']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">State:</span>
                        <span><?php echo htmlspecialchars($order['state']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Zip Code:</span>
                        <span><?php echo htmlspecialchars($order['postal_code']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span><?php echo htmlspecialchars($order['phone_number']); ?></span>
                    </div>
                </div>
                
                <div class="billing-info">
                    <h3 class="section-title">Billing Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span><?php echo htmlspecialchars($order['name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span><?php echo htmlspecialchars($order['street']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">City:</span>
                        <span><?php echo htmlspecialchars($order['city']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">State:</span>
                        <span><?php echo htmlspecialchars($order['state']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Zip Code:</span>
                        <span><?php echo htmlspecialchars($order['postal_code']); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="order-details">
                <h3 class="section-title">Order Details</h3>
                <?php foreach ($orderItems as $item): ?>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('<?php echo htmlspecialchars($item['image'] ?? 'img/placeholder.jpg'); ?>')"></div>
                        <div class="product-details">
                            <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="product-quantity">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></div>
                        </div>
                        <div class="product-price"><?php echo number_format($item['price'], 2); ?> $</div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-summary">
                <h3 class="section-title">Order Summary</h3>
                <div class="summary-row">
                    <div>Subtotal</div>
                    <div><?php echo number_format($subtotal, 2); ?> $</div>
                </div>
                <?php if ($discount > 0): ?>
                    <div class="summary-row">
                        <div>Discount</div>
                        <div>-<?php echo number_format($discount, 2); ?> $</div>
                    </div>
                <?php endif; ?>
                <div class="summary-row">
                    <div>Shipping</div>
                    <div><?php echo number_format($shipping, 2); ?> $</div>
                </div>
                <div class="summary-row">
                    <div>Tax</div>
                    <div><?php echo number_format($tax, 2); ?> $</div>
                </div>
                <div class="summary-row">
                    <div>Total</div>
                    <div><?php echo number_format($total, 2); ?> $</div>
                </div>
            </div>
            
            <div class="estimated-delivery">
                Estimated delivery: <?php echo date('F j, Y', strtotime('+5 days')); ?> - <?php echo date('F j, Y', strtotime('+10 days')); ?>
            </div>
            
            <div class="thank-you-message">
                <p>We appreciate your business and hope you enjoy your purchase!</p>
            </div>
            
            <a href="index.php" class="btn-continue">Continue Shopping</a>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div>
            <h3>LuxTime</h3>
            <p>Your trusted source for quality watches since 2025.</p>
        </div>
        <div>
            <h3>Quick Links</h3>
            <a href="index.php">Home</a>
            <a href="#">Products</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
        </div>
        <div>
            <h3>Contact</h3>
            <p><i class="fa-solid fa-phone"></i> +123 456 789</p>
            <p><i class="fa-solid fa-envelope"></i> info@store.com</p>
            <p><i class="fa-solid fa-location-dot"></i> 123 Store Street, City</p>
        </div>
        <div>
            <h3>Follow Us</h3>
            <span><i class="fa-brands fa-facebook"></i> Facebook</span>
            <span><i class="fa-brands fa-twitter"></i> Twitter</span>
            <span><i class="fa-brands fa-instagram"></i> Instagram</span>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>