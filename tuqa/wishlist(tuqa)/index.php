<?php
include './config.php';
include './controllers/WishlistController.php';

$wishlistController = new WishlistController($conn);
$user_id = 1;  
$wishlistItems = $wishlistController->showWishlist($user_id);
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
        }
        .wishlist-header .actions button {
            padding: 9px 10px;
            background-color: #403431;
            color: #FFFFFF;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .wishlist-header .actions button:hover {
            background-color: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #DDD;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #F7F7F7;
        }
        .product-image {
            max-width: 100px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #403431;
            color: #FFFFFF;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #6c757d;
        }
        .btn-remove, .btn-add-to-cart {
            background-color: #d9534f;
        }
        .btn-remove:hover, .btn-add-to-cart:hover {
            background-color: #c9302c;
        }
        .actions button {
            margin: 10px;
        }

    </style>

</head>
<body>
    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1>Wishlist</h1>
            <div class="actions">
                <button onclick="location.href=' '">
                    <i class="fas fa-cart-plus"></i> Add Selected to Cart
                </button>
                <button onclick="location.href=' '">
                    <i class="fas fa-cart-plus"></i> Add All to Cart
                </button>
            </div>
        </div>
        <?php if ($wishlistItems && $wishlistItems->num_rows > 0): ?>
            <form method="post" action="update_wishlist.php">
                <table>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Stock Status</th>
                        <th>Added Date</th>
                        <th>Actions</th>
                        <th>Select</th>
                    </tr>
                    <?php while ($row = $wishlistItems->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="product-image"></td>
                            <td><?= $row['name']; ?></td>
                            <td>
                                <?php if(isset($row['original_price']) && $row['original_price'] > $row['price']): ?>
                                    <span style="text-decoration: line-through;"><?= $row['original_price']; ?> USD</span> <?= $row['price']; ?> USD
                                <?php else: ?>
                                    <?= $row['price']; ?> USD
                                <?php endif; ?>
                            </td>
                            <td><input type="number" name="quantity[<?= $row['id']; ?>]" value="1" min="1" style="width: 50px;"></td>
                            <td>In Stock</td>
                            <td><?= date('Y-m-d'); ?></td>
                            <td>
                                <form method="post" action="remove_from_wishlist.php" style="display:inline-block;">
                                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="btn btn-remove">Remove</button>
                                </form>
                                <form method="post" action="add_to_cart.php" style="display:inline-block;">
                                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                                </form>
                            </td>
                            <td><input type="checkbox" name="select[]" value="<?= $row['id']; ?>"></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </form>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>

    </div>
</body>
</html>
