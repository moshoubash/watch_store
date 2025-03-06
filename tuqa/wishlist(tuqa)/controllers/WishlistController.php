<?php
include './models/Wishlist.php';  

class WishlistController {

    private $wishlistModel;

    public function __construct($conn) {
        $this->wishlistModel = new Wishlist($conn);
    }

    public function addProductToWishlist($user_id, $product_id) {
        if ($this->wishlistModel->addToWishlist($user_id, $product_id)) {
            echo "Product added to your wishlist!";
        } else {
            echo "Failed to add product to wishlist.";
        }
    }

    public function showWishlist($user_id) {
        $wishlistItems = $this->wishlistModel->getWishlist($user_id);
        return $wishlistItems;  
    }
}
?>
