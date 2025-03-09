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
            <h1 class="mb-4">Edit Discount</h1>
            <form method="POST" action="index.php?controller=discount&action=update">
              <input type="hidden" name="id" value="<?= htmlspecialchars($discount['id']) ?>">

              <div class="mb-3">
                  <label class="form-label">Name:</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($discount['name']) ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Usage Limit:</label>
                  <input name="limit" class="form-control" type="number" value="<?= htmlspecialchars($discount['limit']) ?>" />
              </div>

              <div class="mb-3">
                  <label class="form-label">Percentage:</label>
                  <input name="discount_percentage" class="form-control" type="number" value="<?= htmlspecialchars($discount['discount_percentage']) ?>" required/>
              </div>

              <div class="mb-3">
                  <label class="form-label">Start Date:</label>
                  <input name="start_date" class="form-control" type="date" value="<?= htmlspecialchars(date('Y-m-d', strtotime($discount['start_date']))) ?>" required/>
              </div>

              <div class="mb-3">
                  <label class="form-label">End Date:</label>
                  <input name="end_date" class="form-control" type="date" value="<?= htmlspecialchars(date('Y-m-d', strtotime($discount['end_date']))) ?>" required/>
              </div>
              <div class="mb-3">
                <label class="form-label">Coupon Code:</label>
                <input type="text" name="coupon_code" class="form-control" value="<?= htmlspecialchars($discount['coupon_code']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Discount Amount:</label>
                <input type="number" name="discount_amount" class="form-control" value="<?= htmlspecialchars($discount['discount_amount']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Limit Uses:</label>
                <input type="number" name="limit_uses" class="form-control" value="<?= htmlspecialchars($discount['limit_uses']) ?>" required>
              </div>

              <button type="submit" class="btn btn-primary">Update Discount</button>
              <a href="index.php?controller=discount&action=index" class="btn btn-danger">Cancel</a>
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