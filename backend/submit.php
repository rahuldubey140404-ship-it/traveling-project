<?php
// ============================================================
// submit_enquiry.php — AJAX Booking Form Handler
// ============================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Sanitize
$name    = htmlspecialchars(trim($data['name']        ?? ''));
$email   = filter_var(trim($data['email']             ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(trim($data['phone']       ?? ''));
$dest    = htmlspecialchars(trim($data['destination'] ?? ''));
$date    = $data['travel_date']                       ?? '';
$persons = htmlspecialchars(trim($data['num_persons'] ?? ''));
$msg     = htmlspecialchars(trim($data['message']     ?? ''));

// Validate required fields
if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$phone || !$dest || !$date || !$persons) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    exit;
}

// Validate date not in the past
if (strtotime($date) < strtotime('today')) {
    echo json_encode(['success' => false, 'message' => 'Travel date cannot be in the past.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO enquiries (full_name, email, phone, destination, travel_date, num_persons, message)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $phone, $dest, $date, $persons, $msg]);

    // Optional: Send confirmation email
    // mail($email, 'Enquiry Received – Avipro Travels', "Dear $name, we received your enquiry and will contact you soon.");

    echo json_encode(['success' => true, 'message' => 'Enquiry submitted successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error saving enquiry. Please try again.']);
}