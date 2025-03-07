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
          <h1>Admin</h1>
          <a href="index.php?controller=admin&action=create" class="btn btn-primary my-2">Add New Admin</a>
          <form action="index.php?controller=admin&action=search" method="POST" class="form-inline my-2 d-flex">
            <input type="text" name="keyword" class="form-control" placeholder="Search for admins">
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($_GET['error'])) : ?>
                <div class="alert alert-danger">
                  <?php echo $_GET['error']; ?>
                </div>
              <?php endif; ?>
              
              <?php foreach ($admins as $admin): ?>
              <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= $admin['name'] ?></td>
                <td><?= $admin['email'] ?></td>
                <td><?= $admin['role'] ?></td>
                <td>
                  <!-- Button to trigger role change modal -->
                  <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#roleModal<?= $admin['id'] ?>">
                    <i class="fas fa-exchange-alt"></i>
                  </button>

                  <!-- Role Change Modal -->
                  <div class="modal fade" id="roleModal<?= $admin['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel<?= $admin['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="roleModalLabel<?= $admin['id'] ?>">Change Role</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="index.php?controller=admin&action=changeRole" method="POST">
                          <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                          <p>Are you sure you want to change the role of
                            <?= $admin['name'] ?> from
                            <?= $admin['role'] ?> to
                            <?= $admin['role'] == 'admin' ? 'superadmin' : 'admin' ?>?
                          </p>
                          <input type="hidden" name="newRole"
                            value="<?= $admin['role'] == 'admin' ? 'superadmin' : 'admin' ?>">
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <a href="index.php?controller=admin&action=edit&id=<?= $admin['id'] ?>" class="btn btn-sm btn-dark"><i class="fas fa-edit"></i></a>

                  <!-- Button to trigger delete modal -->
                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                  data-target="#adminModal<?= $admin['id'] ?>">
                  <i class="fas fa-trash"></i>
                  </button>

                  <!-- Delete Modal -->
                  <div class="modal fade" id="adminModal<?= $admin['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel<?= $admin['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="adminModalLabel<?= $admin['id'] ?>">Delete Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="index.php?controller=admin&action=delete" method="POST">
                          <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                          <p>Are you sure you want to delete this admin?</p>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <a href="index.php?controller=admin&action=delete&id=<?= $admin['id'] ?>" class="btn btn-primary">Confirm</a>
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