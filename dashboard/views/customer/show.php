

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Admin Dashboard</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

  <!-- Favicon -->
  <link rel="icon" href="assets/img/wrist-watch.ico" type="image/x-icon" />

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
            <h2>Customer Information</h2>
            <hr>
            <p><b>Name:</b> <?php echo $customer['name']; ?></p>
            <p><b>Email:</b> <?php echo $customer['email']; ?></p>
            <p><b>Phone:</b> <?php echo $customer['phone_number']; ?></p>

            <h2>Address Information</h2>
            <hr>
            <p><b>Country:</b> <?php echo $customer['country']; ?></p>
            <p><b>City:</b> <?php echo $customer['city']; ?></p>
            <p><b>State:</b> <?php echo $customer['state']; ?></p>
            <p><b>Street:</b> <?php echo $customer['street']; ?></p>
            <p><b>Postal Code:</b> <?php echo $customer['postal_code']; ?></p>

            <h2>Orders History</h2>
            <hr>
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Order Date</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td><?= $order['total_price'] ?></td>
                    <td><span class="badge bg-dark"><?= $order['status'] ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <a href="index.php?controller=customer&action=index" class="btn btn-primary">Back to list</a>
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