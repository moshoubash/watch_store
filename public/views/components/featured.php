<?php 
    require_once "./config/database.php";
    $database = new Database();
    $conn = $database->getConnection();
    $query = "SELECT p.id, p.name, p.category_name as product_category, p.price, p.image, SUM(oi.quantity) as total_quantity 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.id
              GROUP BY p.id, p.name, p.category_name, p.price, p.image
              ORDER BY total_quantity DESC 
              LIMIT 6";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="featured-products-carousel" id="dy-recommendations-18344768">
    <header class="featured-products-carousel__header">
        <div class="featured-products-carousel__text-content">
            <h2 class="featured-products-carousel__title">Featured Products</h2>
            <p class="featured-products-carousel__subtitle">Check our best watches</p>
        </div>
        <div class="featured-products-carousel__controls">
            <div class="carousel-arrows">
                <button type="button" class="arrow-button arrow-button--prev" aria-label="Previous" disabled>
                    <svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true">
                        <path d="M10 2L4 8l6 6" stroke="#333" stroke-width="2" fill="none"/>
                    </svg>
                </button>
                <button type="button" class="arrow-button arrow-button--next" aria-label="Next">
                    <svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true">
                        <path d="M2 2l6 6-6 6" stroke="#333" stroke-width="2" fill="none"/>
                    </svg>
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
                    echo '<h3 class="product-card__title">' . htmlspecialchars($product['name']) . '</h3>';
                    echo '<p class="product-card__category">' . htmlspecialchars($product['product_category']) . '</p>';
                    echo '<p class="product-card__price">$' . htmlspecialchars($product['price']) . '</p>';
                    echo '</div>';
                    echo '</article>';
                    echo '</li>';
                }
            ?>
            
        </ul>
    </div>
</section>