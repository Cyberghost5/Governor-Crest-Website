<?php
/**
 * Conference Registration Handler - Governor Crest Real Estate Conference 2026
 */
ob_start();
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/qrcode.php';
require_once __DIR__ . '/mail.php';

// Ensure site_settings is available
if (!isset($site_settings) || !is_array($site_settings)) {
    $site_settings = [
        'company_name' => 'Governor Crest Limited',
        'email' => 'info@governorcrestlimited.com',
        'phone' => '+234 XXX XXX XXXX',
        'address' => 'Bauchi State, Nigeria'
    ];
}

// Helper function to return JSON or Redirect
function respond($success, $message, $ticket_code = '', $redirect_url = '') {
    $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
               (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1');
    
    if ($is_ajax) {
        if (ob_get_length()) {
            ob_clean();
        }
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
            header("Location: ../conference.php");
            exit;
        }
    }
}

// Process POST & AJAX requests
$action = trim($_REQUEST['action'] ?? '');
if ($action === 'register') {
    $full_name = trim($_REQUEST['full_name'] ?? '');
    $email = trim($_REQUEST['email'] ?? '');
    $phone = trim($_REQUEST['phone'] ?? '');
    $occupation = trim($_REQUEST['occupation'] ?? '');
    $questions = trim($_REQUEST['questions'] ?? '');

    // Validation
        if (empty($full_name) || empty($email) || empty($phone)) {
            respond(false, "Please fill in all required fields (Full Name, Email, and Phone Number).");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            respond(false, "Please enter a valid email address.");
        }

        // Ensure database tables exist
        $create_tbl = $conn->query("CREATE TABLE IF NOT EXISTS `conference_registrations` (
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

        if (!$create_tbl) {
            respond(false, "Database table initialization error: " . $conn->error);
        }

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
                respond(true, "You are already registered! Here is your official conference ticket.", $existing_code, "conference-ticket.php?code=" . urlencode($existing_code));
            }
            $check_stmt->close();
        }

        // Generate Unique Ticket Code (e.g. GCR-CONF-8F92A1)
        $attempts = 0;
        $ticket_code = "";
        do {
            $attempts++;
            $random_str = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $ticket_code = "GCR-CONF-" . $random_str;
            
            $stmt_check = $conn->prepare("SELECT id FROM conference_registrations WHERE ticket_code = ?");
            if ($stmt_check) {
                $stmt_check->bind_param("s", $ticket_code);
                $stmt_check->execute();
                $code_exists = $stmt_check->get_result()->num_rows > 0;
                $stmt_check->close();
            } else {
                $code_exists = false;
            }
        } while ($code_exists && $attempts < 10);

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
                $ticket_url = $protocol . $host . $dir . "/conference-ticket.php?code=" . urlencode($ticket_code);
                $qr_image_url = generate_qr_data_uri($ticket_code);

                // Send Ticket Confirmation Email (suppress mail errors to ensure registration completes)
                try {
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
                } catch (Exception $e) {
                    // Ignore email dispatch errors so registration succeeds
                }

                respond(true, "Registration successful! Your QR ticket has been generated.", $ticket_code, "conference-ticket.php?code=" . urlencode($ticket_code));
            } else {
                respond(false, "Database insert failed: " . $conn->error);
            }
        } else {
            respond(false, "Server query error: " . $conn->error);
        }
}


// Default fallback
header("Location: ../conference.php");
exit;
