<?php

// Check if user is logged in (you'll need to implement this based on your authentication system)
// For demonstration, let's assume a user ID is available
// $userId = 15; // Replace with actual user ID from your authentication system


include '../../config/connectt.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {

    header('Location: http://localhost/watch_store_clone/public/views/signup_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


try {
    // Fetch user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "<pre>";
        print_r($user); // See what comes back from the database
        echo "</pre>";
        // die("User not found");
    }
    
    // Fetch payment history
    $paymentStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $paymentStmt->execute([$user_id]);
    $orders = $paymentStmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_cloud.css">
    <title>User Profile - <?php echo htmlspecialchars($user['name']); ?></title>
</head>
<body>
    <!-- <div class="status-bar">Connected successfully!</div> to test if con right--> 
    
    <main>
        <div class="profile">
        <div class="profile_img" style="background-image: url('<?php echo htmlspecialchars(($user['image'] != "") ? $user['image'] : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_640.png'); ?>');"></div>

            <div class="user_name"><h1><?php echo htmlspecialchars($user['name']); ?></h1></div>
            <div>
            <div class="edit_btn"><a href="http://localhost/watch_store_clone/public/views/profile_page/pro_edit.php" style="text-decoration: none; color: white;">Edit Profile</a></div>

            <div class="edit_btn"><a href="http://localhost/watch_store_clone/public/" style="text-decoration: none; color: white;">Home page</a></div>

            <div class="edit_btn"><a href="http://localhost/watch_store_clone/public/views/logout.php" style="text-decoration: none; color: white;"> logout </a></div>
        </div>
    </div>

        <div class="content-container">
            <div class="user_info">
                <p>email : <?php echo htmlspecialchars($user['email']); ?></p>
                <p>phone : <?php echo htmlspecialchars($user['phone_number']); ?></p>
                <p>Addres : <?php echo htmlspecialchars($user['country'] . ", " . $user['city']); ?></p>
                <!-- <p>Role : <?php echo htmlspecialchars($user['role']); ?></p> -->
            </div>
            
            <div class="history">
                <h2>History</h2>
                <div class="history_content">
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="history_item">
                                <div class="payment_info">
                                    <p class="payment_date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                                    <p class="payment_amount">$<?php echo number_format($order['total_price'],2); ?></p>
                                </div>
                                <div class="payment_description">
                                    <p><?php echo htmlspecialchars($order['description']); ?></p>
                                    <p class="payment_status <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></p>
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
    
</body>
</html>