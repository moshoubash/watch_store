<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    
    <!-- Favicon -->
    <link rel="icon" href="../../assets/img/wrist-watch.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <?php require_once "views/layouts/components/fonts.html"; ?>

  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <?php require_once "views/layouts/components/sidebar.html"; ?>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php require_once "views/layouts/components/logoheader.html"; ?>
          </div>
          <!-- Navbar Header -->
          <?php require_once "views/layouts/components/navbar.html"; ?>          
        </div>  

        <!-- Main Content -->
        <div class="container">
          <div class="page-inner">
          <h1 class="mb-4">Edit Product</h1>
            <form method="POST" action="index.php?controller=product&action=update" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

              <div class="mb-3">
                  <label class="form-label">Name:</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Brand:</label>
                  <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']) ?>" required>
              </div>

                <div class="mb-3">
                  <label class="form-label">Category:</label>
                  <select name="category_id" class="form-select">
                  <?php 
                    foreach ($categories as $category) {
                      $selected = $category['id'] == $product['category_id'] ? 'selected' : '';
                      echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
                    }
                  ?>
                  </select>
                </div>

              <div class="mb-3">
                  <label class="form-label">Color:</label>
                  <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($product['color']) ?>">
              </div>

              <div class="mb-3">
                  <label class="form-label">Description:</label>
                  <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
              </div>

                <div class="mb-3">
                  <label class="form-label">Image:</label>
                  <?php if (!empty($product['image'])): ?>
                    <div class="mb-3">
                      <img src="assets/productImages/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="img-fluid" style="max-width: 100px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" name="image" class="form-control">
                  <input type="hidden" name="old_image" value="<?= htmlspecialchars($product['image']) ?>"> 
                </div>

              <div class="mb-3">
                  <label class="form-label">Price:</label>
                  <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Stock:</label>
                  <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($product['stock']) ?>" required>
              </div>

              <button type="submit" class="btn btn-primary">Update Product</button>
              <a href="index.php?controller=product&action=index" class="btn btn-danger">Cancel</a>
            </form>
          </div>
        </div>

        <!-- Footer -->
        <?php require_once "views/layouts/components/footer.html"; ?>
      </div>
    </div> 
    
    <!--   Core JS Files   -->
    <?php require "views/layouts/components/scripts.html"; ?>
  </body>
</html>