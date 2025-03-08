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
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h1>Orders List</h1>
              <!-- Generate Report Button -->
              <div>
                <a href="/watch_store/dashboard/services/orderReport/order_report.php" class="btn btn-danger">
                  <i class="fas fa-file-download"></i> PDF
                </a>
                <a href="/watch_store/dashboard/services/orderReport/order_report_csv.php" class="btn btn-success">
                  <i class="fas fa-file-download"></i> CSV
                </a>
              </div>
            </div>
          <div class="mb-3">
            <form action="index.php?controller=order&action=search" method="POST" class="form-inline d-flex">
              <input type="text" name="keyword" class="form-control" placeholder="Search by order id or customer id or status">
              <button type="submit" class="btn btn-primary">Search</button>
            </form>
          </div>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Customer ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Craeted Date</th>
                <th>Actions</th>
              </tr>
            </thead>  
            <tbody>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['user_id'] ?></td>
                <td><?= $order['total_price'] ?></td>
                <td>
                  <?php if ($order['status'] == 'pending'): ?>
                    <span class="badge bg-warning"><?= $order['status'] ?></span>
                  <?php elseif ($order['status'] == 'shipped'): ?>
                    <span class="badge bg-info"><?= $order['status'] ?></span>
                  <?php elseif ($order['status'] == 'delivered'): ?>
                    <span class="badge bg-success"><?= $order['status'] ?></span>
                  <?php elseif ($order['status'] == 'cancelled'): ?>
                    <span class="badge bg-danger"><?= $order['status'] ?></span>
                  <?php else: ?>
                    <span class="badge bg-dark"><?= $order['status'] ?></span>
                  <?php endif; ?>
                </td>
                <td><?= $order['created_at'] ?></td>
                <td>
                  <a href="index.php?controller=order&action=show&id=<?= $order['id'] ?>" class="btn btn-sm btn-dark"><i class="fas fa-info-circle"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <?php require_once "views/layouts/components/footer.html"; ?>
    </div>
  </div>

  <!--   Core JS Files   -->
  <?php require "views/layouts/components/scripts.html"; ?>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>