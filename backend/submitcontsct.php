<?php
// ============================================================
// submit_contact.php — AJAX Contact Form Handler
// ============================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name    = htmlspecialchars(trim($data['name']    ?? ''));
$email   = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(trim($data['subject'] ?? ''));
$msg     = htmlspecialchars(trim($data['message'] ?? ''));

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Name and a valid email are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (full_name, email, subject, message)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $subject, $msg]);
    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error sending message. Please try again.']);
}