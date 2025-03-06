<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    
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
            <h1>Customers List</h1>
            
            <a href="index.php?controller=customer&action=create" class="btn btn-primary my-2">Add New Customer</a>
            
            <form action="index.php?controller=customer&action=search" method="POST" class="form-inline my-2 d-flex">
              <input type="text" name="keyword" class="form-control" placeholder="Search by name or email">
              <button type="submit" class="btn btn-primary">Search</button>
            </form>
            
            <table class="table table-striped">
              <thead class="table-dark">
                  <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (isset($_GET['error'])) : ?>
                    <div class="alert alert-danger">
                      <?php echo $_GET['error']; ?>
                    </div>
                  <?php endif; ?>
                  <?php foreach ($customers as $customer): ?>
                  <tr>
                  <td><?= $customer['id'] ?></td>
                  <td><?= $customer['name'] ?></td>
                  <td><?= $customer['email'] ?></td>
                  <td><?= $customer['phone_number'] ?></td>
                  <td>
                    <a href="index.php?controller=customer&action=edit&id=<?= $customer['id'] ?>" class="btn btn-sm btn-dark"><i class="fas fa-edit"></i></a>
                    <a href="index.php?controller=customer&action=show&id=<?= $customer['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-info-circle"></i></a>
                    <!-- Button to trigger modal -->
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                      data-target="#customerModal<?= $customer['id'] ?>">
                      <i class="fas fa-trash"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="customerModal<?= $customer['id'] ?>" tabindex="-1" role="dialog"
                      aria-labelledby="customerModalLabel<?= $customer['id'] ?>" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="customerModalLabel<?= $customer['id'] ?>">Delete Customer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="index.php?controller=admin&action=changeRole" method="POST">
                              <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                              <p>Are you sure for deleting this customer ?</p>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <a href="index.php?controller=customer&action=delete&id=<?= $customer['id'] ?>"
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