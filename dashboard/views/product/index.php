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
          <h1>Products List</h1>
          <a href="index.php?controller=product&action=create" class="btn btn-primary my-2">Add New Product</a>
          <form action="index.php?controller=product&action=search" method="POST" class="form-inline my-2 d-flex">
            <input type="text" name="keyword" class="form-control" placeholder="Search for products">
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr>
                <td>
                  <?= $product['id'] ?>
                </td>
                <td><img src="assets/productImages/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="75">
                </td>
                <td>
                  <?= $product['name'] ?>
                </td>
                <td>
                  <?= $product['brand'] ?>
                </td>
                <td>$
                  <?= number_format($product['price'], 2) ?>
                </td>
                <td>
                  <?= $product['stock'] ?>
                </td>
                <td>
                  <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>"
                    class="btn btn-sm btn-dark"><i class="fas fa-edit"></i></a>
                  <!-- Button to trigger modal -->
                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                    data-target="#productModal<?= $product['id'] ?>">
                    <i class="fas fa-trash"></i>
                  </button>

                  <!-- Modal -->
                  <div class="modal fade" id="productModal<?= $product['id'] ?>" tabindex="-1" role="dialog"
                    aria-labelledby="productModalLabel<?= $product['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="productModalLabel<?= $product['id'] ?>">Delete Product</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="index.php?controller=admin&action=changeRole" method="POST">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <p>Are you sure for deleting this product ?</p>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                              <a href="index.php?controller=product&action=delete&id=<?= $product['id'] ?>"
                                class="btn btn-primary">Confirm</a>
                            </div>
                          </form>
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