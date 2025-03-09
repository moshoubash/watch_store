<?php
class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function register($data) {

        if (strlen($data['password']) < 8 || !preg_match('/[a-zA-Z]/', $data['password'])) {
            return ['error' => 'Password must be at least 8 characters long and contain at least one letter'];
        }
    
        $this->user->name = $data['UserName'];
        $this->user->email = $data['Email'];
        $this->user->country = $data['country'];
        $this->user->state = $data['state'];
        $this->user->city = $data['city'];
        $this->user->street = $data['street_address'];
        $this->user->postal_code = $data['zip'];
        $this->user->phone_number = $data['pho_num'];
        $this->user->password = $data['password'];
        $this->user->role = 'user';
    
        if ($this->user->findByEmail()->rowCount() > 0) {
            return ['error' => 'Email already exists'];
        }
    
        if ($this->user->create()) {
            return ['success' => 'New record created successfully'];
        }
    
        return ['error' => 'Failed to create new record'];
    }

    public function login($data) {
        $this->user->email = $data['Email'];
        $stmt = $this->user->findByEmail();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ['error' => 'Invalid email or password'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'superadmin') {
            header("Location: /watch_store/dashboard/");
        } elseif ($user['role'] === 'admin') {
            header("Location: /watch_store/dashboard/");
        } else {
            header("Location: /watch_store/public/");
        }
        exit();
    }
}
?>