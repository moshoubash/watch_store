<?php
// Include necessary files
require_once 'config.php';
require_once 'Cart.php';
require_once 'Product.php';

// Initialize session and database connection
initSession();
$conn = connectDB();

// Create cart and product instances
$cart = new Cart($conn);
$product = new Product($conn);

// Page title
$page_title = 'Shopping Cart';

// Include header
include 'header.php';
?>

<div class="container cart-container">
    <div class="cart-items">
        <h2>Shopping Cart (<?php echo count($_SESSION['cart']); ?> items)</h2>
        
        <?php
        $cart_items = $cart->getCartItems();
        
        if (empty($cart_items)): 
        ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <p>Continue <a href="products.php">shopping</a> to add items.</p>
            </div>
        <?php 
        else:
            foreach ($cart_items as $item):
        ?>
            <div class="item">
                <div class="item-image">
                    <?php if ($item['image']): ?>
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <?php else: ?>
                        <div class="placeholder-image">
                            <i class="fas fa-watch"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="item-details">
                    <div class="item-category"><?php echo htmlspecialchars($item['category']); ?></div>
                    <div class="item-title"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="item-meta">
                        <?php echo htmlspecialchars($item['color']); ?> / 
                        <?php echo htmlspecialchars($item['size']); ?>
                    </div>
                    
                    <form action="cart_controller.php" method="post" class="quantity-control">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        
                        <button type="submit" class="quantity-btn" onclick="this.form.quantity.value = Math.max(1, parseInt(this.form.quantity.value) - 1); this.form.submit();">-</button>
                        
                        <input type="text" name="quantity" class="quantity-input" 
                               value="<?php echo $item['quantity']; ?>" 
                               min="1" 
                               max="<?php echo $item['stock']; ?>">
                        
                        <button type="submit" class="quantity-btn" onclick="this.form.quantity.value = Math.min(<?php echo $item['stock']; ?>, parseInt(this.form.quantity.value) + 1); this.form.submit();">+</button>
                    </form>
                </div>
                
                <div class="item-price">
                    $<?php echo number_format($item['price'], 2); ?>
                </div>
                
                <form action="cart_controller.php" method="post" class="remove-form">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="remove-item">&times;</button>
                </form>
            </div>
        <?php 
            endforeach; 
        endif; 
        ?>
    </div>
    
    <?php if (!empty($cart_items)): ?>
    <div class="order-summary">
        <h2>Order Summary</h2>
        
        <?php
        $subtotal = $cart->getSubtotal();
        $tax = $cart->calculateTax($subtotal);
        $shipping = 0; // Free shipping
        $total = $cart->getTotal($subtotal, $tax, $shipping);
        ?>
        
        <div class="summary-row">
            <span>Subtotal</span>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
        </div>
        
        <div class="summary-row">
            <span>Shipping</span>
            <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></span>
        </div>
        
        <div class="summary-row">
            <span>Tax</span>
            <span>$<?php echo number_format($tax, 2); ?></span>
        </div>
        
        <div class="summary-row summary-total">
            <span>Total</span>
            <span>$<?php echo number_format($total, 2); ?></span>
        </div>
        
        <form action="cart_controller.php" method="post">
            <input type="hidden" name="action" value="checkout">
            <button type="submit" class="checkout-btn">Proceed to Checkout</button>
        </form>
        
        <form action="cart_controller.php" method="post" class="empty-cart-form">
            <input type="hidden" name="action" value="empty">
            <button type="submit" class="empty-cart-btn">Empty Cart</button>
        </form>
    </div>
    <?php endif; ?>
</div>


</document_content>

