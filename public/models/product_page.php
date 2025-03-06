<?php
class Watch
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getWatchById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM watch_men WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>