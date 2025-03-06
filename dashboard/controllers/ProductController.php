<?php
    require_once "models/Product.php";

    class ProductController {
        private $productModel;
        private $categoryModel;

        public function __construct() {
            $this->productModel = new Product();
            $this->categoryModel = new Category();
        }

        // Display all products
        public function index() {
            $products = $this->productModel->getAllProducts();
            include "views/product/index.php";
        }

        // Show form to create product
        public function create() {
            $categories = $this->categoryModel->getAllCategories();
            include "views/product/create.php";
        }

        // Store new product in database
        public function store() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "name" => $_POST["name"],
                    "brand" => $_POST["brand"],
                    "category_id" => $_POST["category_id"],
                    "color" => $_POST["color"],
                    "description" => $_POST["description"],
                    "image" => $_POST["image"], 
                    "price" => $_POST["price"],
                    "stock" => $_POST["stock"],
                ];
                $this->productModel->createProduct($data);
                header("Location: index.php?controller=product&action=index");
            }
        }

        // Show form to edit product
        public function edit() {
            $id = $_GET["id"];
            $product = $this->productModel->getProductById($id);
            include "views/product/edit.php";
        }

        // Update existing product
        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "brand" => $_POST["brand"],
                    "category_id" => $_POST["category_id"],
                    "color" => $_POST["color"],
                    "description" => $_POST["description"],
                    "image" => $_POST["image"], 
                    "price" => $_POST["price"],
                    "stock" => $_POST["stock"],
                ];
                $this->productModel->updateProduct($data);
                header("Location: index.php?controller=product&action=index");
            }
        }

        // Delete product
        public function delete() {
            $id = $_GET["id"];
            $this->productModel->deleteProduct($id);
            header("Location: index.php?controller=product&action=index");
        }

        // Search for products
        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $products = $this->productModel->searchProducts($keyword);
                include "views/product/index.php";
            }
        }
    }   
?>