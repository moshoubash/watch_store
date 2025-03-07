<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProductsByCategory($category_id) {
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
