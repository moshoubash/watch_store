<?php
// Database Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'shopping_cart_db';

// Connect to Database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start Session
session_start();

// Initialize shopping cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Product Class
class Product {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getProduct($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getAllProducts() {
        $stmt = $this->conn->query("SELECT * FROM products");
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
}

// Cart Class
class Cart {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function addToCart($product_id, $quantity = 1) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    
    public function updateQuantity($product_id, $quantity) {
        if ($quantity <= 0) {
            $this->removeFromCart($product_id);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    
    public function removeFromCart($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    
    public function getCartItems() {
        $items = [];
        $product = new Product($this->conn);
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $productData = $product->getProduct($product_id);
            if ($productData) {
                $productData['quantity'] = $quantity;
                $items[] = $productData;
            }
        }
        
        return $items;
    }
    
    public function getSubtotal() {
        $subtotal = 0;
        $items = $this->getCartItems();
        
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        return $subtotal;
    }
    
    public function calculateTax($subtotal, $tax_rate = 0.10) {
        return $subtotal * $tax_rate;
    }
    
    public function getTotal($subtotal, $tax, $shipping = 0) {
        return $subtotal + $tax + $shipping;
    }
    
    public function applyPromoCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM promo_codes WHERE code = ? AND active = 1");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function emptyCart() {
        $_SESSION['cart'] = [];
    }
}

// Handle Cart Actions
$action = isset($_POST['action']) ? $_POST['action'] : '';
$cart = new Cart($conn);

if ($action === 'add') {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $cart->addToCart($product_id, $quantity);
    header("Location: cart.php");
    exit;
} elseif ($action === 'update') {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $cart->updateQuantity($product_id, $quantity);
    header("Location: cart.php");
    exit;
} elseif ($action === 'remove') {
    $product_id = $_POST['product_id'];
    $cart->removeFromCart($product_id);
    header("Location: cart.php");
    exit;
} elseif ($action === 'promo') {
    $promo_code = $_POST['promo_code'];
    $_SESSION['promo_code'] = $promo_code;
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .cart-items {
            width: 65%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .order-summary {
            width: 30%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            align-items: center;
        }
        .item-image {
            width: 80px;
            height: 80px;
            margin-right: 15px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .item-meta {
            color: #666;
            font-size: 0.9em;
        }
        .item-price {
            font-weight: bold;
            text-align: right;
            min-width: 100px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .quantity-btn {
            background: none;
            border: 1px solid #ddd;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .quantity-input {
            width: 40px;
            height: 25px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
        }
        .remove-item {
            color: #f44336;
            cursor: pointer;
            font-size: 1.2em;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .summary-total {
            font-weight: bold;
            font-size: 1.2em;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }
        .promo-form {
            display: flex;
            margin: 20px 0;
        }
        .promo-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        .promo-btn {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        .checkout-btn {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            width: 100%;
            text-align: center;
            font-size: 1.1em;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .payment-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
            color: #666;
            font-size: 1.5em;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="cart-items">
        <h2>Shopping Cart (<?php echo count($_SESSION['cart']); ?> items)</h2>
        
        <?php
        $cart_items = $cart->getCartItems();
        foreach ($cart_items as $item):
        ?>
        <div class="item">
            <div class="item-image">
                <i class="fas fa-watch"></i>
            </div>
            <div class="item-details">
                <div class="item-title"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="item-meta"><?php echo htmlspecialchars($item['color']); ?> / <?php echo htmlspecialchars($item['size']); ?></div>
                <form action="cart.php" method="post" class="quantity-control">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="button" class="quantity-btn" onclick="this.form.quantity.value = Math.max(1, parseInt(this.form.quantity.value) - 1); this.form.submit();">-</button>
                    <input type="text" name="quantity" class="quantity-input" value="<?php echo $item['quantity']; ?>">
                    <button type="button" class="quantity-btn" onclick="this.form.quantity.value = parseInt(this.form.quantity.value) + 1; this.form.submit();">+</button>
                </form>
            </div>
            <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
            <form action="cart.php" method="post">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                <button type="submit" class="remove-item" style="background:none;border:none;">&times;</button>
            </form>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    
    <div class="order-summary">
        <h2>Order Summary</h2>
        
        <?php
        $subtotal = $cart->getSubtotal();
        $tax = $cart->calculateTax($subtotal);
        $shipping = 0; // Free shipping in this example
        $total = $cart->getTotal($subtotal, $tax, $shipping);
        
        // Apply promo code if exists
        $discount = 0;
        if (isset($_SESSION['promo_code'])) {
            $promo = $cart->applyPromoCode($_SESSION['promo_code']);
            if ($promo) {
                $discount = $subtotal * ($promo['discount'] / 100);
                $total -= $discount;
            }
        }
        ?>
        
        <div class="summary-row">
            <div>Subtotal</div>
            <div>$<?php echo number_format($subtotal, 2); ?></div>
        </div>
        
        <?php if ($discount > 0): ?>
        <div class="summary-row" style="color: #4CAF50;">
            <div>Discount</div>
            <div>-$<?php echo number_format($discount, 2); ?></div>
        </div>
        <?php endif; ?>
        
        <div class="summary-row">
            <div>Shipping</div>
            <div><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></div>
        </div>
        
        <div class="summary-row">
            <div>Tax</div>
            <div>$<?php echo number_format($tax, 2); ?></div>
        </div>
        
        <div class="summary-row summary-total">
            <div>Total</div>
            <div>$<?php echo number_format($total, 2); ?></div>
        </div>
        
        <form action="cart.php" method="post" class="promo-form">
            <input type="hidden" name="action" value="promo">
            <input type="text" name="promo_code" class="promo-input" placeholder="Promo code">
            <button type="submit" class="promo-btn">Apply</button>
        </form>
        
        <button class="checkout-btn">Proceed to Checkout</button>
        
        <div class="payment-icons">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-amex"></i>
            <i class="fab fa-cc-discover"></i>
        </div>
    </div>
</div>

</body>
</html>