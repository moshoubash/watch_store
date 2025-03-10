<?php
    require_once "models/Discount.php";

    class DiscountController {
        private $discountModel;
        private $productModel;

        public function __construct() {
            $this->discountModel = new Discount();
            $this->productModel = new Product();
        }

        // Display all discounts
        public function index() {
            $discounts = $this->discountModel->getAllDiscounts();
            include "views/discount/index.php";
        }

        // Show form to create discount
        public function create() {
            $products = $this->productModel->getAllProducts();
            include "views/discount/create.php";
        }

        // Store new discount in database
        public function store() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "name" => $_POST["name"],
                    "discount_percentage" => $_POST["discount_percentage"],
                    "start_date" => $_POST["start_date"],
                    "end_date" => $_POST["end_date"],
                    "limit" => $_POST["limit"],
                    "discount_amount" => $_POST["discount_amount"],
                    "limit_uses" => $_POST["limit_uses"],
                    "coupon_code" => $_POST["coupon_code"]
                ];
                $this->discountModel->createDiscount($data);
                header("Location: index.php?controller=discount&action=index");
            }
        }

        // Show form to edit discount
        public function edit() {
            $id = $_GET["id"];
            $discount = $this->discountModel->getDiscountById($id);
            $products = $this->productModel->getAllProducts();
            include "views/discount/edit.php";
        }

        // Update existing discount
        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "discount_percentage" => $_POST["discount_percentage"],
                    "start_date" => $_POST["start_date"],
                    "end_date" => $_POST["end_date"],
                    "limit" => $_POST["limit"],
                    "discount_amount" => $_POST["discount_amount"],
                    "limit_uses" => $_POST["limit_uses"],
                    "coupon_code" => $_POST["coupon_code"]
                ];
                $this->discountModel->updateDiscount($data);
                header("Location: index.php?controller=discount&action=index");
            }
        }

        // Delete discount
        public function delete() {
            $id = $_GET["id"];
            $this->discountModel->deleteDiscount($id);
            header("Location: index.php?controller=discount&action=index");
        }

        // Search for discounts
        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $discounts = $this->discountModel->searchDiscounts($keyword);
                include "views/discount/index.php";
            }
        }
    }   
?>