<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin Dashboard</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

  <!-- Favicon -->
  <link rel="icon" href="assets/img/wrist-watch.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <?php require_once "views/layouts/components/fonts.html"; ?>

  <!-- Charts js -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
  <script src="assets/js/plugin/chart.js/chart.min.js"></script>
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
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-primary bubble-shadow-small">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Users</p>
                        <h4 class="card-title"><?php echo $totalCustomers; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-success bubble-shadow-small">
                        <i class="fas fa-luggage-cart"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Sales</p>
                        <h4 class="card-title">$ <?php echo $totalSales; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-info bubble-shadow-small">
                        <i class="fas fa-box"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Products</p>
                        <h4 class="card-title"><?php echo $totalProducts; ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-secondary bubble-shadow-small">
                        <i class="far fa-check-circle"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Order</p>
                        <h4 class="card-title"><?php echo $totalOrders;?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <div class="row">
            <!-- Doughnut Chart -->
            <div class="col-md-6">
              <div class="card">
              <div class="card-header">
              <div class="card-title">Top Sold Products</div>
              </div>
              <div class="card-body">
              <div class="chart-container"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
              <canvas id="doughnutChart" style="width: 680px; height: 300px; display: block;" width="1360" height="600" class="chartjs-render-monitor"></canvas>
              </div>
              </div>
              </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-md-6">
              <div class="card">
              <div class="card-header">
                <div class="card-title">Total Orders Per Month</div>
              </div>
              <div class="card-body">
                <div class="chart-container"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <canvas id="barChart" width="1360" height="600" style="display: block; height: 300px; width: 680px;" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
              </div>
            </div>
            </div>
          <div class="row">
            <div class="col">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                    <div class="card-title">Orders History</div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th scope="col">Order Id</th>
                          <th scope="col" class="text-end">Date & Time</th>
                          <th scope="col" class="text-end">Total Price</th>
                          <th scope="col" class="text-end">Status</th>
                        </tr>
                      </thead>
                        <tbody>
                          <?php foreach ($orders as $order): ?>
                          <tr>
                            <th scope="row">
                              #<?php echo $order['id']; ?>
                            </th>
                            <td class="text-end"><?php echo date('Y-m-d h:i:s A', strtotime($order['created_at'])); ?></td>
                            <td class="text-end">$<?php echo $order['total_price']; ?></td>
                            <td class="text-end">
                            <span class="badge badge-<?php echo $order['status'] == 'delivered' ? 'success' : 'info'; ?>"><?php echo $order['status']; ?></span>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <?php require_once "views/layouts/components/footer.html"; ?>
    </div>
  </div>

  <!--   Core JS Files   -->
  <?php require "views/layouts/components/scripts.html"; ?>

  <script>
    var ctx = document.getElementById('doughnutChart').getContext('2d');
    var doughnutChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: [<?php echo json_encode($product1Name); ?>, <?php echo json_encode($product2Name); ?>, <?php echo json_encode($product3Name); ?>],
        datasets: [{
        label: 'Top 3 Sold Products',
        data: [<?php echo $product1Sales; ?>, <?php echo $product2Sales; ?>, <?php echo $product3Sales; ?>],
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
        hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
      }
    );

    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        datasets: [{
          label: 'Total Orders',
          data: [<?php echo $ordersMonth1; ?>, <?php echo $ordersMonth2; ?>, <?php echo $ordersMonth3; ?>, <?php echo $ordersMonth4; ?>, <?php echo $ordersMonth5; ?>, <?php echo $ordersMonth6; ?>, <?php echo $ordersMonth7; ?>, <?php echo $ordersMonth8; ?>, <?php echo $ordersMonth9; ?>, <?php echo $ordersMonth10; ?>, <?php echo $ordersMonth11; ?>, <?php echo $ordersMonth12; ?>],
          backgroundColor: 'rgba(54, 162, 235, 0.8)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>

</html>