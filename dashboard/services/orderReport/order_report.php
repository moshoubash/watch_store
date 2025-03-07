<?php
require('fpdf.php');
require '../../config/database.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Add a logo if you have one
        // $this->Image('logo.png',10,6,30);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'Order History Report', 0, 1, 'C');
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

$database = new Database();
$pdo = $database->getConnection();

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255);

// Calculate the total width of the table
$tableWidth = 30 + 50 + 30 + 40 + 30;
$pdf->SetX(($pdf->GetPageWidth() - $tableWidth) / 2);

$pdf->Cell(30, 10, 'Order ID', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Customer ID', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Status', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Order Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total Amount', 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as $order) {
    $pdf->SetX(($pdf->GetPageWidth() - $tableWidth) / 2);
    $pdf->Cell(30, 10, $order['id'], 1);
    $pdf->Cell(50, 10, $order['user_id'], 1);
    $pdf->Cell(30, 10, ucfirst($order['status']), 1);
    $pdf->Cell(40, 10, $order['created_at'], 1);
    $pdf->Cell(30, 10, '$' . number_format($order['total_price'], 2), 1);
    $pdf->Ln();
}

$pdf->Output();
?>