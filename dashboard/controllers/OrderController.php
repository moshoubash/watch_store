<?php
    require_once "models/Customer.php";
    require_once "models/Order.php";

    class OrderController {
        private $orderModel;
        private $customerModel;
        private $productModel;

        public function __construct() {
            $this->orderModel = new Order();
            $this->customerModel = new Customer();
            $this->productModel = new Product();
        }

        // Display all orders
        public function index() {
            $orders = $this->orderModel->getAllOrders();
            include "views/order/index.php";
        }

        // Show order details
        public function show() {
            $id = $_GET["id"];
            $order = $this->orderModel->getOrderById($id);
            $customer = $this->customerModel->getCustomerById($order["user_id"]);
            $orderItems = $this->orderModel->getOrderItems($id);
            
            $orderItemsWithProductInfo = [];
            foreach ($orderItems as $item) {
                $product = $this->productModel->getProductById($item["product_id"]);
                $orderItemsWithProductInfo[] = [
                    "item" => $item,
                    "product" => $product
                ];
            }

            include "views/order/view.php";
        }

        // Search for orders
        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $orders = $this->orderModel->searchOrders($keyword);
                include "views/order/index.php";
            }
        }

        public function updateStatus() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $order_id = $_POST["order_id"];
                $status = $_POST["status"];
                $this->orderModel->updateStatus($order_id, $status);
                header("Location: index.php?controller=order&action=show&id=$order_id");
            }
        }
    }   
?>