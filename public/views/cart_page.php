<?php
require_once '../config/cart_con.php';
require_once '../models/Cart.php';
require_once '../models/Product.php';

// Initialize session and database connection
initSession();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signup_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = connectDB();

// Create cart instance with user_id
$cart = new Cart($conn, $user_id);
$product = new Product($conn);

// Get number of unique items in cart (not total quantity)
$cart_items = $cart->getCartItems();
$unique_items_count = count($cart_items);

// Page title
$page_title = 'Shopping Cart';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://kit.fontawesome.com/d890c03bb3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
<?php include './components/navbar.html'; ?>
<div class="container cart-container">
    <div class="cart-items">
        <h2>Shopping Cart (<?php echo $unique_items_count; ?> <?php echo $unique_items_count === 1 ? 'item' : 'items'; ?>)</h2>
       
        <?php
        if (empty($cart_items)): 
        ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <p>Continue <a href="/watch_store/public">shopping</a> to add items.</p>
            </div>
        <?php 
        else:
            foreach ($cart_items as $item):
                // Ensure we have all the necessary product information
                $product_id = $item['product_id'] ?? $item['id'] ?? 0;
                $item_id = $item['cart_item_id'] ?? $item['id'] ?? 0;
                $item_price = $item['price'] ?? 0;
                $item_quantity = $item['quantity'] ?? 1;
                $item_total = $item_price * $item_quantity;
        ?>
            <div class="item" id="cart-item-<?php echo $product_id; ?>">
                <div class="item-image">
                    <?php if (isset($item['image']) && $item['image']): ?>
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <?php else: ?>
                        <div class="placeholder-image">
                            <i class="fas fa-watch"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="item-details">
                    <div class="item-category"><?php echo htmlspecialchars($item['category'] ?? ''); ?></div>
                    <div class="item-title"><?php echo htmlspecialchars($item['name']); ?></div>
                    <?php if (isset($item['color']) && isset($item['size'])): ?>
                    <div class="item-meta">
                        <?php echo htmlspecialchars($item['color']); ?>  
                        <?php echo htmlspecialchars($item['size']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn" 
                            onclick="updateQuantity('decrease', '<?php echo $item_id; ?>', '<?php echo $product_id; ?>', <?php echo $item_price; ?>)">-</button>
            
                        <input type="text" name="quantity" class="quantity-input" 
                               value="<?php echo $item_quantity; ?>" 
                               min="0" 
                               max="<?php echo $item['stock'] ?? 10; ?>" 
                               id="quantity-input-<?php echo $item_id; ?>" 
                               oninput="validateQuantity('<?php echo $item_id; ?>');"
                               onchange="updateQuantity('manual', '<?php echo $item_id; ?>', '<?php echo $product_id; ?>', <?php echo $item_price; ?>)">
            
                        <button type="button" class="quantity-btn" 
                                onclick="updateQuantity('increase', '<?php echo $item_id; ?>', '<?php echo $product_id; ?>', <?php echo $item_price; ?>)">+</button>    
                    </div>
                </div>
                
                <div class="item-price" id="item-total-<?php echo $item_id; ?>">
                    $<?php echo number_format($item_total, 2); ?>
                </div>
                
                <button type="button" class="remove-item" onclick="removeItem(<?php echo $product_id; ?>)">&times;</button>
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
            <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span>
        </div>
        
        <div class="summary-row">
            <span>Shipping</span>
            <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></span>
        </div>
        
        <div class="summary-row">
            <span>Tax</span>
            <span id="tax">$<?php echo number_format($tax, 2); ?></span>
        </div>
        
        <div class="summary-row summary-total">
            <span>Total</span>
            <span id="total">$<?php echo number_format($total, 2); ?></span>
        </div>
        
        <form action="/watch_store/checkout/checkout.php" method="get">
            <button type="submit" class="checkout-btn">Proceed to Checkout</button>
        </form>
        
        <form action="cart_controller.php" method="post" class="empty-cart-form">
            <input type="hidden" name="action" value="empty">
            <button type="submit" class="empty-cart-btn">Empty Cart</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/navbar.js"></script>
<script>
    // Include the JavaScript functions defined in the previous code block
    function updateQuantity(action, itemId, productId, price) {
    const quantityInput = document.getElementById("quantity-input-" + itemId);
    let currentQuantity = parseInt(quantityInput.value);
    const maxStock = parseInt(quantityInput.getAttribute('max'));
    
    // Update quantity based on action
    if (action === 'decrease' && currentQuantity > 0) {
        currentQuantity -= 1;
    } else if (action === 'increase' && currentQuantity < maxStock) {
        currentQuantity += 1;
    } else if (action === 'manual') {
        // Handle manual input
        currentQuantity = parseInt(quantityInput.value);
        if (isNaN(currentQuantity)) currentQuantity = 1;
    }
    
    // Update the input value
    quantityInput.value = currentQuantity;
    
    // Calculate item total price
    const itemTotalElement = document.getElementById("item-total-" + itemId);
    const itemTotal = (currentQuantity * price).toFixed(2);
    itemTotalElement.textContent = "$" + itemTotal;
    
    // If quantity is 0, remove the item
    if (currentQuantity === 0) {
        removeItem(productId);
        return;
    }
    
    // Update cart in the database using AJAX
    updateCartAjax(productId, currentQuantity);
    
    // Update order summary totals
    updateOrderSummary();
}

function removeItem(productId) {
    // Create form data
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', productId);
    
    // Send AJAX request to remove item
    fetch('cart_controller.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the item from DOM
            document.getElementById('cart-item-' + productId).remove();
            
            // Update cart count in navbar
            updateCartCount(data.unique_items_count);
            
            // If cart is empty, show empty cart message
            if (data.unique_items_count === 0) {
                showEmptyCartMessage();
            } else {
                // Update order summary
                updateOrderSummary();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function showEmptyCartMessage() {
    // Clear cart items container
    const cartItemsContainer = document.querySelector('.cart-items');
    const orderSummary = document.querySelector('.order-summary');
    
    // Remove order summary if it exists
    if (orderSummary) {
        orderSummary.remove();
    }
    
    // Keep the heading but update count to 0
    const heading = cartItemsContainer.querySelector('h2');
    if (heading) {
        heading.textContent = 'Shopping Cart (0 items)';
    }
    
    // Add empty cart message
    const emptyCartDiv = document.createElement('div');
    emptyCartDiv.className = 'empty-cart';
    emptyCartDiv.innerHTML = `
        <p>Your cart is empty.</p>
        <p>Continue <a href="/watch_store/public">shopping</a> to add items.</p>
    `;
    
    // Remove all items
    const items = cartItemsContainer.querySelectorAll('.item');
    items.forEach(item => item.remove());
    
    // Add empty cart message
    cartItemsContainer.appendChild(emptyCartDiv);
}

function updateCartAjax(productId, quantity) {
    // Create form data
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    // Send AJAX request
    fetch('cart_controller.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in navbar (unique items count)
            updateCartCount(data.unique_items_count);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateOrderSummary() {
    // Get all items and calculate subtotal
    const itemElements = document.querySelectorAll('.item');
    let subtotal = 0;
    
    itemElements.forEach(item => {
        const priceElement = item.querySelector('.item-price');
        const priceText = priceElement.textContent.replace('$', '');
        subtotal += parseFloat(priceText);
    });
    
    // Calculate tax and total
    const taxRate = 0.07; // Assuming 7% tax rate, adjust as needed
    const tax = subtotal * taxRate;
    const shipping = 0; // Free shipping
    const total = subtotal + tax + shipping;
    
    // Update order summary elements
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('tax').textContent = '$' + tax.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);
}

function updateCartCount(count) {
    // Update the cart count in the heading
    const headingElement = document.querySelector('.cart-items h2');
    if (headingElement) {
        const itemText = count === 1 ? 'item' : 'items';
        headingElement.textContent = `Shopping Cart (${count} ${itemText})`;
    }
    
    // Update the navbar cart count if it exists
    const navCartCount = document.querySelector('.cart-count');
    if (navCartCount) {
        navCartCount.textContent = count;
    }
}

function validateQuantity(itemId) {
    const quantityInput = document.getElementById('quantity-input-' + itemId);
    const min = parseInt(quantityInput.getAttribute('min'));
    const max = parseInt(quantityInput.getAttribute('max'));
    
    let currentValue = parseInt(quantityInput.value);
    
    // Handle NaN
    if (isNaN(currentValue)) {
        currentValue = min;
        quantityInput.value = min;
    }
}

    // Display messages if available in session
    <?php if (isset($_SESSION['message'])): ?>
    Swal.fire({
        title: 'Cart Updated',
        text: '<?php echo $_SESSION['message']; ?>',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
    <?php unset($_SESSION['message']); endif; ?>
</script>

<?php include './components/footer.html'; ?>
</body>
</html>