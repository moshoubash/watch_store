<?php 
    require_once "models/Admin.php";

    class AdminController{
        private $adminModel;
        private $customerModel;

        public function __construct() {
            $this->adminModel = new Admin();
            $this->customerModel = new Customer();
        }

        public function index() {
            $admins = $this->adminModel->getAllAdmins();
            include "views/admin/index.php";
        }

        public function create() {
            include "views/admin/create.php";
        }

        public function store() {
            $confirmPassword = $_POST["confirm-password"];
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "role" => $_POST["role"],
                    "phone_number" => $_POST["phone_number"],
                    "country" => $_POST["country"],
                    "city" => $_POST["city"],
                    "street" => $_POST["street"],
                    "state" => $_POST["state"],
                    "postal_code" => $_POST["postal_code"],
                    "password" => $_POST["password"],
                    "confirm-password" => $confirmPassword,
                ];

                $this->adminModel->createAdmin($data);
                header("Location: index.php?controller=admin&action=index");
            }
        }

        public function edit() {
            $id = $_GET["id"];
            $admin = $this->adminModel->getAdminById($id);
            include "views/admin/edit.php";
        }

        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "role" => $_POST["role"],
                    "phone_number" => $_POST["phone_number"],
                    "country" => $_POST["country"],
                    "city" => $_POST["city"],
                    "street" => $_POST["street"],
                    "state" => $_POST["state"],
                    "postal_code" => $_POST["postal_code"],
                    "password" => $_POST["password"],
                ];
                $this->adminModel->updateAdmin($data);
                header("Location: index.php?controller=admin&action=index");
            }
        }

        public function delete() {
            $id = $_GET["id"];

            $admins = $this->customerModel->getOrdersByCustomerId($id);
            
            if (count($admins) > 0) {
                $error = "The Admin cannot be deleted because he has sensitive actions.";
                header("Location: index.php?controller=admin&action=index&error=$error");
                die();
            }

            $this->adminModel->deleteAdmin($id);
            header("Location: index.php?controller=admin&action=index");
        }

        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $admins = $this->adminModel->searchAdmins($keyword);
                include "views/admin/index.php";
            }
        }

        public function changeRole() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                "id" => $_POST["id"],
                "role" => $_POST["newRole"],
            ];
            $this->adminModel->changeRole($data);
            header("Location: index.php?controller=admin&action=index");
            }
        }

        public function settings() {
            $id = $_GET["id"];
            $admin = $this->adminModel->getAdminById($id);
            include "views/admin/settings.php";
        }

        public function updateSettings() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "phone_number" => $_POST["phone_number"],
                    "country" => $_POST["country"],
                    "city" => $_POST["city"],
                    "street" => $_POST["street"],
                    "state" => $_POST["state"],
                    "postal_code" => $_POST["postal_code"],
                    "password" => $_POST["password"],
                ];
                $this->adminModel->updateAdmin($data);
                header("Location: index.php?controller=admin&action=index");
            }
        }
    }
?>