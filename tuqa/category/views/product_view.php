<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <h1>Products</h1>
<ul>
    <?php foreach ($products as $product): ?>
        <li>
            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
            <h3><?= $product['name']; ?></h3>
            <p><?= $product['description']; ?></p>
            <span><?= $product['price']; ?></span>
            <a href="cart.php?action=add&product_id=<?= $product['id']; ?>&user_id=<?= $user_id; ?>">Add to Cart</a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
