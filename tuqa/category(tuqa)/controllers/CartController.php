<?php
include './models/cart.php';

class CartController {
    private $cartModel;

    public function __construct($db) {
        $this->cartModel = new Cart($db);
    }

    public function viewCart($user_id) {
        $cart = $this->cartModel->getUserCart($user_id);
        include './views/'; 
    }

    public function addToCart($user_id, $product_id, $quantity) {
        $this->cartModel->addToCart($user_id, $product_id, $quantity);
        header("Location: /cart.php?user_id=$user_id");
    }
}
?>