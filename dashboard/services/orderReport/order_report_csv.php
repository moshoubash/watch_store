<?php
    require('fpdf.php');
    require '../../config/database.php';

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=order_history.csv");
    
    $database = new Database();
    $pdo = $database->getConnection();

    $output = fopen("php://output", "w");
    fputcsv($output, ["Order ID", "Customer ID", "Total Amount ", "Status", "Order Date"]);

    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>
