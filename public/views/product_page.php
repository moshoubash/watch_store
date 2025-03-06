<?php
  // Include the database connection file
  include('../config/con.php');

  // Get the watch details from the database
  $watchId = 4;
  $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
  $stmt->execute([$watchId]);
  $watch = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/css/product_page.css" />
  <title><?php echo htmlspecialchars($watch['name'])?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  

  <main class="product-container">
    <div class="product-image">
      <?php
        // Use the watch image from database
        $imageUrl = $watch['image'] ?? 'https://timex.com/cdn/shop/files/8603_TX_TC24_feature_collection_TW2W98900.jpg?v=1739944454&width=768';
      ?>
      <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Timex Waterbury Traditional Chronograph" />
    </div>

    <div class="product-details">
      

      <br>
      <br>
      <br>

      <div class="product-status">Brand : <?php echo " " . htmlspecialchars($watch['brand'])?></div>

      <h1 class="product-title"><?php echo htmlspecialchars($watch['name'])?></h1>
      
      <div class="product-size">41 mm</div>

      <div class="product-color">
        <span class="color-label">Color:</span>
        <span class="color-value"><?php echo htmlspecialchars($watch['color'])?></span>
      </div>

      <div class="color-options">
        <div class="color-swatch selected">
          <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Stainless Steel Watch" />
        </div>
      </div>

      <div class="product-price">
        <div class="current-price"><?php echo htmlspecialchars($watch['price']).'$'?></div>
        <div class="payment-options">
          <?php if ($watch['stock'] > 0): ?>
            <span class="in-stock"><?php echo htmlspecialchars($watch['stock']) . " "?>In Stock</span>
          <?php else: ?>
            <span class="out-of-stock">Out of Stock</span>
          <?php endif; ?>
        </div>
        
        <div class="stock-input-container">
  <label for="stock_count" class="stock-label">Select Quantity</label>
  <input 
    id="stock_count"
    class="stock-input"
    type="number"
    value="1"
    min="1"
    max="<?php echo htmlspecialchars($watch['stock'])?>"
  />
</div>
      </div>

      <div class="product-actions">
        <button class="wishlist-btn">
          <i class="far fa-heart"></i>
        </button>
        <button class="add-to-bag-btn">
          Add to Bag
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>

  
    </div>
  </main>
</body>
</html>
