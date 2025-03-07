<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: /watch_store/views/signup_login.php');
    exit;
}

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
    
    $userStmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $userStmt->execute([$user_id]);
    $user = $userStmt->fetch();
    
    if (!$user) {
        echo "User not found!";
        exit;
    }

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
    
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $tax = $subtotal * 0.10; 
    $shipping = 4.99;
    $total = $subtotal + $tax + $shipping;
    
    $discountStmt = $pdo->prepare('
        SELECT * FROM discounts 
        WHERE product_id IN (SELECT product_id FROM cart_items WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?))
        AND start_date <= NOW() AND end_date >= NOW()
    ');
    $discountStmt->execute([$user_id]);
    $discounts = $discountStmt->fetchAll();
    
    $discount_amount = 0;
    foreach ($discounts as $discount) {
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $discount['product_id']) {
                $discount_amount += ($item['price'] * $item['quantity']) * ($discount['discount_percentage'] / 100);
            }
        }
    }
    
    if ($discount_amount > 0) {
        $total -= $discount_amount;
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
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
        
        foreach ($cartItems as $item) {
            $orderItemStmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        }

        $deleteCartItemsStmt = $pdo->prepare('
            DELETE FROM cart_items 
            WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?)
        ');
        $deleteCartItemsStmt->execute([$user_id]);

        $pdo->commit();

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
    <title>checkout page</title>

</head>
<body>
<?php include '../public/views/components/navbar.html'; ?>
   
    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="checkout-container">
            
                <div class="checkout-left">
                 
                    <div class="checkout-section">
                        <h2 class="section-title"> Shipping Information</h2>
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
                                <label for="city">city</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>
                            <div>
                                <label for="state">state</label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div>
                                <label for="zipCode">zipCode</label>
                                <input type="text" id="zipCode" name="zipCode" value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
                            </div>
                            <div><label for="phone"> phone</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                    
              
                    <div class="checkout-section">
                        <h2 class="section-title">payment-option</h2>
                        <div class="payment-options">
                            <div class="payment-option">
               
                            </div>
                        </div>
                        
                        <!-- Coupon Code -->
                        <div class="form-group">
                            <label for="couponCode">coupon Code</label>
                            <div class="coupon-container">
                                <input type="text" id="couponCode" name="couponCode" class="coupon-input" placeholder="Enter discount code">
                                <button type="button" class="apply-coupon" id="applyCoupon">apply</button>
                            </div>
                        </div>
                    </div>
                </div>
                
             
                <div class="checkout-right">
                    <div class="checkout-section">
                        <h2 class="section-title">Order Summary</h2>
                        
                        
                        <?php if (empty($cartItems)): ?>
                            <p>Shopping cart is empty.<a href="index.php">shop now</a></p>
                        <?php else: ?>
                            <?php foreach ($cartItems as $item): ?>
                                <div class="product-item">
                                    <div class="product-image" style="background-image: url('<?php echo htmlspecialchars($item['image'] ?? 'img/placeholder.jpg'); ?>')"></div>
                                    <div class="product-details">
                                        <div class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="product-quantity">quantity: <?php echo htmlspecialchars($item['quantity']); ?></div>
                                    </div>
                                    <div class="product-price"><?php echo number_format($item['price'], 2); ?> $</div>
                                </div>
                            <?php endforeach; ?>
                            
                            
                            <div class="order-totals">
                                <div class="order-row">
                                    <div>total</div>
                                    <div><?php echo number_format($subtotal, 2); ?> $</div>
                                </div>
                                <?php if ($discount_amount > 0): ?>
                                    <div class="order-row">
                                        <div>discount <span class="discount-tag">dis</span></div>
                                        <div>-<?php echo number_format($discount_amount, 2); ?> $</div>
                                    </div>
                                <?php endif; ?>
                                <div class="order-row">
                                    <div>shipping</div>
                                    <div><?php echo number_format($shipping, 2); ?> $</div>
                                </div>
                                <div class="order-row">
                                    <div>tax</div>
                                    <div><?php echo number_format($tax, 2); ?> $</div>
                                </div>
                                <div class="order-row">
                                    <div>total</div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle payment method fields
            const creditCardRadio = document.getElementById('creditCard');
            const paypalRadio = document.getElementById('paypal');
            const creditCardFields = document.getElementById('creditCardFields');

            paypalRadio.addEventListener('change', function() {
                if (this.checked) {
                    creditCardFields.style.display = 'none';
                }
            });

            creditCardRadio.addEventListener('change', function() {
                if (this.checked) {
                    creditCardFields.style.display = 'block';
                }
            });

            // Handle coupon application
            const applyCouponBtn = document.getElementById('applyCoupon');
            
            applyCouponBtn.addEventListener('click', function() {
                const couponCode = document.getElementById('couponCode').value.trim();
                
                if (!couponCode) {
                    alert('Please enter discount code');
                    return;
                }
                
              
                fetch('verify-coupon.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ coupon_code: couponCode })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        // Update the total with discount
                        const totalElement = document.getElementById('total-amount');
                        totalElement.textContent = data.new_total.toFixed(2) + ' $';
                        
                        // Add discount row if it doesn't exist
                        if (!document.querySelector('.order-row .discount-tag')) {
                            const discountRow = document.createElement('div');
                            discountRow.className = 'order-row';
                            discountRow.innerHTML = `
                                <div>الخصم <span class="discount-tag">${data.discount_percentage}%</span></div>
                                <div>-${data.discount_amount.toFixed(2)} $</div>
                            `;
                            
                            // Insert before the total row
                            const orderTotals = document.querySelector('.order-totals');
                            const totalRow = orderTotals.lastElementChild;
                            orderTotals.insertBefore(discountRow, totalRow);
                        }
                        
                        alert('Discount applied successfully!');
                    } else {
                        alert('Discount code is invalid or expired');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while validating the coupon');
                });
            });
        });
    </script>
</body>
</html>