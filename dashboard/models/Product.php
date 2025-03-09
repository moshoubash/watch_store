<?php
    require_once "config/database.php"; 

    class Product {
        private $conn;
        private $table = "products";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllProducts() {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProductById($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createProduct($data) {
            $query = "INSERT INTO " . $this->table . " (name, brand, category_id, color, description, image, price, stock) 
                    VALUES (:name, :brand, :category_id, :color, :description, :image, :price, :stock)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function updateProduct($data) {
            $query = "UPDATE " . $this->table . " 
                    SET name = :name, brand = :brand, category_id = :category_id, color = :color, 
                        description = :description, image = :image, price = :price, stock = :stock 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        }

        public function deleteProduct($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }

        public function searchProducts($keyword) {
            $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :keyword OR 
                                                               brand LIKE :keyword OR
                                                               description LIKE :keyword OR
                                                               color LIKE :keyword OR
                                                               category_name LIKE :keyword
                                                        ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":keyword", "%$keyword%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getTotalProducts() {
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }

        public function getTopThreeSoldProducts() {
            $query = "SELECT p.id, p.name, p.category_name as product_category, p.price, p.image, SUM(oi.quantity) as total_quantity 
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  GROUP BY p.id, p.name, p.category_name, p.price, p.image
                  ORDER BY total_quantity DESC 
                  LIMIT 3";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>