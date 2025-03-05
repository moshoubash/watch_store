<?php
class Wishlist {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addToWishlist($user_id, $product_id) {
        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }

    public function getWishlist($user_id) {
        $query = "SELECT p.id, p.name, p.price, p.image 
                  FROM wishlist w 
                  INNER JOIN products p ON w.product_id = p.id 
                  WHERE w.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>
