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
      <?php require_once "views/layouts/components/sidebar.php"; ?>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php require_once "views/layouts/components/logoheader.php"; ?>
          </div>
          <!-- Navbar Header -->
          <?php require_once "views/layouts/components/navbar.php"; ?>          
        </div>  

        <!-- Main Content -->
        <div class="container">
          <div class="page-inner">
            <h1 class="mb-4">Edit Category</h1>
            <form method="POST" action="index.php?controller=category&action=update" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">

              <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
              </div>
              
              <?php if (isset($_GET['error']) && $_GET['error'] == 'invalidFileType'): ?>
                <div class="alert alert-danger" role="alert">
                  Invalid file type. Please upload a valid image file (Only JPG, JPEG, PNG, and GIF).
                </div>
              <?php endif; ?>

              <div class="mb-3">
                <label class="form-label">Image:</label>
                
                <?php if (!empty($category['image'])): ?>
                  <div class="mb-3">
                    <img src="assets/categoryImages/<?= htmlspecialchars($category['image']) ?>" alt="Category Image" class="img-fluid" style="max-width: 100px;">
                  </div>
                <?php endif; ?>

                <input type="file" name="image" class="form-control">
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($category['image']) ?>"> 
              </div>

              <button type="submit" class="btn btn-primary">Update Category</button>
              <a href="index.php?controller=category&action=index" class="btn btn-danger">Cancel</a>
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