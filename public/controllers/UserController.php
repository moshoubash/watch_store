<?php
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../controllers/UserController.php';
class UserController {
    private $db;
    private $users;

    public function __construct($db) {
        $this->db = $db;
        $this->users = new users($db);
    }

    public function register($data) {
        $this->users->name = $data['name'];
        $this->users->email = $data['email'];
        $this->users->country = $data['country'];
        $this->users->state = $data['state'];
        $this->users->city = $data['city'];
        $this->users->street = $data['street'];
        $this->users->postal_code = $data['postal_code'];
        $this->users->phone_number = $data['phone_number'];
        $this->users->password = $data['password'];
        $this->users->role = 'users';

        if ($this->users->findByemail()->rowCount() > 0) {
            return ['error' => 'email already exists'];
        }

        if ($this->users->create()) {
            return ['success' => 'New record created successfully'];
        }

        return ['error' => 'Failed to create new record'];
    }

    public function login($data) {
        $this->users->email = $data['email'];
        $stmt = $this->users->findByemail();
        $users = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$users || !password_verify($data['password'], $users['password'])) {
            return ['error' => 'Invalid email or password'];
        }

        $_SESSION['users_id'] = $users['id'];
        $_SESSION['email'] = $users['email'];
        $_SESSION['name'] = $users['name'];
        $_SESSION['role'] = $users['role'];

        if ($users['role'] === 'superadmin') {
            header("Location: ../superadmin_dashboard.php");
        } elseif ($users['role'] === 'admin') {
            header("Location: ../admin_dashboard.php");
        } else {
            // header("Location: ../index.php");
            echo"<script>alert('Login Successful')</script>";
        }
        exit();
    }
}
?>