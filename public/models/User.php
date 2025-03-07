<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $country;
    public $state;
    public $city;
    public $street;
    public $postal_code;
    public $phone_number;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET
            name = :name,
            email = :email,
            country = :country,
            state = :state,
            city = :city,
            street = :street,
            postal_code = :postal_code,
            phone_number = :phone_number,
            password = :password,
            role = :role";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->street = htmlspecialchars(strip_tags($this->street));
        $this->postal_code = htmlspecialchars(strip_tags($this->postal_code));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':street', $this->street);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function findByEmail() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt;
    }
}
?>