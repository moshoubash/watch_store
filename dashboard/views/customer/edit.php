<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Admin Dashboard</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

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
          <h1>Edit Customer</h1>
          
          <?php if (isset($_GET['error'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?php
                    if ($_GET['error'] === 'email_exists') {
                        echo "Email already exists!";
                    }
                ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php?controller=customer&action=update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($customer['id']) ?>">

            <div class="row">
              <div class="col-md-12">
                <hr>
                <div class="mb-3">
                  <label class="form-label">Name:</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']) ?>"
                    required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email:</label>
                  <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>
                
                <!--Hidden Role-->
                <input type="hidden" name="role" value="user" class="form-control">
                
                <div class="mb-3">
                  <label class="form-label">Phone Number:</label>
                  <input type="text" name="phone_number" class="form-control"
                    value="<?= htmlspecialchars($customer['phone_number']) ?>">
                </div>

                <input type="hidden" name="password" value="<?= htmlspecialchars($customer['password']) ?>">

                <h2>Address Details</h2>
                <hr>
                <div class="mb-3">
                  <label class="form-label">Country:</label>
                  <input type="text" name="country" class="form-control"
                    value="<?= htmlspecialchars($customer['country']) ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">City:</label>
                  <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer['city']) ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Street:</label>
                  <input type="text" name="street" class="form-control"
                    value="<?= htmlspecialchars($customer['street']) ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">State:</label>
                  <input type="text" name="state" class="form-control"
                    value="<?= htmlspecialchars($customer['state']) ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Postal Code:</label>
                  <input type="number" name="postal_code" class="form-control"
                    value="<?= htmlspecialchars($customer['postal_code']) ?>">
                </div>

                <button type="submit" class="btn btn-primary px-4">Update Customer</button>
                <a href="index.php?controller=customer&action=index" class="btn btn-danger">Cancel</a>
              </div>
            </div>
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