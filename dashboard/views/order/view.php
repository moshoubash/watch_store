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
          <div class="d-flex justify-content-between align-items-center mb-3">
              <h1>Order Details</h1>
              <!-- Generate Report Button -->
                <a href="/watch_store/dashboard/services/orderReport/order_details_report.php?order_id=<?php echo $order['id']; ?>" class="btn btn-success">
                  <i class="fas fa-file-download"></i> Generate Report
                </a>
            </div>
          <h2>Customer Information</h2>
          <hr>
          <p><b>Name:</b> <?php echo $customer['name']; ?></p>
          <p><b>Email:</b> <?php echo $customer['email']; ?></p>
          <p><b>Phone:</b> <?php echo $customer['phone_number']; ?></p>

          <h2>Order Information</h2>
          <hr>
          <p><b>Order ID:</b> <?php echo $order['id']; ?></p>
          <p><b>Order Date:</b> <?php echo $order['created_at']; ?></p>
          <p><b>Status:</b> 
            <form action="index.php?controller=order&action=updateStatus" method="post">
              <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
              <select name="status" onchange="this.form.submit()" class="form-select">
                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
            </form>
          </p>
          <p><b>Total Amount:</b> <?php echo "$".$order['total_price']; ?></p>

          <h2>Order Items</h2>
          <hr>
          <table border="1" class="table table-striped">
              <thead class="table-dark">
                  <tr>
                      <th>Order Item ID</th>
                      <th>Product Image</th>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Price</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                      foreach ($orderItemsWithProductInfo as $item) {
                        echo "<tr>
                            <td>{$item['item']['id']}</td>
                            <td>
                              <img src='assets/productImages/{$item['product']['image']}' alt='{$item['product']['name']}' width='100'>
                            </td>
                            <td>{$item['product']['name']}</td>
                            <td>{$item['item']['quantity']}</td>
                            <td>{$item['item']['price']}</td>
                          </tr>";
                      }
                      ?>    
                    <tr class="table-dark">
                      <td class="text-center" colspan="5"><b>TOTAL : $<?php echo $order['total_price'] ?></b></td>
                    </tr>
              </tbody>
          </table>
          <a href="index.php?controller=order&action=index" class="btn btn-primary">Back to list</a>
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