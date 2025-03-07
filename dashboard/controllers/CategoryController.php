<?php
    require_once "models/Category.php";

    class CategoryController {
        private $categoryModel;

        public function __construct() {
            $this->categoryModel = new Category();
        }

        public function index() {
            $categories = $this->categoryModel->getAllCategories();
            include "views/category/index.php";
        }

        public function create() {
            include "views/category/create.php";
        }

        public function store() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $fileName = uniqid() . basename($_FILES["image"]["name"]);
                $uploadDir = "assets/categoryImages/";
                $targetFilePath = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        
                $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        
                if (!in_array($fileType, $allowedTypes)) {
                    header("Location: index.php?controller=category&action=create&error=invalidFileType");
                    exit();
                }
        
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

                $data = [
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "image" => $fileName,
                ];
                $this->categoryModel->createCategory($data);
                header("Location: index.php?controller=category&action=index");
            }
        }

        public function edit() {
            $id = $_GET["id"];
            $category = $this->categoryModel->getCategoryById($id);
            include "views/category/edit.php";
        }

        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //handle old image
                $id = $_POST["id"];
                $category = $this->categoryModel->getCategoryById($id);
                $oldImage = $category["image"];

                if (!empty($_FILES["image"]["name"])) {
                    if (file_exists("assets/categoryImages/" . $oldImage)) {
                        unlink("assets/categoryImages/" . $oldImage);
                    }
                    $fileName = uniqid() . basename($_FILES["image"]["name"]);
                    $uploadDir = "assets/categoryImages/";
                    $targetFilePath = $uploadDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            
                    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
            
                    if (!in_array($fileType, $allowedTypes)) {
                        header("Location: index.php?controller=category&action=edit&id=" . $id . "&error=invalidFileType");
                        exit();
                    }
            
                    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
                } 
                else {
                    $fileName = $oldImage;
                }
        
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
                
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "image" => $fileName,
                ];
                $this->categoryModel->updateCategory($data);
                header("Location: index.php?controller=category&action=index");
            }
        }

        public function delete() {
            $id = $_GET["id"];

            $targetCategory = $this->categoryModel->getCategoryById($id);
            $products = $this->categoryModel->getProductsByCategoryId($id);
            if (count($products) > 0) {
                $error = "Can't delete category because it has products";
                header("Location: index.php?controller=category&action=index&error=$error");
                die();
            }
            
            $this->categoryModel->deleteCategory($id);
            header("Location: index.php?controller=category&action=index");
        }

        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $categories = $this->categoryModel->searchCategories($keyword);
                include "views/category/index.php";
            }
        }
    }   
?>