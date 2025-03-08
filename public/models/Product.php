<?php
class Product {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getProduct($id) {
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   c.name AS category_name, 
                   ws.type, 
                   ws.material, 
                   ws.water_resistant, 
                   ws.color, 
                   ws.dial_size, 
                   ws.strap_type,
                   ws.target_audience,
                   d.discount_percentage,
                   d.start_date,
                   d.end_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN watch_specs ws ON p.id = ws.product_id
            LEFT JOIN discounts d ON p.id = d.product_id 
                AND CURRENT_TIMESTAMP BETWEEN d.start_date AND d.end_date
            WHERE p.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getAllProducts($filters = []) {
        $query = "
            SELECT p.*, 
                   c.name AS category_name, 
                   ws.type, 
                   ws.color,
                   d.discount_percentage,
                   d.start_date,
                   d.end_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN watch_specs ws ON p.id = ws.product_id
            LEFT JOIN discounts d ON p.id = d.product_id 
                AND CURRENT_TIMESTAMP BETWEEN d.start_date AND d.end_date
            WHERE 1=1
        ";
        
        $params = [];
        
        // Add filter conditions
        if (!empty($filters['category'])) {
            $query .= " AND c.name = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['gender'])) {
            $query .= " AND p.gender = :gender";
            $params[':gender'] = $filters['gender'];
        }
        
        if (!empty($filters['type'])) {
            $query .= " AND ws.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getProductsByCategory($category) {
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   c.name AS category_name, 
                   ws.type, 
                   ws.color,
                   d.discount_percentage,
                   d.start_date,
                   d.end_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN watch_specs ws ON p.id = ws.product_id
            LEFT JOIN discounts d ON p.id = d.product_id 
                AND CURRENT_TIMESTAMP BETWEEN d.start_date AND d.end_date
            WHERE c.name = :category
        ");
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function updateStock($id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function searchProducts($keyword) {
        $search = "%$keyword%";
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   c.name AS category_name, 
                   ws.type, 
                   ws.color,
                   d.discount_percentage,
                   d.start_date,
                   d.end_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN watch_specs ws ON p.id = ws.product_id
            LEFT JOIN discounts d ON p.id = d.product_id 
                AND CURRENT_TIMESTAMP BETWEEN d.start_date AND d.end_date
            WHERE p.name LIKE :search OR p.description LIKE :search
        ");
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getProductPrice($id) {
        $product = $this->getProduct($id);
        
        // Check if there's an active discount
        if ($product['discount_percentage'] && 
            strtotime($product['start_date']) <= time() && 
            strtotime($product['end_date']) >= time()) {
            return $product['price'] * (1 - ($product['discount_percentage'] / 100));
        }
        
        return $product['price'];
    }
}
?>