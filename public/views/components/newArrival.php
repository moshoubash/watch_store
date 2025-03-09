<?php 
    require_once "./config/database.php";
    $database = new Database();
    $conn = $database->getConnection();
    $query = "SELECT id, name, category_name as product_category, price, image 
              FROM products 
              ORDER BY created_at DESC 
              LIMIT 6";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="featured-products-carousel" id="dy-recommendations-18344768">
    <header class="featured-products-carousel__header">
        <div class="featured-products-carousel__text-content">
            <h2 class="featured-products-carousel__title">New Arrivals</h2>
            <p class="featured-products-carousel__subtitle">Check out the New Arrivals items</p>
        </div>
        <div class="featured-products-carousel__controls">
            <div class="carousel-arrows">
                <button type="button" class="arrow-button arrow-button--prev" aria-label="Previous" disabled>
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button type="button" class="arrow-button arrow-button--next" aria-label="Next">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="carousel-content">
        <ul class="carousel-list">
            <?php 
                foreach ($products as $product) {
                    echo '<li class="carousel-item">';
                    echo '<article class="product-card">';
                    echo '<div class="product-card__media">';
                    echo '<img src="/watch_store/dashboard/assets/productImages/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" width="400" height="500" class="product-card__image">';
                    echo '</div>';
                    echo '<div class="product-card__info">';
                    echo '<h3 class="product-card__title">' . '<a href="/watch_store/public/views/product_page.php?id=' . $product['id'] . '">' . htmlspecialchars($product['name']) . '</a>' . '</h3>';
                    echo '<p class="product-card__category">' . '<a href="/watch_store/public/views/category.php?categories=' . $product['product_category'] . '">' . htmlspecialchars($product['product_category']) . '</a>' . '</p>';
                    echo '<p class="product-card__price">$' . htmlspecialchars($product['price']) . '</p>';
                    echo '</div>';
                    echo '</article>';
                    echo '</li>';
                }
            ?>
        </ul>
    </div>
</section>