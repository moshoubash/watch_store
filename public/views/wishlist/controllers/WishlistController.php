<?php
include './models/Wishlist.php';  

class WishlistController {

    private $wishlistModel;

    public function __construct($conn) {
        $this->wishlistModel = new Wishlist($conn);
    }
    
    public function addProductToWishlist($user_id, $product_id) {
        if ($this->wishlistModel->addToWishlist($user_id, $product_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function showWishlist($user_id) {
        $wishlistItems = $this->wishlistModel->getWishlist($user_id);
        return $wishlistItems;  
    }
    
    public function removeFromWishlist($user_id, $product_id) {
        if ($this->wishlistModel->removeFromWishlist($user_id, $product_id)) {
            return true;
        } else {
            return false;
        }
    }
}
?>