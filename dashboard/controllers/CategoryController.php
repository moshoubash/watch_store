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
                $data = [
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "image" => $_POST["image"],
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
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "image" => $_POST["image"],
                ];
                $this->categoryModel->updateCategory($data);
                header("Location: index.php?controller=category&action=index");
            }
        }

        public function delete() {
            $id = $_GET["id"];
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