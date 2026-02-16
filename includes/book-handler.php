<?php
// Handle appointment booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_submit'])) {
    require_once 'config/database.php';
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once __DIR__ . '/csrf.php';

    $full_name = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['emailAddress'] ?? '');
    $phone = trim($_POST['phoneNumber'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $preferred_date = trim($_POST['preferredDate'] ?? '');
    $preferred_time = trim($_POST['preferredTime'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // CSRF check
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $error_message = 'Invalid or missing CSRF token. Please refresh the page and try again.';
        return;
    }

    // Basic validation
    if (empty($full_name) || empty($email)) {
        $error_message = 'Please provide your name and email.';
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please provide a valid email address.';
        return;
    }
    if (!empty($phone) && !preg_match('/^[0-9+\-()\s]{6,20}$/', $phone)) {
        $error_message = 'Please provide a valid phone number.';
        return;
    }
    // Date validation: ensure not in the past
    if (!empty($preferred_date)) {
        $d = DateTime::createFromFormat('Y-m-d', $preferred_date);
        $today = new DateTime('today');
        if (!$d) {
            $error_message = 'Please provide a valid date.';
            return;
        }
        if ($d < $today) {
            $error_message = 'Preferred date cannot be in the past.';
            return;
        }
        // If date is today, ensure time is not in the past
        if ($d == $today && !empty($preferred_time)) {
            $now = new DateTime();
            $t = DateTime::createFromFormat('H:i', $preferred_time);
            if ($t && ($t->format('H:i') < $now->format('H:i'))) {
                $error_message = 'Preferred time cannot be in the past for today.';
                return;
            }
        }
    }

    // Create appointments table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS appointments (
        id INT(11) NOT NULL AUTO_INCREMENT,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) DEFAULT NULL,
        service VARCHAR(150) DEFAULT NULL,
        preferred_date DATE DEFAULT NULL,
        preferred_time VARCHAR(50) DEFAULT NULL,
        message TEXT,
        status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $conn->query($create_table_sql);

    // Check for existing booking with same email AND phone (ignore cancelled)
    $duplicate = false;
    if (!empty($email) && !empty($phone)) {
        $chk = $conn->prepare("SELECT id FROM appointments WHERE email = ? AND phone = ? AND status IN ('pending','confirmed') LIMIT 1");
        $chk->bind_param('ss', $email, $phone);
        $chk->execute();
        $cres = $chk->get_result();
        if ($cres && $cres->num_rows > 0) {
            $duplicate = true;
            $error_message = 'An appointment already exists for that email and phone number.';
        }
    } elseif (!empty($email) && empty($phone)) {
        // If phone blank, check by email only
        $chk = $conn->prepare("SELECT id FROM appointments WHERE email = ? AND status IN ('pending','confirmed') LIMIT 1");
        $chk->bind_param('s', $email);
        $chk->execute();
        $cres = $chk->get_result();
        if ($cres && $cres->num_rows > 0) {
            $duplicate = true;
            $error_message = 'An appointment already exists for that email address.';
        }
    }

    if (!$duplicate) {
        $stmt = $conn->prepare("INSERT INTO appointments (full_name, email, phone, service, preferred_date, preferred_time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $phone, $service, $preferred_date, $preferred_time, $message);

        if ($stmt->execute()) {
            $success_message = true;
        }
    }
}
?>
