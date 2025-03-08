<?php
    require_once "config/database.php"; 

    class Discount {
        private $conn;
        private $table = "discounts";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllDiscounts() {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getDiscountById($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createDiscount($data) {
            $query = "INSERT INTO " . $this->table . " (name, `limit`, discount_percentage, start_date, end_date) 
                    VALUES (:name, :limit, :discount_percentage, :start_date, :end_date)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function updateDiscount($data) {
            $query = "UPDATE " . $this->table . " 
                    SET name = :name, `limit` = :limit, discount_percentage = :discount_percentage, 
                        start_date = :start_date, end_date = :end_date
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function deleteDiscount($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }

        public function searchDiscounts($keyword) {
            $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":keyword", "%$keyword%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>