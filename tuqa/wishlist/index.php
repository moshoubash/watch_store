<?php
include './config.php';
include './controllers/WishlistController.php';

// Use session to get current user
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$wishlistController = new WishlistController($conn);
$wishlistItems = $wishlistController->showWishlist($user_id);

// For debugging
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #F2F2F2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .wishlist-container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #FFFFFF;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .wishlist-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #DDD;
            margin-bottom: 20px;
        }
        .wishlist-header h1 {
            margin: 0;
            position: relative;
            padding-top: 30px; 
        }
        .wishlist-header h1::before {
            content: "\f004";  
            font-family: "Font Awesome 5 Free"; 
            font-size: 20px;
            color: #FF0000;
            position: absolute;
            top: -16px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px; 
        }
        .wishlist-header .actions {
            display: flex;
            justify-content: flex-end; 
            width: 100%; 
            margin-top: 15px;
        }
        .wishlist-header .actions button {
            padding: 9px 10px;
            background-color: #403431;
            color: #FFFFFF;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }
        .wishlist-header .actions button:hover {
            background-color: #6c757d;
        }
        
        /* Card Layout Styles */
        .wishlist-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .wishlist-card {
            border: 1px solid #DDD;
            border-radius: 8px;
            padding: 15px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
        }
        .wishlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-image-container {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .product-image {
            max-width: 100%;
            max-height: 180px;
            object-fit: contain;
        }
        .product-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            height: 40px;
            overflow: hidden;
        }
        .product-price {
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
            color: #403431;
        }
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
            margin-right: 5px;
        }
        .product-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .btn {
            padding: 8px 12px;
            background-color: #403431;
            color: #FFFFFF;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            flex: 1;
            text-align: center;
            margin: 0 5px;
            font-size: 13px;
        }
        .btn:hover {
            background-color: #6c757d;
        }
        .btn-remove {
            background-color: #d9534f;
        }
        .btn-remove:hover {
            background-color: #c9302c;
        }
        .btn-add-to-cart {
            background-color: #5cb85c;
        }
        .btn-add-to-cart:hover {
            background-color: #4cae4c;
        }
        .select-checkbox {
            position: absolute;
            top: 10px;
            right: 10px;
            transform: scale(1.3);
        }
        .empty-wishlist {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 18px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="wishlist-container">
        <?php if(isset($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        
        <div class="wishlist-header">
            <h1>Wishlist</h1>
            <?php if ($wishlistItems && $wishlistItems->num_rows > 0): ?>
            <div class="actions">
                <button id="add-selected" onclick="addSelectedToCart()">
                    <i class="fas fa-cart-plus"></i> Add Selected to Cart
                </button>
                <button onclick="addAllToCart()">
                    <i class="fas fa-cart-plus"></i> Add All to Cart
                </button>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($wishlistItems && $wishlistItems->num_rows > 0): ?>
            <form id="wishlistForm" method="post">
                <div class="wishlist-cards">
                    <?php while ($row = $wishlistItems->fetch_assoc()): ?>
                        <div class="wishlist-card">
                            <input type="checkbox" name="select[]" value="<?= $row['id']; ?>" class="select-checkbox">
                            <div class="product-image-container">
                                <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="product-image">
                            </div>
                            <div class="product-info">
                                <h3><?= $row['name']; ?></h3>
                                <div class="product-price">
                                    <?php if(isset($row['original_price']) && $row['original_price'] > $row['price']): ?>
                                        <span class="original-price"><?= $row['original_price']; ?> USD</span>
                                    <?php endif; ?>
                                    <?= $row['price']; ?> USD
                                </div>
                                <div class="stock-status">
                                    <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                                </div>
                                <div class="product-actions">
                                    <button type="button" onclick="removeFromWishlist(<?= $row['id']; ?>)" class="btn btn-remove">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                    <button type="button" onclick="addToCart(<?= $row['id']; ?>)" class="btn btn-add-to-cart" NAME="add_to_cart">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-wishlist">
                <i class="fas fa-heart-broken" style="font-size: 48px; color: #d9534f; margin-bottom: 20px;"></i>
                <p>Your wishlist is empty.</p>
                <button class="btn" onclick="location.href='../public/views/category.php'">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // For debugging
        function showAlert(message) {
            alert(message);
        }
        
        function removeFromWishlist(productId) {
            if(confirm('Are you sure you want to remove this item from your wishlist?')) {
                console.log("Removing product ID: " + productId);
                
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = 'remove_from_wishlist.php';
                
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_id';
                input.value = productId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function addToCart(productId) {
            console.log("Adding to cart product ID: " + productId);
            
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/watch_store_clone/watch_store/tuqa/wishlist/';
            
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_id';
            input.value = productId;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
        
        function addSelectedToCart() {
            const selectedProducts = document.querySelectorAll('.select-checkbox:checked');
            if(selectedProducts.length === 0) {
                alert('Please select at least one product.');
                return;
            }
            
            let form = document.getElementById('wishlistForm');
            form.action = 'add_selected_to_cart.php';
            form.submit();
        }
        
        function addAllToCart() {
            // Select all checkboxes
            const allCheckboxes = document.querySelectorAll('.select-checkbox');
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            
            // Submit the form
            let form = document.getElementById('wishlistForm');
            form.action = 'add_selected_to_cart.php';
            form.submit();
        }
    </script>
</body>
</html>