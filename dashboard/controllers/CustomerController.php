<?php
    require_once "models/Customer.php";

    class CustomerController {
        private $customerModel;
        private $orderModel;

        public function __construct() {
            $this->customerModel = new Customer();
            $this->orderModel = new Order();
        }

        public function index() {
            $customers = $this->customerModel->getAllCustomers();
            include "views/customer/index.php";
        }

        public function create() {
            include "views/customer/create.php";
        }

        public function store() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "country" => $_POST["country"],
                    "city" => $_POST["city"],
                    "street" => $_POST["street"],
                    "state" => $_POST["state"],
                    "postal_code" => $_POST["postal_code"],
                    "phone_number" => $_POST["phone_number"],
                    "role" => $_POST["role"],
                    "password" => $_POST["password"],
                    "confirm_password" => $_POST["confirm_password"],
                ];
                $this->customerModel->createCustomer($data);
                header("Location: index.php?controller=customer&action=index");
            }
        }

        public function edit() {
            $id = $_GET["id"];
            $customer = $this->customerModel->getCustomerById($id);
            include "views/customer/edit.php";
        }

        // Update existing customer
        public function update() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "country" => $_POST["country"],
                    "city" => $_POST["city"],
                    "street" => $_POST["street"],
                    "state" => $_POST["state"],
                    "postal_code" => $_POST["postal_code"],
                    "phone_number" => $_POST["phone_number"],
                    "role" => $_POST["role"],
                    "password" => $_POST["password"],
                ];
                $this->customerModel->updateCustomer($data);
                header("Location: index.php?controller=customer&action=index");
            }
        }

        // Delete customer
        public function delete() {
            $id = $_GET["id"];

            $targetCustomer = $this->customerModel->getCustomerById($id);
            $customers = $this->customerModel->getOrdersByCustomerId($id);
            if (count($customers) > 0) {
                $error = "The customer cannot be deleted because he has orders.";
                header("Location: index.php?controller=customer&action=index&error=$error");
                die();
            }

            $this->customerModel->deleteCustomer($id);
            header("Location: index.php?controller=customer&action=index");
        }

        // Search for customers
        public function search() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $keyword = $_POST["keyword"];
                $customers = $this->customerModel->searchCustomers($keyword);
                include "views/customer/index.php";
            }
        }

        // Show customer details
        public function show() {
            $id = $_GET["id"];
            $customer = $this->customerModel->getCustomerById($id);
            $orders = $this->orderModel->getOrdersByCustomerId($id);
            include "views/customer/show.php";
        }
    }   
?>