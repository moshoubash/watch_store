<?php
namespace Controllers;

use Models\ContactModel;

class ContactController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ContactModel($pdo);
    }

    public function handleSubmission() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, 'Invalid request method');
            return;
        }

        // Retrieve form data
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        // Validate input
        $validationErrors = $this->model->validateInput($name, $email, $message);
        
        if (!empty($validationErrors)) {
            $this->sendResponse(false, implode(', ', $validationErrors));
            return;
        }

        // Attempt to save message
        $saved = $this->model->saveMessage($name, $email, $message);

        if ($saved) {
            $this->sendResponse(true, 'Message sent successfully');
        } else {
            $this->sendResponse(false, 'Failed to save message');
        }
    }

    private function sendResponse($success, $message) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message
        ]);
        exit;
    }
}
?>