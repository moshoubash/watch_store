<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
</head>
<body>
    <h1>Your Wishlist</h1>

    <?php if ($wishlistItems->num_rows > 0): ?>
        <ul>
            <?php while ($row = $wishlistItems->fetch_assoc()): ?>
                <li>
                    <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" width="100">
                    <p><?= $row['name']; ?></p>
                    <p><?= $row['price']; ?> USD</p>
                    <a href="product_details.php?id=<?= $row['id']; ?>">View Details</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Your wishlist is empty.</p>
    <?php endif; ?>
</body>
</html>
