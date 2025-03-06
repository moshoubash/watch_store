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
          <h1 class="mb-4">Add New Category</h1>
          <form method="POST" action="index.php?controller=category&action=store" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                      <label class="form-label">Name:</label>
                      <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Description:</label>
                      <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalidFileType'): ?>
                      <div class="alert alert-danger" role="alert">
                        Invalid file type. Please upload a valid image file (Only JPG, JPEG, PNG, and GIF).
                      </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                      <label class="form-label">Image:</label>
                      <input type="file" name="image" class="form-control" accept="image/*" />
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