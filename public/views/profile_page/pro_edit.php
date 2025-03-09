<?php
session_start();
include '../../config/connectt.php';

if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $userId = (int)$_SESSION['user_id'];
} else {
    
    header("Location:  /watch_store/public/views/signup_login.php");
    exit;
}

// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone_number'] ?? '';
        $country = $_POST['country'] ?? '';
        $city = $_POST['city'] ?? '';
        $street = $_POST['street'] ?? '';
        $state = $_POST['state'] ?? '';
        $imageUrl = $_POST['image_url'] ?? ''; // Get image URL from form
        
        // Handle password update (only if provided)
        $passwordUpdateSQL = '';
        $params = [$name, $email, $phone, $country, $city, $street, $state];
        
        if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            // Check password requirements
                // Only proceed with password update if requirements are met
                if ($_POST['password'] === $_POST['confirm_password']) {
                    // Hash the password
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $passwordUpdateSQL = ', password = ?';
                    $params[] = $hashedPassword;
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Passwords Do Not Match',
                            text: 'Please ensure both passwords are identical',
                            confirmButtonColor: '#3085d6'
                        });
                    </script>";
                    // Still continue with other updates
                }
            
        }
        
        // Add image URL to SQL if provided
        $imageUpdateSQL = '';
        if (!empty($imageUrl)) {
            $imageUpdateSQL = ', image = ?';
            $params[] = $imageUrl;
        }
        
        // Add user ID to params
        $params[] = $userId;
        
        // Update user information
        $sql = "UPDATE users SET 
                name = ?, 
                email = ?, 
                phone_number = ?, 
                country = ?, 
                city = ?, 
                street = ?, 
                state = ? 
                $passwordUpdateSQL 
                $imageUpdateSQL 
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            $successMessage = 'Information updated successfully';
            header("Location: profile.php");
        } else {
            $errorMessage = 'Error updating information';
        }
    } catch (PDOException $e) {
        $errorMessage = 'Database error: ' . $e->getMessage();
    }
}

try {
    // Fetch current user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("User not found");
    }
    
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
    <title>Edit Profile - <?php echo htmlspecialchars($user['name']); ?></title>
    <style>
        .edit-form {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .cancel-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .profile-image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4CAF50;
        }
        
        .image-input-container {
            margin-top: 10px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <main>
        <h1 style="text-align: center;">Edit Personal Information</h1>
        
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <div class="edit-form">
            <form method="POST">
                <div class="profile-image-container">
                    <img class="profile-image" src="<?php echo htmlspecialchars(($user['image'] != "") ? $user['image'] : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_640.png'); ?>" alt="Profile Picture">
                    <div class="image-input-container">
                        <label for="image_url">Profile Image URL</label>
                        <input type="url" id="image_url" name="image_url" placeholder="Enter image URL here" value="<?php echo htmlspecialchars($user['image'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">New Password (Leave empty to keep current password)</label>
                        <input type="password" id="password" name="password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <br>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="street">Street</label>
                        <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($user['street']); ?>">
                    </div>
                </div>
                
                <div class="buttons">
                    <a href="profile.php" class="cancel-btn">Cancel</a>
                    <button type="submit" class="submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        // Basic form validation for password matching
        document.querySelector('form').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword && (password !== '' || confirmPassword !== '')) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Passwords Do Not Match',
            text: 'Please ensure both passwords are identical',
            confirmButtonColor: '#3085d6',
            timer: 5000
        });
    }
    // Check password requirements
    else if (password !== '' && (password.length < 8 || !/[a-zA-Z]/.test(password))) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Password',
            text: 'Password must be at least 8 characters long and contain at least one letter',
            confirmButtonColor: '#3085d6',
            timer: 5000
        });
    }
});
        
        // Preview image when URL is entered
        document.getElementById('image_url').addEventListener('input', function() {
            var imageUrl = this.value;
            var profileImage = document.querySelector('.profile-image');
            
            if (imageUrl) {
                profileImage.src = imageUrl;
            } else {
                profileImage.src = 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_640.png';
            }
        });
    </script>
    <!-- Add this in your HTML head section -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>