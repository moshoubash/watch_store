<?php
    require_once "config/database.php"; 

    class Category {
        private $conn;
        private $table = "categories";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllCategories() {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCategoryById($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createCategory($data) {
            $query = "INSERT INTO " . $this->table . " (name, description, image) 
                    VALUES (:name, :description, :image)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function updateCategory($data) {
            $query = "UPDATE " . $this->table . " 
                    SET name = :name, description = :description , image = :image
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function deleteCategory($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }

        public function searchCategories($keyword) {
            $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :keyword OR 
                                                               description LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":keyword", "%$keyword%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>