<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $UserName;
    public $Email;
    public $country;
    public $state;
    public $city;
    public $s_address;
    public $zip;
    public $phone;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET
            UserName = :UserName,
            Email = :Email,
            country = :country,
            state = :state,
            city = :city,
            s_address = :s_address,
            zip = :zip,
            pho_num = :phone,
            password = :password,
            role = :role";

        $stmt = $this->conn->prepare($query);

        $this->UserName = htmlspecialchars(strip_tags($this->UserName));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->s_address = htmlspecialchars(strip_tags($this->s_address));
        $this->zip = htmlspecialchars(strip_tags($this->zip));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(':UserName', $this->UserName);
        $stmt->bindParam(':Email', $this->Email);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':s_address', $this->s_address);
        $stmt->bindParam(':zip', $this->zip);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function findByEmail() {
        $query = "SELECT * FROM " . $this->table . " WHERE Email = :Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Email', $this->Email);
        $stmt->execute();
        return $stmt;
    }
}
?>