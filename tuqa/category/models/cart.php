<?php
class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserCart($user_id) {
        $query = "SELECT * FROM cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addToCart($user_id, $product_id, $quantity) {
        $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
}
