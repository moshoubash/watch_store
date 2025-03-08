<?php 
    $user_id = $_SESSION['user_id'];
?>
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
                <h1>Admin settings</h1>
                <?php if (isset($_GET['error'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                            if ($_GET['error'] === 'email_exists') {
                                echo "Email already exists!";
                            }
                        ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="index.php?controller=admin&action=updateSettings" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id']) ?>">

                    <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <label class="form-label">Name:</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name']) ?>"
                            required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($admin['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role:</label>
                            <select name="role" class="form-control">
                                <option value="">Select Role</option>
                                <option value="admin" <?= $admin['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="superadmin" <?= $admin['role'] === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Phone Number:</label>
                        <input type="text" name="phone_number" class="form-control"
                            value="<?= htmlspecialchars($admin['phone_number']) ?>">
                        </div>
                        
                        <input type="hidden" name="password" value="<?= htmlspecialchars($admin['password']) ?>">

                        <h2>Address Details</h2>
                        <hr>
                        <div class="mb-3">
                        <label class="form-label">Country:</label>
                        <input type="text" name="country" class="form-control"
                            value="<?= htmlspecialchars($admin['country']) ?>">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">City:</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($admin['city']) ?>">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Street:</label>
                        <input type="text" name="street" class="form-control"
                            value="<?= htmlspecialchars($admin['street']) ?>">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">State:</label>
                        <input type="text" name="state" class="form-control"
                            value="<?= htmlspecialchars($admin['state']) ?>">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Postal Code:</label>
                        <input type="number" name="postal_code" class="form-control"
                            value="<?= htmlspecialchars($admin['postal_code']) ?>">
                        </div>

                        <div>
                        <button type="submit" class="btn btn-primary px-4">Update admin</button>
                        <a href="/watch_store/dashboard" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                    </div>
                </form>
                </div>
            </div>
            
            <!-- Footer -->
            <?php require_once "views/layouts/components/footer.html"; ?>
        </div>

        <!--   Core JS Files   -->
        <?php require "views/layouts/components/scripts.html"; ?>
</body>

</html>