<?php
require_once '../controllers/profile.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <title>User Profile - <?php echo htmlspecialchars($user['name']); ?></title>
</head>
<body>
    <main>
        <div class="profile">
            <div class="profile_img" style="background-image: url('<?php echo htmlspecialchars(($user['profile_image'] != "") ? $user['profile_image'] : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_640.png'); ?>');"></div>
            <div class="user_name"><h1><?php echo htmlspecialchars($user['name']); ?></h1></div>
            <div class="edit_btn"><p>Edit</p></div>
        </div>

        <div class="content-container">
            <div class="user_info">
                <p>email : <?php echo htmlspecialchars($user['email']); ?></p>
                <p>phone : <?php echo htmlspecialchars($user['phone']); ?></p>
                <p>Addres : <?php echo htmlspecialchars($user['address']); ?></p>
                <p>Role : <?php echo htmlspecialchars($user['role']); ?></p>
            </div>

            <div class="history">
                <h2>History</h2>
                <div class="history_content">
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $payment): ?>
                            <div class="history_item">
                                <div class="payment_info">
                                    <p class="payment_date"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></p>
                                    <p class="payment_amount">$<?php echo number_format($payment['amount'], 2); ?></p>
                                </div>
                                <div class="payment_description">
                                    <p><?php echo htmlspecialchars($payment['description']); ?></p>
                                    <p class="payment_status <?php echo strtolower($payment['status']); ?>"><?php echo htmlspecialchars($payment['status']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no_history">No payment history available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script>
    </script>
</body>
</html>
