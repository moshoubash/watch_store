<?php
$host="localhost";
$dbname="watch_store";
$username="root";
$password="";
$dsn="mysql:host=$host;dbname=$dbname";
try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Database Connection Failed: " . $e->getMessage();
}

$categories = isset($_GET['categories']) ? $_GET['categories'] : 'all';

$query = "SELECT p.*, ws.strap_type, ws.water_resistant, ws.material, ws.type, ws.color, ws.dial_size, ws.target_audience
          FROM products p 
          LEFT JOIN watch_specs ws ON p.id = ws.product_id";

    if ($categories !== 'all') {
        $query .= " WHERE p.category_name = :categories";
    }

    $stmt = $conn->prepare($query);
    if ($categories !== 'all') {
        $stmt->bindParam(':categories', $categories);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit();
    }
    
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1;
    
    $cartQuery = "SELECT id FROM cart WHERE user_id = :user_id";
    $cartStmt = $conn->prepare($cartQuery);
    $cartStmt->bindParam(':user_id', $user_id);
    $cartStmt->execute();
    
    if ($cartStmt->rowCount() > 0) {
        $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
        $cart_id = $cart['id'];
    } else {
        $createCartQuery = "INSERT INTO cart (user_id, created_at) VALUES (:user_id, NOW())";
        $createCartStmt = $conn->prepare($createCartQuery);
        $createCartStmt->bindParam(':user_id', $user_id);
        $createCartStmt->execute();
        $cart_id = $conn->lastInsertId();
    }
    
    $checkQuery = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':cart_id', $cart_id);
    $checkStmt->bindParam(':product_id', $product_id);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        $updateQuery = "UPDATE cart_items SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':quantity', $quantity);
        $updateStmt->bindParam(':cart_id', $cart_id);
        $updateStmt->bindParam(':product_id', $product_id);
        $updateStmt->execute();
    } else {
        $insertQuery = "INSERT INTO cart_items (cart_id, product_id, quantity, added_at) VALUES (:cart_id, :product_id, :quantity, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':cart_id', $cart_id);
        $insertStmt->bindParam(':product_id', $product_id);
        $insertStmt->bindParam(':quantity', $quantity);
        $insertStmt->execute();
    }
    
    header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['categories']) ? "?categories=".$_GET['categories'] : ""));
    exit();
}

$brandQuery = "SELECT DISTINCT brand FROM products ORDER BY brand ASC";
$brandStmt = $conn->prepare($brandQuery);
$brandStmt->execute();
$brands = $brandStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/category.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <title>Luxury Watches</title>
</head>

<body>
    <?php include './components/navbar.html'; ?>
    <main class="container">
        <aside class="sidebar">
            <h3>Filters</h3>
            <label for="priceRange">Price Range</label>
            <input id="priceRange" type="range" min="50" max="5000" value="2500">
            <span id="priceValue">$2500</span>
            <h4>Brand</h4>
            <select id="filter-brand">
                <option value="all">All Brands</option>
                <?php foreach ($brands as $brand): ?>
                <option value="<?= htmlspecialchars($brand) ?>">
                    <?= htmlspecialchars($brand) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <h4>Color</h4>
            <select id="filter-color">
                <option value="all">All Colors</option>
                <option value="Black">Black</option>
                <option value="Silver">Silver</option>
                <option value="gold">gold</option>
                <option value="Rose Gold">Rose Gold</option>
                <option value="Space">Space</option>
                <option value="Gray">Gray</option>
            </select>
            <h4>Key Features</h4>
            <label><input type="checkbox" id="feature-chrono"> Chronograph</label>
            <label><input type="checkbox" id="feature-auto"> Automatic</label>
            <label><input type="checkbox" id="feature-waterproof"> Waterproof</label>
            <button class="reset" id="resetFilters">Reset Filters</button>
        </aside>
        <section class="products">
            <h2>Luxury Watches
                <?= $categories !== 'all' ? ' - ' . ucfirst($categories) : '' ?>
            </h2>
            <p>Explore our exclusive collection of high-end luxury watches crafted with precision and elegance.</p>
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                <div class="card" data-brand="<?= htmlspecialchars($product['brand']) ?>"
                    data-price="<?= $product['price'] ?>" data-color="<?= htmlspecialchars($product['color'] ?? '') ?>">

                    <a href="../views/product_page.php?id=<?= $product['id'] ?>" class="product-link">
                        <img src="<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>" width="200" height="200">
                    </a>

                    <a href="../views/product_page.php?id=<?= $product['id'] ?>" class="product-title">
                        <h3>
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                    </a>

                    <p class="price">$
                        <?= number_format($product['price'], 2) ?>
                    </p>
                    <p class="brand">
                        <?= htmlspecialchars($product['brand']) ?>
                    </p>
                    <?php if (isset($product['color'])): ?>
                    <p class="color">Color:
                        <?= htmlspecialchars($product['color']) ?>
                    </p>
                    <?php echo "Product: " . $product['name'] . ", Color: " . ($product['color'] ?? 'None') . "<br>"; ?>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php include './components/footer.html'; ?>
    <script src="../assets/js/navbar.js"></script>
    <script src="../assets/js/category.js"></script>
</body>

</html>