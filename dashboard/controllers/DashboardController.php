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

            $topProducts = $productModel->getTopThreeSoldProducts();

            $product1Sales = $topProducts[0]['total_quantity'];
            $product2Sales = $topProducts[1]['total_quantity'];
            $product3Sales = $topProducts[2]['total_quantity'];

            $product1Name = $topProducts[0]['name'];
            $product2Name = $topProducts[1]['name'];
            $product3Name = $topProducts[2]['name'];

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