<?php 
    require_once "config/database.php";

    class Admin{
        private $conn;
        private $table = "users";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllAdmins(){
            $query = "SELECT * FROM " . $this->table . " WHERE role = 'admin' or role = 'superadmin'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAdminById($id){
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createAdmin($data){
            $query = "SELECT COUNT(*) as count 
                      FROM " . $this->table . " 
                      WHERE email = :email";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $data['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if ($result['count'] > 0) {
                if ($data['password'] != $data['confirm-password']) {
                    header("Location: index.php?controller=admin&action=create&error=email_exists_password_mismatch");
                } else {
                    header("Location: index.php?controller=admin&action=create&error=email_exists");
                }
                exit();
            }
            
            if ($data['password'] != $data['confirm-password']) {
                header("Location: index.php?controller=admin&action=create&error=password_mismatch");
                exit();
            }
            
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table . " (name, email, role, phone_number, country, city, street, state, postal_code, password) 
                    VALUES (:name, :email, :role, :phone_number, :country, :city, :street, :state, :postal_code, :password)";
            $stmt = $this->conn->prepare($query);
            unset($data['confirm-password']);
            return $stmt->execute($data);
        }

        public function deleteAdmin($id){
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }

        public function updateAdmin($data){
            $query = "SELECT COUNT(*) as count 
                      FROM " . $this->table . " 
                      WHERE email = :email";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $data['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                header("Location: index.php?controller=admin&action=edit&error=email_exists&id=" . $data['id']);
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

        public function changeRole($data){
            $query = "UPDATE " . $this->table . " 
                    SET role = :role
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function updateSettings($data){
            $query = "SELECT COUNT(*) as count 
                      FROM " . $this->table . " 
                      WHERE email = :email";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $data['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                header("Location: index.php?controller=admin&action=settings&error=email_exists&id=" . $data['id']);
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
    }
?>