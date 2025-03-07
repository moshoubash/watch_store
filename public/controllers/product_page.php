<?php
include_once '../config/connectt.php';
include_once '../models/product_page.php';

$watchModel = new Watch($pdo);
$watch = $watchModel->getWatchById(2);

// include '../views/product_page.php';
?>