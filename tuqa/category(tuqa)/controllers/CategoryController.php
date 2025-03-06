<?php
include './models/category.php';

class CategoryController {
    private $categoryModel;

    public function __construct($db) {
        $this->categoryModel = new Category($db);
    }

    public function showCategories() {
        $categories = $this->categoryModel->getAllCategories();
        include './views/category_view.php'; 
    }
}
