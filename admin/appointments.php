<?php
session_start();
require_once '../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';

// Require admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$message = '';

// Load site settings (for address, email, company name)
$settings = [];
$settings_q = $conn->query("SELECT * FROM site_settings");
if ($settings_q) {
    while ($s = $settings_q->fetch_assoc()) {
        $settings[$s['setting_key']] = $s['setting_value'];
    }
}

// Use reusable mail helper (PHPMailer if available)
require_once __DIR__ . '/../includes/mail.php';

// Handle approve/disapprove actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    // CSRF validation for admin action
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $message = 'Invalid CSRF token.';
    } else {
        // Fetch appointment details for email
        
        
    
    }
    // If CSRF valid, fetch appointment details
    if ($message === '') {
        $appt = null;
        $gstmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? LIMIT 1");
        $gstmt->bind_param('i', $id);
        $gstmt->execute();
        $gres = $gstmt->get_result();
        if ($gres && $gres->num_rows === 1) {
            $appt = $gres->fetch_assoc();
        }
    }

    if ($action === 'approve') {
        $new_status = 'confirmed';
    } elseif ($action === 'disapprove') {
        $new_status = 'cancelled';
    } else {
        $new_status = null;
    }

    if ($new_status) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $id);
        if ($stmt->execute()) {
            $message = 'Appointment updated.';

            // Send notification email if we have appointment and recipient
            if ($appt && !empty($appt['email'])) {
                $to = $appt['email'];
                $company = htmlspecialchars($settings['company_name'] ?? 'Governor Crest Limited');
                $from_email = htmlspecialchars($settings['email'] ?? 'info@governorcrest.com');
                $address = htmlspecialchars($settings['address'] ?? '');

                $appt_date = htmlspecialchars($appt['preferred_date'] ?: $appt['created_at']);
                $appt_time = htmlspecialchars($appt['preferred_time'] ?: '');

                if ($new_status === 'confirmed') {
                    $subject = "$company - Your appointment has been confirmed";
                    $status_text = 'confirmed';
                } else {
                    $subject = "$company - Your appointment has been cancelled";
                    $status_text = 'cancelled';
                }

                // Render email from template
                $templateVars = [
                    'company' => $company,
                    'full_name' => $appt['full_name'] ?? '',
                    'status_text' => $status_text,
                    'appt_date' => $appt_date,
                    'appt_time' => $appt_time,
                    'address' => $address,
                    'phone' => $settings['phone'] ?? '',
                    'notes' => $appt['message'] ?? ''
                ];
                $body = render_email_template('appointment_status', $templateVars);

                // Use helper which prefers PHPMailer (via composer) and falls back to SMTP/mail()
                $sendResult = send_mail_helper($to, $subject, $body, $from_email, $company, $settings);
                $sent = is_array($sendResult) ? ($sendResult['ok'] ?? false) : (bool)$sendResult;

                // Ensure email_logs table exists
                $conn->query("CREATE TABLE IF NOT EXISTS email_logs (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    appointment_id INT(11) DEFAULT NULL,
                    to_email VARCHAR(255) NOT NULL,
                    subject VARCHAR(255) NOT NULL,
                    body MEDIUMTEXT,
                    method VARCHAR(50) DEFAULT NULL,
                    success TINYINT(1) DEFAULT 0,
                    error_message TEXT,
                    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                // Insert log
                $ilog = $conn->prepare("INSERT INTO email_logs (appointment_id, to_email, subject, body, method, success, error_message) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $method = is_array($sendResult) ? ($sendResult['method'] ?? 'unknown') : 'unknown';
                $err = is_array($sendResult) ? ($sendResult['error'] ?? '') : '';
                $okInt = $sent ? 1 : 0;
                $aid = $appt['id'] ?? null;
                $ilog->bind_param('issssis', $aid, $to, $subject, $body, $method, $okInt, $err);
                $ilog->execute();
            }
        } else {
            $message = 'Unable to update appointment.';
        }
    }
}

// Handle resend action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_id'])) {
    $resend_id = intval($_POST['resend_id']);
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $message = 'Invalid CSRF token for resend.';
    } else {
        $gstmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? LIMIT 1");
        $gstmt->bind_param('i', $resend_id);
        $gstmt->execute();
        $gres = $gstmt->get_result();
        if ($gres && $gres->num_rows === 1) {
            $appt = $gres->fetch_assoc();
            if (!empty($appt['email'])) {
                $to = $appt['email'];
                $company = htmlspecialchars($settings['company_name'] ?? 'Governor Crest Limited');
                $from_email = htmlspecialchars($settings['email'] ?? 'info@governorcrest.com');
                $address = htmlspecialchars($settings['address'] ?? '');
                $appt_date = htmlspecialchars($appt['preferred_date'] ?: $appt['created_at']);
                $appt_time = htmlspecialchars($appt['preferred_time'] ?: '');
                $status_text = $appt['status'];
                $subject = "$company - Appointment notification";
                $templateVars = [
                    'company' => $company,
                    'full_name' => $appt['full_name'] ?? '',
                    'status_text' => $status_text,
                    'appt_date' => $appt_date,
                    'appt_time' => $appt_time,
                    'address' => $address,
                    'phone' => $settings['phone'] ?? '',
                    'notes' => $appt['message'] ?? ''
                ];
                $body = render_email_template('appointment_status', $templateVars);

                $sendResult = send_mail_helper($to, $subject, $body, $from_email, $company, $settings);
                $sent = is_array($sendResult) ? ($sendResult['ok'] ?? false) : (bool)$sendResult;

                // ensure table exists and log
                $conn->query("CREATE TABLE IF NOT EXISTS email_logs (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    appointment_id INT(11) DEFAULT NULL,
                    to_email VARCHAR(255) NOT NULL,
                    subject VARCHAR(255) NOT NULL,
                    body MEDIUMTEXT,
                    method VARCHAR(50) DEFAULT NULL,
                    success TINYINT(1) DEFAULT 0,
                    error_message TEXT,
                    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                $ilog = $conn->prepare("INSERT INTO email_logs (appointment_id, to_email, subject, body, method, success, error_message) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $method = is_array($sendResult) ? ($sendResult['method'] ?? 'unknown') : 'unknown';
                $err = is_array($sendResult) ? ($sendResult['error'] ?? '') : '';
                $okInt = $sent ? 1 : 0;
                $aid = $appt['id'];
                $ilog->bind_param('issssis', $aid, $to, $subject, $body, $method, $okInt, $err);
                $ilog->execute();

                $message = $sent ? 'Resend attempt logged and email sent.' : 'Resend attempt logged; email may not have been delivered.';
            } else {
                $message = 'Appointment has no email to resend to.';
            }
        } else {
            $message = 'Appointment not found for resend.';
        }
    }
}

// Admin filters: status, search, pagination, CSV export
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$where = "1=1";
$allowed = ['pending','confirmed','cancelled'];
if ($statusFilter && in_array($statusFilter, $allowed)) {
    $sf = $conn->real_escape_string($statusFilter);
    $where .= " AND status='" . $sf . "'";
}
if ($q !== '') {
    $esc = $conn->real_escape_string($q);
    $where .= " AND (full_name LIKE '%" . $esc . "%' OR email LIKE '%" . $esc . "%' OR service LIKE '%" . $esc . "%')";
}

// CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $csvRes = $conn->query("SELECT * FROM appointments WHERE $where ORDER BY created_at DESC");
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="appointments.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id','full_name','email','phone','service','preferred_date','preferred_time','status','created_at']);
    if ($csvRes) {
        while ($r = $csvRes->fetch_assoc()) {
            fputcsv($out, [$r['id'],$r['full_name'],$r['email'],$r['phone'],$r['service'],$r['preferred_date'],$r['preferred_time'],$r['status'],$r['created_at']]);
        }
    }
    fclose($out);
    exit;
}

// Count total
$cntRes = $conn->query("SELECT COUNT(*) as cnt FROM appointments WHERE $where");
$total = 0;
if ($cntRes) {
    $rowc = $cntRes->fetch_assoc();
    $total = intval($rowc['cnt']);
}
$totalPages = max(1, ceil($total / $perPage));

// Fetch paginated
$result = $conn->query("SELECT * FROM appointments WHERE $where ORDER BY created_at DESC LIMIT $offset, $perPage");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="admin-layout d-flex">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main-content flex-grow-1 p-4">
            <?php include 'includes/topbar.php'; ?>

            <div class="mt-4">
                <h4>Appointments</h4>
                <form method="GET" class="row g-2 align-items-center mt-3">
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            <option value="pending" <?php echo ($statusFilter==='pending')?'selected':''; ?>>Pending</option>
                            <option value="confirmed" <?php echo ($statusFilter==='confirmed')?'selected':''; ?>>Confirmed</option>
                            <option value="cancelled" <?php echo ($statusFilter==='cancelled')?'selected':''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="search" name="q" class="form-control" placeholder="Search name, email or service" value="<?php echo htmlspecialchars($q); ?>">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-secondary">Filter</button>
                    </div>
                    <div class="col-auto">
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['export'=>'csv'])); ?>" class="btn btn-outline-primary">Export CSV</a>
                    </div>
                </form>
                <?php if ($message): ?>
                    <div class="alert alert-success mt-3"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Preferred Date</th>
                                        <th>Preferred Time</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($row['service']); ?></td>
                                            <td><?php echo htmlspecialchars($row['preferred_date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['preferred_time']); ?></td>
                                            <td style="max-width:200px;white-space:pre-wrap;"><?php echo htmlspecialchars($row['message']); ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'pending'): ?>
                                                    <span class="badge bg-secondary">Pending</span>
                                                <?php elseif ($row['status'] === 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php elseif ($row['status'] === 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['status']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                            <td>
                                                <form method="POST" style="display:inline-block;">
                                                    <?php echo csrf_input(); ?>
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <?php if ($row['status'] !== 'confirmed'): ?>
                                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success mb-1">Approve</button>
                                                    <?php endif; ?>
                                                    <?php if ($row['status'] !== 'cancelled'): ?>
                                                        <button type="submit" name="action" value="disapprove" class="btn btn-sm btn-danger mb-1">Disapprove</button>
                                                    <?php endif; ?>
                                                    <button type="submit" name="resend_id" value="<?php echo $row['id']; ?>" class="btn btn-sm btn-primary mb-1">Resend Email</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">No appointments found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="appointments pagination">
                                <ul class="pagination mt-3">
                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?php echo $p==$page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page'=>$p])); ?>"><?php echo $p; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
