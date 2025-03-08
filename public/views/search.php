<?php
    include '../config/database.php';
    $keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
    $database = new Database();
    $conn = $database->getConnection();
    $query = "SELECT * FROM products WHERE name LIKE :keyword OR category_name LIKE :keyword OR brand like :keyword";
    $stmt = $conn->prepare($query);
    $stmt->execute(['keyword' => '%' . $keyword . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/category.css">
    <script src="https://kit.fontawesome.com/d890c03bb3.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php require_once "../views/components/navbar.html"; ?>

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
            <!--Main Content-->
            <div class="search-section">
                <h1>Search Results for "<i><?php echo htmlspecialchars($keyword); ?></i>"</h1>
            </div>
            
            <div class="product-list">
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $product): ?>
                        <div class="card" data-brand="TIMEX" data-price="199.00" data-color="Silver">
                            <a href="../views/product_page.php?id=<?= $product['id'] ?>" class="product-link">
                                <img src="/watch_store/dashboard/assets/productImages/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="Giorgio Galli S2Ti Swiss Made Automatic 38mm">
                            </a>

                            <a href="../views/product_page.php?id=<?= $product['id'] ?>" class="product-title">
                                <h3>Giorgio Galli S2Ti Swiss Made Automatic 38mm</h3>
                            </a>

                            <p class="size">38 mm</p>
                            <p class="price">$199.00</p>

                            <form method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                    <p>No results found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php require_once "./components/footer.html" ?>
    <script><?php require_once "../assets/js/navbar.js" ?></script>
    <script><?php require_once "../assets/js/category.js" ?></script>
</body>
</html>