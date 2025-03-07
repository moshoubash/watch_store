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
                    <h1>Add New Admin</h1>
                    <?php if (isset($_GET['error'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php
                                if ($_GET['error'] === 'email_exists') {
                                    echo "Email already exists!";
                                } elseif ($_GET['error'] === 'password_mismatch') {
                                    echo "Passwords do not match!";
                                } elseif ($_GET['error'] === 'email_exists_password_mismatch') {
                                    echo "Email already exists and passwords do not match!";
                                }
                            ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="index.php?controller=admin&action=store">
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Name:</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role:</label>
                                    <select name="role" class="form-control">
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" name="phone_number" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password:</label>
                                    <input type="password" name="password" class="form-control" minlength="8">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm Password:</label>
                                    <input type="password" name="confirm-password" class="form-control" minlength="8">
                                </div>

                                <h2>Address Details</h2>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Country:</label>
                                    <input type="text" name="country" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City:</label>
                                    <input type="text" name="city" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Street:</label>
                                    <input type="text" name="street" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">State:</label>
                                    <input type="text" name="state" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Postal Code:</label>
                                    <input type="number" name="postal_code" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary px-4">Create</button>
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