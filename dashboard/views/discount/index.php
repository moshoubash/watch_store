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
      <div class="container main-content">
        <div class="page-inner">
          <h1>Discounts List</h1>
          <a href="index.php?controller=discount&action=create" class="btn btn-primary my-2">Add New Discount</a>
          <form action="index.php?controller=discount&action=search" method="POST" class="form-inline my-2 d-flex">
            <input type="text" name="keyword" class="form-control" placeholder="Search for discount">
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Percentage</th>
                <th>Usage Limit</th>
                <th>Expiration Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($discounts as $discount): ?>
              <tr>
                <td>
                  <?= $discount['id'] ?>
                </td>
                <td>
                  <?= $discount['name'] ?>
                </td>
                <td>
                  <?= $discount['discount_percentage'] ?>%
                </td>
                <td>
                  <?= $discount['limit'] ?>
                </td>
                <td>
                  <?= $discount['end_date'] ?>
                </td>
                <td>
                  <a href="index.php?controller=discount&action=edit&id=<?= $discount['id'] ?>"
                    class="btn btn-sm btn-dark"><i class="fas fa-edit"></i></a>
                  <!-- Button to trigger modal -->
                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                    data-target="#discountModal<?= $discount['id'] ?>">
                    <i class="fas fa-trash"></i>
                  </button>

                  <!-- Modal -->
                  <div class="modal fade" id="discountModal<?= $discount['id'] ?>" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel<?= $discount['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="discountModalLabel<?= $discount['id'] ?>">Delete Discount</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <p>Are you sure you want to delete this discount?</p>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <a href="index.php?controller=discount&action=delete&id=<?= $discount['id'] ?>"
                              class="btn btn-primary">Confirm</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
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