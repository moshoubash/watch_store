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

        public function getProductSales($product_id) {
            $query = "SELECT SUM(quantity) as total_sales 
                      FROM order_items 
                      WHERE product_id = :product_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'];
        }
    }
?>