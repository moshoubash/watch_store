<?php
    require_once "config/database.php"; 

    class Customer {
        private $conn;
        private $table = "users";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllCustomers() {
            $query = "SELECT * FROM " . $this->table . " WHERE role = 'user'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCustomerById($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createCustomer($data) {
            $query = "SELECT COUNT(*) as count 
                      FROM " . $this->table . " 
                      WHERE email = :email";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $data['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                if ($data['password'] != $data['confirm_password']) {
                    header("Location: index.php?controller=customer&action=create&error=email_exists_password_mismatch");
                } else {
                    header("Location: index.php?controller=customer&action=create&error=email_exists");
                }
                exit();
            }
            
            if ($data['password'] != $data['confirm_password']) {
                header("Location: index.php?controller=customer&action=create&error=password_mismatch");
                exit();
            }
            
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table . " (name, email, role, phone_number, country, city, street, state, postal_code, password) 
                    VALUES (:name, :email, :role, :phone_number, :country, :city, :street, :state, :postal_code, :password)";
            $stmt = $this->conn->prepare($query);
            unset($data['confirm_password']);
            return $stmt->execute($data);
        }

        public function updateCustomer($data) {
            $query = "SELECT COUNT(*) as count 
                      FROM " . $this->table . " 
                      WHERE email = :email";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $data['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                header("Location: index.php?controller=customer&action=edit&error=email_exists&id=" . $data['id']);
                exit();
            }
            
            $query = "UPDATE " . $this->table . " 
                    SET name = :name,
                        email = :email, 
                        role = :role, 
                        phone_number = :phone_number, 
                        country = :country, 
                        city = :city, 
                        street = :street, 
                        state = :state, 
                        postal_code = :postal_code,
                        password = :password
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function deleteCustomer($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }

        public function searchCustomers($keyword) {
            $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :keyword OR 
                                                               email LIKE :keyword OR
                                                               phone_number LIKE :keyword OR
                                                               country LIKE :keyword OR
                                                               city LIKE :keyword OR
                                                               street LIKE :keyword OR
                                                               state LIKE :keyword OR
                                                               postal_code LIKE :keyword";
                                                               
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":keyword", "%$keyword%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getTotalCustomers() {
            $query = "SELECT COUNT(*) as total_customers FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_customers'];
        }

        public function getOrdersByCustomerId($id) {
            $query = "SELECT * FROM orders WHERE user_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>