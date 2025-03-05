<?php
    class DashboardController {
        public function index() {
            require_once 'models/Product.php';
            $productModel = new Product();
            $orderModel = new Order();
            $customers = new Customer();

            $totalProducts = $productModel->getTotalProducts();
            $totalOrders = $orderModel->getTotalOrders();
            $totalSales = $orderModel->getTotalSales();
            $totalCustomers = $customers->getTotalCustomers();

            $product1Sales = $productModel->getProductSales(4);
            $product2Sales = $productModel->getProductSales(5);
            $product3Sales = $productModel->getProductSales(7);

            $product1Name = $productModel->getProductById(4)['name'];
            $product2Name = $productModel->getProductById(5)['name'];
            $product3Name = $productModel->getProductById(7)['name'];

            $ordersMonth1 = $orderModel->getOrdersByMonth(1);
            $ordersMonth2 = $orderModel->getOrdersByMonth(2);
            $ordersMonth3 = $orderModel->getOrdersByMonth(3);
            $ordersMonth4 = $orderModel->getOrdersByMonth(4);
            $ordersMonth5 = $orderModel->getOrdersByMonth(5);
            $ordersMonth6 = $orderModel->getOrdersByMonth(6);
            $ordersMonth7 = $orderModel->getOrdersByMonth(7);
            $ordersMonth8 = $orderModel->getOrdersByMonth(8);
            $ordersMonth9 = $orderModel->getOrdersByMonth(9);
            $ordersMonth10 = $orderModel->getOrdersByMonth(10);
            $ordersMonth11 = $orderModel->getOrdersByMonth(11);
            $ordersMonth12 = $orderModel->getOrdersByMonth(12);
            
            $orders = $orderModel->getAllOrders();
            require_once 'views/admin/dashboard.php';
        }
    }
?>