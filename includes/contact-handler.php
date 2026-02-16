<?php
// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_submit'])) {
    require_once 'config/database.php';
    
    $full_name = $_POST['fullName'] ?? '';
    $email = $_POST['emailAddress'] ?? '';
    $phone = $_POST['phoneNumber'] ?? '';
    $message = $_POST['message'] ?? '';
    
    $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $message);
    
    if ($stmt->execute()) {
        $success_message = true;
    }
}
?>
