<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db);
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $messages = $userController->register($_POST);
    } elseif (isset($_POST['login'])) {
        $messages = $userController->login($_POST);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Login Page</title>
    <link rel="stylesheet" href="../assets/Timex.css">
</head>
<body>
    <div class="form-container">
        <h1>Log in</h1>
        <div class="tabs">
            <div class="tab active" id="loginTab">Log In</div>
            <div class="tab" id="signupTab">Sign Up</div>
        </div>
        
        <?php if (isset($messages['error'])): ?>
            <div class="message error">
                <span><?php echo $messages['error']; ?></span>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">X</span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($messages['success'])): ?>
            <div class="message success">
                <span><?php echo $messages['success']; ?></span>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">X</span>
            </div>
        <?php endif; ?>
        
        <form id="loginForm" class="form active" method="POST" action="">
            <div class="form-group">
                <label for="loginEmail">Email Address*</label>
                <input type="email" id="loginEmail" name="Email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password*</label>
                <input type="password" id="loginPassword" name="password" placeholder="Password" required>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe" style="display: inline; margin-left: 10px;">Remember me</label>
            </div>
            <button type="submit" name="login" class="submit-btn">Log In</button>
            <a href="#" class="alt-link" id="goToSignup">
                Need an account? Sign up
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="M12 5l7 7-7 7"></path>
                </svg>
            </a>
        </form>
        <form id="signupForm" class="form" method="POST" action="">
            <div class="form-group">
                <label for="firstName">Username*</label>
                <input type="text" id="firstName" name="UserName" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="signupEmail">Email Address*</label>
                <input type="email" id="signupEmail" name="Email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <label for="Address">Address</label>
                <div class="address">
                    <input type="text" id="country" name="country" placeholder="country" required>
                    <input type="text" id="state" name="state" placeholder="state (Option)">
                    <input type="text" id="street_address" name="street_address" placeholder="Street address (Option)">
                    <input type="text" id="city" name="city" placeholder="City" required>
                    <input type="text" id="zip" name="zip" placeholder="ZIP" required>
                </div>
            </div>
            <div class="form-group">
                <label for="Phone">Phone*</label>
                <input type="number" id="phone_number" name="pho_num" placeholder="Phone Number" required>
            </div>
            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Re-enter Password*</label>
                <input type="password" id="confirmPassword" name="cpassword" placeholder="Password" required>
            </div>
            <button type="submit" name="submit" class="submit-btn">Submit</button>
            <a href="#" class="alt-link" id="goToLogin">
                Already have an account?
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="M12 5l7 7-7 7"></path>
                </svg>
            </a>
        </form>
    </div>
    <div class="image-container"></div>
    <script>
        const heading = document.querySelector('h1');
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');
        const goToLogin = document.getElementById('goToLogin');
        const goToSignup = document.getElementById('goToSignup');

        function showLogin() {
            loginTab.classList.add('active');
            signupTab.classList.remove('active');
            loginForm.classList.add('active');
            signupForm.classList.remove('active');
            heading.textContent = 'Log In';
        }

        function showSignup() {
            signupTab.classList.add('active');
            loginTab.classList.remove('active');
            signupForm.classList.add('active');
            loginForm.classList.remove('active');
            heading.textContent = 'Sign Up';
        }

        loginTab.addEventListener('click', showLogin);
        signupTab.addEventListener('click', showSignup);

        goToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            showLogin();
        });

        goToSignup.addEventListener('click', function(e) {
            e.preventDefault();
            showSignup();
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".close-btn").forEach(btn => {
                btn.addEventListener("click", function () {
                    this.parentElement.style.display = "none";
                });
            });
        });

        <?php if (isset($messages['error']) && strpos($_SERVER['REQUEST_URI'], 'login') !== false): ?>
            showLogin();
        <?php endif; ?>

        <?php if (isset($messages['success'])): ?>
            showLogin();
        <?php endif; ?>
    </script>
    
</body>
</html>