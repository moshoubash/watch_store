<?php
require_once 'Product.php';

class Cart {
    private $conn;
    private $user_id;
    
    public function __construct($db, $user_id = null) {
        $this->conn = $db;
        $this->user_id = $user_id;
    }
    
    public function addToCart($product_id, $quantity = 1) {
        // If user is logged in, use database cart
        if ($this->user_id) {
            // Check if product already exists in cart
            $stmt = $this->conn->prepare("
                SELECT id, quantity 
                FROM cart_items ci
                JOIN cart c ON ci.cart_id = c.id
                WHERE c.user_id = :user_id AND ci.product_id = :product_id
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $existing = $stmt->fetch();
            
            // If product exists, update quantity
            if ($existing) {
                $stmt = $this->conn->prepare("
                    UPDATE cart_items 
                    SET quantity = quantity + :quantity 
                    WHERE id = :cart_item_id
                ");
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':cart_item_id', $existing['id'], PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Create cart if not exists
                $stmt = $this->conn->prepare("
                    INSERT INTO cart (user_id) 
                    SELECT :user_id 
                    WHERE NOT EXISTS (SELECT 1 FROM cart WHERE user_id = :user_id)
                ");
                $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                $stmt->execute();
                
                // Get cart ID
                $stmt = $this->conn->prepare("
                    SELECT id FROM cart WHERE user_id = :user_id
                ");
                $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                $stmt->execute();
                $cart = $stmt->fetch();
                
                // Add item to cart
                $stmt = $this->conn->prepare("
                    INSERT INTO cart_items (cart_id, product_id, quantity) 
                    VALUES (:cart_id, :product_id, :quantity)
                ");
                $stmt->bindParam(':cart_id', $cart['id'], PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->execute();
            }
        } 
        // If no user, use session cart
        else {
            if (!isset($_SESSION['temp_cart'])) {
                $_SESSION['temp_cart'] = [];
            }
            
            if (isset($_SESSION['temp_cart'][$product_id])) {
                $_SESSION['temp_cart'][$product_id] += $quantity;
            } else {
                $_SESSION['temp_cart'][$product_id] = $quantity;
            }
        }
    }
    
    public function updateQuantity($product_id, $quantity) {
        // If user is logged in, update database cart
        if ($this->user_id) {
            $stmt = $this->conn->prepare("
                UPDATE cart_items ci
                JOIN cart c ON ci.cart_id = c.id
                SET ci.quantity = :quantity
                WHERE c.user_id = :user_id AND ci.product_id = :product_id
            ");
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
        } 
        // If no user, update session cart
        else {
            if ($quantity <= 0) {
                $this->removeFromCart($product_id);
            } else {
                $_SESSION['temp_cart'][$product_id] = $quantity;
            }
        }
    }
    
    public function removeFromCart($product_id) {
        // If user is logged in, remove from database cart
        if ($this->user_id) {
            $stmt = $this->conn->prepare("
                DELETE ci FROM cart_items ci
                JOIN cart c ON ci.cart_id = c.id
                WHERE c.user_id = :user_id AND ci.product_id = :product_id
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
        } 
        // If no user, remove from session cart
        else {
            if (isset($_SESSION['temp_cart'][$product_id])) {
                unset($_SESSION['temp_cart'][$product_id]);
            }
        }
    }
    
    public function getCartItems() {
        $product = new Product($this->conn);
        $items = [];
        
        // If user is logged in, get cart from database
        if ($this->user_id) {
            $stmt = $this->conn->prepare("
                SELECT ci.product_id, ci.quantity, 
                       p.name, p.price, p.image, 
                       c.name AS category, 
                       d.discount_percentage
                FROM cart_items ci
                JOIN cart cart ON ci.cart_id = cart.id
                JOIN products p ON ci.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN discounts d ON p.id = d.product_id 
                    AND CURRENT_TIMESTAMP BETWEEN d.start_date AND d.end_date
                WHERE cart.user_id = :user_id
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll();
        } 
        // If no user, get cart from session
        else {
            if (!isset($_SESSION['temp_cart']) || empty($_SESSION['temp_cart'])) {
                return [];
            }
            
            foreach ($_SESSION['temp_cart'] as $product_id => $quantity) {
                $productData = $product->getProduct($product_id);
                if ($productData) {
                    $productData['quantity'] = $quantity;
                    $items[] = $productData;
                }
            }
        }
        
        return $items;
    }
    
    public function getCartCount() {
        // If user is logged in, count from database
        if ($this->user_id) {
            $stmt = $this->conn->prepare("
                SELECT SUM(quantity) as total_count 
                FROM cart_items ci
                JOIN cart c ON ci.cart_id = c.id
                WHERE c.user_id = :user_id
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total_count'] ?? 0;
        } 
        // If no user, count from session
        else {
            $count = 0;
            if (isset($_SESSION['temp_cart'])) {
                foreach ($_SESSION['temp_cart'] as $quantity) {
                    $count += $quantity;
                }
            }
            return $count;
        }
    }
    
    public function getSubtotal() {
        $items = $this->getCartItems();
        $subtotal = 0;
        
        $product = new Product($this->conn);
        
        foreach ($items as $item) {
            // Get current price (with potential discount)
            $price = $product->getProductPrice($item['product_id'] ?? $item['id']);
            $quantity = $item['quantity'];
            
            $subtotal += $price * $quantity;
        }
        
        return $subtotal;
    }
    
    public function calculateTax($subtotal) {
        return $subtotal * TAX_RATE;
    }
    
    public function getTotal($subtotal, $tax, $shipping = 0) {
        return $subtotal + $tax + $shipping;
    }
    
    public function emptyCart() {
        // If user is logged in, empty database cart
        if ($this->user_id) {
            $stmt = $this->conn->prepare("
                DELETE ci FROM cart_items ci
                JOIN cart c ON ci.cart_id = c.id
                WHERE c.user_id = :user_id
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
        } 
        // If no user, empty session cart
        else {
            $_SESSION['temp_cart'] = [];
        }
    }
}
?>
