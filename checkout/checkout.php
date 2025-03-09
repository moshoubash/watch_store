<?php
session_start();
$applied_discount = 0;
$coupon_code = '';
if (!isset($_SESSION['user_id'])) {
    header('Location: /watch_store/views/signup_login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
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

try {
    // Connect to database
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Get user information
    $userStmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $userStmt->execute([$user_id]);
    $user = $userStmt->fetch();
    
    if (!$user) {
        echo "User not found!";
        exit;
    }

    // Get cart items
    $cartStmt = $pdo->prepare('
        SELECT c.id as cart_id, ci.product_id, ci.quantity, p.name as product_name, 
               p.price, p.image, p.description
        FROM cart c
        JOIN cart_items ci ON c.id = ci.cart_id
        JOIN products p ON ci.product_id = p.id
        WHERE c.user_id = ?
    ');
    $cartStmt->execute([$user_id]);
    $cartItems = $cartStmt->fetchAll();
    
    // Calculate subtotal
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    // Calculate tax and shipping
    $tax = $subtotal * 0.10; 
    $shipping = 4.99;
    
    // Initialize total before discounts
    $total = $subtotal + $tax + $shipping;
    
    // Get product discounts
    $discountStmt = $pdo->prepare('
        SELECT * FROM discounts 
        WHERE product_id IN (SELECT product_id FROM cart_items WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?))
        AND start_date <= NOW() AND end_date >= NOW()
    ');
    $discountStmt->execute([$user_id]);
    $discounts = $discountStmt->fetchAll();

    // Apply coupon discount if available in session
    if (isset($_SESSION['coupon_discount']) && isset($_SESSION['coupon_code'])) {
        $applied_discount = $_SESSION['coupon_discount'];
        $coupon_code = $_SESSION['coupon_code'];
    }

    // Calculate product discounts
    $discount_amount = 0;
    foreach ($discounts as $discount) {
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $discount['product_id']) {
                $discount_amount += ($item['price'] * $item['quantity']) * ($discount['discount_percentage'] / 100);
            }
        }
    }
    
    // Apply all discounts to total
    $total = $subtotal + $tax + $shipping - $discount_amount - $applied_discount;
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Process order on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    try {
        $pdo->beginTransaction();
        
        // Create order
        $orderStmt = $pdo->prepare('
            INSERT INTO orders (user_id, total_price, status, created_at) 
            VALUES (?, ?, ?, NOW())
        ');
        $orderStmt->execute([$user_id, $total, 'pending']);
        $order_id = $pdo->lastInsertId();

        // Add order items
        $orderItemStmt = $pdo->prepare('
            INSERT INTO order_items (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ');
        
        foreach ($cartItems as $item) {
            $orderItemStmt->execute([
                $order_id, 
                $item['product_id'], 
                $item['quantity'], 
                $item['price']
            ]);
            
            // Update product stock
            $updateStockStmt = $pdo->prepare('
                UPDATE products SET stock = stock - ? WHERE id = ?
            ');
            $updateStockStmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Record coupon use if applicable
        if (isset($_SESSION['coupon_id'])) {
            $couponUseStmt = $pdo->prepare('
                INSERT INTO coupon_uses (coupon_id, order_id, user_id, discount_amount, used_at) 
                VALUES (?, ?, ?, ?, NOW())
            ');
            $couponUseStmt->execute([
                $_SESSION['coupon_id'],
                $order_id,
                $user_id,
                $_SESSION['coupon_discount']
            ]);
            
            // Clear coupon session variables
            unset($_SESSION['coupon_id']);
            unset($_SESSION['coupon_discount']);
            unset($_SESSION['coupon_code']);
        }

        // Clear cart
        $deleteCartItemsStmt = $pdo->prepare('
            DELETE FROM cart_items 
            WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?)
        ');
        $deleteCartItemsStmt->execute([$user_id]);

        $pdo->commit();
        
        // Store the order ID in session and redirect

        header('Location: order-confirmation.php?order_id=' . $order_id);
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Order processing failed: " . $e->getMessage();
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./checkout.css">
    <link rel="stylesheet" href="../public/assets/css/navbar.css">
    <link rel="stylesheet" href="../public/assets/css/footer.css">
    <title>Checkout - Watch Store</title>
</head>
<body>

<?php include '../public/views/components/navbar.php'; ?>
    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="checkout-container">
                <div class="checkout-left">
                    <div class="checkout-section">
                        <h2 class="section-title">Shipping Information</h2>
                        <div class="form-group form-row">
                            <div>
                                <label for="firstName">Name</label>
                                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                            <div>
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="streetAddress">Address</label>
                            <input type="text" id="streetAddress" name="streetAddress" value="<?php echo htmlspecialchars($user['street'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group form-row">
                            <div>
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>
                            <div>
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div>
                                <label for="zipCode">ZIP Code</label>
                                <input type="text" id="zipCode" name="zipCode" value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
                            </div>
                            <div>
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
 
                    <div class="checkout-section">
    
                        <div class="form-group">
                            <label for="couponCode">Coupon Code</label>
                            <div class="coupon-container">
                                <input type="text" id="couponCode" name="couponCode" class="coupon-input" 
                                       placeholder="Enter discount code" value="<?php echo htmlspecialchars($coupon_code); ?>">
                                <button type="button" class="apply-coupon" id="applyCoupon">Apply</button>
                            </div>
                            <div id="coupon-message"></div>
                        </div>
                    </div>
                </div>
 
                <div class="checkout-right">
                    <div class="checkout-section">
                        <h2 class="section-title">Order Summary</h2>

                        <?php if (empty($cartItems)): ?>
                            <p>Your shopping cart is empty. <a href="/watch_store/public/">Shop now</a></p>
                        <?php else: ?>

                            <?php foreach ($cartItems as $item): ?>
                                <div class="product-item">
                                    <div class="product-image">
                                        <img src="/watch_store/dashboard/assets/productImages/<?php echo $item['image'] ?>" alt="Product image" width="70">
                                    </div>
                                    
                                    <div class="product-details">
                                        <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="product-quantity">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></div>
                                    </div>
                                    <div class="product-price"><?php echo number_format($item['price'], 2); ?> $</div>
                                </div>
                            <?php endforeach; ?>
    
                            <div class="order-totals">
                                <div class="order-row">
                                    <div>Subtotal</div>
                                    <div><?php echo number_format($subtotal, 2); ?> $</div>
                                </div>
                                
                                <?php if ($discount_amount > 0): ?>
                                    <div class="order-row">
                                        <div>Product Discount <span class="discount-tag">Sale</span></div>
                                        <div>-<?php echo number_format($discount_amount, 2); ?> $</div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($applied_discount > 0): ?>
                                    <div class="order-row coupon-discount">
                                        <div>Coupon Discount <span class="discount-tag"><?php echo htmlspecialchars($coupon_code); ?></span></div>
                                        <div>-<?php echo number_format($applied_discount, 2); ?> $</div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="order-row">
                                    <div>Shipping</div>
                                    <div><?php echo number_format($shipping, 2); ?> $</div>
                                </div>
                                
                                <div class="order-row">
                                    <div>Tax</div>
                                    <div><?php echo number_format($tax, 2); ?> $</div>
                                </div>
                                
                                <div class="order-row total-row">
                                    <div>Total</div>
                                    <div id="total-amount"><?php echo number_format($total, 2); ?> $</div>
                                </div>
                            </div>
                            <button type="submit" name="complete_order" class="btn-complete">Complete Purchase</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include '../public/views/components/footer.html'; ?>
    <script src="../public/assets/js/navbar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="./checkout.js"></script>
</body>
</html>