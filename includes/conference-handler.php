<?php
/**
 * Conference Registration Handler - Governor Crest Real Estate Conference 2026
 */
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/qrcode.php';
require_once __DIR__ . '/mail.php';

// Helper function to return JSON or Redirect
function respond($success, $message, $ticket_code = '', $redirect_url = '') {
    $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'ticket_code' => $ticket_code,
            'redirect' => $redirect_url
        ]);
        exit;
    } else {
        if ($success && !empty($redirect_url)) {
            header("Location: " . $redirect_url);
            exit;
        } else {
            $_SESSION['conf_msg'] = $message;
            $_SESSION['conf_msg_type'] = $success ? 'success' : 'danger';
            header("Location: ../conference");
            exit;
        }
    }
}

// Process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    if ($action === 'register') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');
        $questions = trim($_POST['questions'] ?? '');

        // Validation
        if (empty($full_name) || empty($email) || empty($phone)) {
            respond(false, "Please fill in all required fields (Full Name, Email, and Phone Number).");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            respond(false, "Please enter a valid email address.");
        }

        // Ensure database tables exist
        $conn->query("CREATE TABLE IF NOT EXISTS `conference_registrations` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `ticket_code` VARCHAR(50) NOT NULL,
          `full_name` VARCHAR(255) NOT NULL,
          `email` VARCHAR(255) NOT NULL,
          `phone` VARCHAR(50) NOT NULL,
          `occupation` VARCHAR(255) DEFAULT NULL,
          `questions` TEXT DEFAULT NULL,
          `status` ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
          `checked_in` TINYINT(1) DEFAULT 0,
          `checked_in_at` DATETIME DEFAULT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `ticket_code` (`ticket_code`),
          KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Check if user is already registered with this email
        $check_stmt = $conn->prepare("SELECT ticket_code FROM conference_registrations WHERE email = ? LIMIT 1");
        if ($check_stmt) {
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_res = $check_stmt->get_result();
            if ($check_res && $check_res->num_rows > 0) {
                $existing = $check_res->fetch_assoc();
                $existing_code = $existing['ticket_code'];
                $check_stmt->close();
                
                // Redirect directly to their existing ticket
                respond(true, "You are already registered! Here is your official conference ticket.", $existing_code, "../conference-ticket?code=" . urlencode($existing_code));
            }
            $check_stmt->close();
        }

        // Generate Unique Ticket Code (e.g. GCR-CONF-8F92A1)
        do {
            $random_str = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $ticket_code = "GCR-CONF-" . $random_str;
            
            $stmt_check = $conn->prepare("SELECT id FROM conference_registrations WHERE ticket_code = ?");
            $stmt_check->bind_param("s", $ticket_code);
            $stmt_check->execute();
            $code_exists = $stmt_check->get_result()->num_rows > 0;
            $stmt_check->close();
        } while ($code_exists);

        // Insert Registration Record
        $insert_stmt = $conn->prepare("INSERT INTO conference_registrations (ticket_code, full_name, email, phone, occupation, questions) VALUES (?, ?, ?, ?, ?, ?)");
        if ($insert_stmt) {
            $insert_stmt->bind_param("ssssss", $ticket_code, $full_name, $email, $phone, $occupation, $questions);
            $inserted = $insert_stmt->execute();
            $insert_stmt->close();

            if ($inserted) {
                // Build Ticket Link & QR Code
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $dir = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/\\');
                $ticket_url = $protocol . $host . $dir . "/conference-ticket?code=" . urlencode($ticket_code);
                $qr_image_url = generate_qr_data_uri($ticket_code);

                // Send Ticket Confirmation Email
                $email_html = render_email_template('conference_ticket', [
                    'full_name' => $full_name,
                    'ticket_code' => $ticket_code,
                    'email' => $email,
                    'phone' => $phone,
                    'ticket_url' => $ticket_url,
                    'qr_image_url' => $qr_image_url,
                    'company' => $site_settings['company_name'] ?? 'Governor Crest Limited'
                ]);

                $subject = "Your Ticket: Governor Crest Real Estate Conference 2026";
                @send_mail_helper($email, $subject, $email_html, 'info@governorcrestlimited.com', 'Governor Crest Limited', $site_settings);

                respond(true, "Registration successful! Your QR ticket has been sent to your email.", $ticket_code, "../conference-ticket?code=" . urlencode($ticket_code));
            } else {
                respond(false, "Database error. Could not complete registration. Please try again.");
            }
        } else {
            respond(false, "Server query error. Please try again.");
        }
    }
}

// Default fallback
header("Location: ../conference");
exit;
