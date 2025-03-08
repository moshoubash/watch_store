<?php 
    require_once "models/Admin.php";

    class AuthController{
        private $userModel;

        public function __construct() {
            $this->adminModel = new Admin();
        }

        // public function logout() {
        //     session_start(); 
        //     session_unset(); 
        //     session_destroy(); 
        //     header("Location: /watch_store/public/views/signup_login.php");
        //     exit();
        // }
    }
?>