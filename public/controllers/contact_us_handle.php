<?php
require_once '../config/con.php';
require_once '../models/contactmodel.php';
require_once './contact_us_controller.php';

use Controllers\ContactController;

// Create controller and handle submission
$controller = new ContactController($pdo);
$controller->handleSubmission();
?>