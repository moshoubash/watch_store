<?php 
    require('fpdf.php');
    require_once '../../config/database.php';

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(190, 10, 'Order Details Report', 0, 1, 'C');
            $this->Ln(10);
        }

        // Page footer
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    if (!isset($_GET['order_id'])) {
        die('Order ID is required');
    }

    $order_id = $_GET['order_id'];

    $database = new Database();
    if (!$database) {
        die('Database connection failed');
    }
    $pdo = $database->getConnection();

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 220, 255);

    // Customer Information
    $pdf->Cell(190, 10, 'Customer Information', 1, 1, 'C', true);
    $pdf->Ln(5);

    $sql = "SELECT id, name, email, phone_number FROM users WHERE id = (SELECT user_id FROM orders WHERE id = :order_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        $pdf->Cell(30, 10, 'Customer ID', 1);
        $pdf->Cell(80, 10, 'Name', 1);
        $pdf->Cell(80, 10, 'Email', 1);
        $pdf->Ln();
        $pdf->Cell(30, 10, $customer['id'], 1);
        $pdf->Cell(80, 10, $customer['name'], 1);
        $pdf->Cell(80, 10, $customer['email'], 1);
        $pdf->Ln(15);
    } else {
        $pdf->Cell(0, 10, 'No customer information found', 1, 0, 'C');
        $pdf->Ln(15);
    }

    // Order Information
    $pdf->Cell(190, 10, 'Order Information', 1, 1, 'C', true);
    $pdf->Ln(5);

    $sql = "SELECT id, user_id, status, created_at, total_price 
            FROM orders 
            WHERE id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $pdf->Cell(30, 10, 'Order ID', 1);
        $pdf->Cell(50, 10, 'Customer ID', 1);
        $pdf->Cell(30, 10, 'Status', 1);
        $pdf->Cell(40, 10, 'Order Date', 1);
        $pdf->Cell(40, 10, 'Total Amount', 1);
        $pdf->Ln();
        $pdf->Cell(30, 10, $order['id'], 1);
        $pdf->Cell(50, 10, $order['user_id'], 1);
        $pdf->Cell(30, 10, ucfirst($order['status']), 1);
        $pdf->Cell(40, 10, $order['created_at'], 1);
        $pdf->Cell(40, 10, '$' . number_format($order['total_price'], 2), 1);
        $pdf->Ln(15);
    } else {
        $pdf->Cell(0, 10, 'No order found with the given ID', 1, 0, 'C');
        $pdf->Ln(15);
    }

    // Order Items
    $pdf->Cell(190, 10, 'Order Items', 1, 1, 'C', true);
    $pdf->Ln(5);

    $sql = "SELECT product_id, quantity, price 
            FROM order_items 
            WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($items) {
        $pdf->Cell(30, 10, 'Product ID', 1);
        $pdf->Cell(80, 10, 'Quantity', 1);
        $pdf->Cell(80, 10, 'Price', 1);
        $pdf->Ln();
        foreach ($items as $item) {
            $pdf->Cell(30, 10, $item['product_id'], 1);
            $pdf->Cell(80, 10, $item['quantity'], 1);
            $pdf->Cell(80, 10, '$' . number_format($item['price'], 2), 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No items found for this order', 1, 0, 'C');
    }

    $pdf->Output();
?>
