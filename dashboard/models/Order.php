<?php
    require_once "config/database.php"; 

    class Order {
        private $conn;
        private $table = "orders";

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function getAllOrders() {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getOrderById($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function searchOrders($keyword) {
            $query = "SELECT * FROM " . $this->table . " WHERE user_id LIKE :keyword OR 
                                                               status LIKE :keyword OR
                                                               id LIKE :keyword";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":keyword", "%$keyword%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getOrderItems($order_id) {
            $query = "SELECT * FROM order_items WHERE order_id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getOrdersByCustomerId($id) {
            $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateStatus($order_id, $status) {
            $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":order_id", $order_id);
            return $stmt->execute();
        }

        public function getTotalOrders() {
            $query = "SELECT COUNT(*) as total_orders FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_orders'];
        }

        public function getTotalSales() {
            $query = "SELECT SUM(total_price) as total_sales FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'];
        }

        public function getOrdersByMonth($month) {
            $query = "SELECT COUNT(*) as total_orders 
                      FROM " . $this->table . " 
                      WHERE MONTH(created_at) = :month";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":month", $month);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_orders'];
        }
    }
?>