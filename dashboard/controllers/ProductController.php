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

        // Store new product
        public function store() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $fileName = uniqid() . basename($_FILES["image"]["name"]);
                $uploadDir = "assets/productImages/";
                $targetFilePath = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        
                $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        
                if (!in_array($fileType, $allowedTypes)) {
                    header("Location: index.php?controller=product&action=create&error=invalidFileType");
                }
        
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
        
                $data = [
                    "name" => $_POST["name"],
                    "brand" => $_POST["brand"],
                    "category_id" => $_POST["category_id"],
                    "color" => $_POST["color"],
                    "description" => $_POST["description"],
                    "price" => $_POST["price"],
                    "stock" => $_POST["stock"],
                    "image" => $fileName
                ];
        
                $this->productModel->createProduct($data);
        
                header("Location: index.php?controller=product&action=index");
                exit();
            }
        }
        

        // Show form to edit product
        public function edit() {
            $id = $_GET["id"];
            $categories = $this->categoryModel->getAllCategories();
            $product = $this->productModel->getProductById($id);
            include "views/product/edit.php";
        }

        // Update existing product
        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                //handle old image
                $id = $_POST["id"];
                $product = $this->productModel->getProductById($id);
                $oldImage = $product["image"];

                if (!empty($_FILES["image"]["name"])) {
                    if (file_exists("assets/productImages/" . $oldImage)) {
                        unlink("assets/productImages/" . $oldImage);
                    }
                    $fileName = uniqid() . basename($_FILES["image"]["name"]);
                    $uploadDir = "assets/productImages/";
                    $targetFilePath = $uploadDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            
                    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
            
                    if (!in_array($fileType, $allowedTypes)) {
                        header("Location: index.php?controller=product&action=edit&id=" . $id . "&error=invalidFileType");
                        exit();
                    }
            
                    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
                } else {
                    $fileName = $oldImage;
                }
        
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "brand" => $_POST["brand"],
                    "category_id" => $_POST["category_id"],
                    "color" => $_POST["color"],
                    "description" => $_POST["description"],
                    "image" => $fileName, 
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