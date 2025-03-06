<?php 
    require_once "models/Admin.php";

    class AuthController{
        private $userModel;

        public function __construct() {
            $this->adminModel = new Admin();
        }

        public function logout() {
            session_start();
            session_destroy();
            header("Location: index.php?controller=auth&action=login");
        }
    }
?>