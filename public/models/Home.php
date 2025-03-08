<?php 
    require_once "../config/database.php";

    class Home {
        private $conn;

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getTop5SaledProducts() {
            $query = "SELECT p.product_id, p.product_name, c.category_name as product_category, p.price, p.image, SUM(oi.quantity) as total_quantity 
              FROM orderitem oi
              JOIN products p ON oi.product_id = p.product_id
              JOIN categories c ON p.category_id = c.category_id
              GROUP BY p.product_id, p.product_name, product_category, p.price, p.image
              ORDER BY total_quantity DESC 
              LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>