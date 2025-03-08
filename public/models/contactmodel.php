<?php
namespace Models;

class ContactModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function validateInput($name, $email, $message) {
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($message)) {
            $errors[] = 'Message is required';
        }

        return $errors;
    }

    public function saveMessage($name, $email, $message) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO contact_messages (name, email, message, create_at) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([
                htmlspecialchars($name),
                htmlspecialchars($email),
                htmlspecialchars($message)
            ]);
        } catch (\PDOException $e) {
            // Log error or handle as needed
            return false;
        }
    }
}
?>