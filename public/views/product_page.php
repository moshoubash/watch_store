<?php
  // Include the database connection file
  include('../config/connectt.php');

  // Get the watch details from the database
  $watchId = $_GET['id'] ?? 1;
  $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
  $stmt->execute([$watchId]);
  $watch = $stmt->fetch();

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: /watch_store/public/views/signup_login.php");
        exit();
    }
    
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1;
    
    // Check if user exists in the database first
    $userCheckQuery = "SELECT id FROM users WHERE id = :user_id";
    $userCheckStmt = $pdo->prepare($userCheckQuery);
    $userCheckStmt->bindParam(':user_id', $user_id);
    $userCheckStmt->execute();
    
    if ($userCheckStmt->rowCount() == 0) {
        // User doesn't exist in database - session is invalid
        session_unset();
        session_destroy();
        header("Location: /watch_store/public/views/signup_login.php?error=invalid_session");
        exit();
    }
    
    // Now proceed with cart operations
    $cartQuery = "SELECT id FROM cart WHERE user_id = :user_id";
    $cartStmt = $pdo->prepare($cartQuery);
    $cartStmt->bindParam(':user_id', $user_id);
    $cartStmt->execute();
    
    if ($cartStmt->rowCount() > 0) {
        $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
        $cart_id = $cart['id'];
    } else {
        try {
            $createCartQuery = "INSERT INTO cart (user_id, created_at) VALUES (:user_id, NOW())";
            $createCartStmt = $pdo->prepare($createCartQuery);
            $createCartStmt->bindParam(':user_id', $user_id);
            $createCartStmt->execute();
            $cart_id = $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Log error and redirect with meaningful message
            error_log("Cart creation error: " . $e->getMessage());
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=cart_creation");
            exit();
        }
    }
    
    $checkQuery = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':cart_id', $cart_id);
    $checkStmt->bindParam(':product_id', $product_id);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        $updateQuery = "UPDATE cart_items SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':quantity', $quantity);
        $updateStmt->bindParam(':cart_id', $cart_id);
        $updateStmt->bindParam(':product_id', $product_id);
        $updateStmt->execute();
    } else {
        $insertQuery = "INSERT INTO cart_items (cart_id, product_id, quantity, added_at) VALUES (:cart_id, :product_id, :quantity, NOW())";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':cart_id', $cart_id);
        $insertStmt->bindParam(':product_id', $product_id);
        $insertStmt->bindParam(':quantity', $quantity);
        $insertStmt->execute();
    }
    
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($watch['name'])?></title>
  <link rel="stylesheet" href="../assets/css/product_page.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
  
<?php include './components/navbar.html'; ?>

  <main class="product-container">
    <div class="product-image">
      <?php
        // Use the watch image from database
        $imageUrl =  $watch['image'] ? "/watch_store/dashboard/assets/productImages/" . $watch['image'] :  "/watch_store/dashboard/assets/productImages/placeholder.jpg";
      ?>
      <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Timex Waterbury Traditional Chronograph" />
    <style>
      .product-image {
        background-image: url("<?php echo htmlspecialchars($imageUrl); ?>");
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
      }
    </style>
    </div>

    <div class="product-details">
      <br>
      <br>
      <br>
      <div class="product-status">Brand : <?php echo " " . htmlspecialchars($watch['brand'])?></div>

      <h1 class="product-title"><?php echo htmlspecialchars($watch['name'])?></h1>
      
      <div class="product-size"><?php echo htmlspecialchars($watch['description'])?></div>

      <div class="product-color">
        <span class="color-label">Color:</span>
        <span class="color-value"><?php echo htmlspecialchars($watch['color'])?></span>
      </div>

      <div class="color-options">
        <div class="color-swatch selected">
          <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Stainless Steel Watch" />
        </div>
      </div>
      <form method="post">
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
    name="quantity"
    type="number"
    value="1"
    min="1"
    max="<?php echo htmlspecialchars($watch['stock'])?>"
  />
  <input 
    type="hidden"
    name="product_id"
    value="<?php echo htmlspecialchars($watch['id'])?>"
  />
</div>
      </div>
      <div class="product-actions">
        <button class="wishlist-btn">
          <i class="far fa-heart"></i>
        </button>
        <button class="add-to-bag-btn"  name="add_to_cart">
          Add to Bag
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>
      </form>

  
    </div>
  </main>

  <?php include './components/footer.html'; ?>

  <script src="../assets/js/navbar.js"></script>

</body>
</html>
