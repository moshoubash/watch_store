<?php
include './models/product.php';

class ProductController {
    private $productModel;

    public function __construct($db) {
        $this->productModel = new Product($db);
    }

    public function showProductsByCategory($category_id) {
        $products = $this->productModel->getProductsByCategory($category_id);
        include './views/product_view.php'; 
    }
}
