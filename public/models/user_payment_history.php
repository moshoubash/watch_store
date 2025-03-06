<?php
class Payment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPaymentsByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY payment_date DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>