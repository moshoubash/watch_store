<?php
class Wishlist {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addToWishlist($user_id, $product_id) {
        // Check if item already exists in wishlist
        $checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("ii", $user_id, $product_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            return true; // Item already in wishlist
        }
        
        // Add new item to wishlist
        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }

    public function getWishlist($user_id) {
        // Modify query to ensure we get the product_id as 'id'
        $query = "SELECT p.id, p.name, p.price, p.image, p.stock, w.product_id 
                  FROM wishlist w 
                  INNER JOIN products p ON w.product_id = p.id 
                  WHERE w.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    
    public function removeFromWishlist($user_id, $product_id) {
        $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }
}