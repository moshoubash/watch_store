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
          <h1 class="mb-4">Add New Product</h1>
          <form method="POST" action="index.php?controller=product&action=store">
              <div class="row">
                  <div class="col-md-12">
                      <div class="mb-3">
                          <label class="form-label">Name:</label>
                          <input type="text" name="name" class="form-control" required>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Brand:</label>
                          <input type="text" name="brand" class="form-control">
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Category:</label>
                          <select name="category_id" class="form-select">
                            <?php 
                              foreach ($categories as $category) {
                                echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                              }
                            ?>
                          </select>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Color:</label>
                          <input type="text" name="color" class="form-control">
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="mb-3">
                          <label class="form-label">Description:</label>
                          <textarea name="description" class="form-control" rows="3"></textarea>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Image URL:</label>
                          <input type="text" name="image" class="form-control">
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Price:</label>
                          <input type="number" step="0.01" name="price" class="form-control" required>
                      </div>
                      <div class="mb-3">
                          <label class="form-label">Stock:</label>
                          <input type="number" name="stock" class="form-control" required>
                      </div>
                  </div>
              </div>
              <button type="submit" class="btn btn-primary px-4">Save</button>
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