<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=conference_registrations_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Ticket Code', 'Full Name', 'Email', 'Phone', 'Occupation', 'Questions For Speakers', 'Checked In Status', 'Check-In Timestamp', 'Registration Date']);

    $csv_res = $conn->query("SELECT * FROM conference_registrations ORDER BY id DESC");
    if ($csv_res) {
        while ($row = $csv_res->fetch_assoc()) {
            fputcsv($output, [
                $row['id'],
                $row['ticket_code'],
                $row['full_name'],
                $row['email'],
                $row['phone'],
                $row['occupation'] ?? '',
                $row['questions'] ?? '',
                $row['checked_in'] ? 'Yes' : 'No',
                $row['checked_in_at'] ?? 'N/A',
                $row['created_at']
            ]);
        }
    }
    fclose($output);
    exit;
}

// Handle Manual Status Actions (Toggle Check-in or Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $reg_id = intval($_POST['reg_id'] ?? 0);
        
        if ($_POST['action'] === 'toggle_checkin' && $reg_id > 0) {
            $curr = $conn->query("SELECT checked_in FROM conference_registrations WHERE id = {$reg_id}")->fetch_assoc();
            $new_status = ($curr['checked_in'] == 1) ? 0 : 1;
            $now = ($new_status == 1) ? date('Y-m-d H:i:s') : null;
            
            $stmt = $conn->prepare("UPDATE conference_registrations SET checked_in = ?, checked_in_at = ? WHERE id = ?");
            $stmt->bind_param("isi", $new_status, $now, $reg_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash_msg'] = "Attendee check-in status updated successfully.";
            header("Location: conference-registrations.php");
            exit;
        }

        if ($_POST['action'] === 'delete' && $reg_id > 0) {
            $stmt = $conn->prepare("DELETE FROM conference_registrations WHERE id = ?");
            $stmt->bind_param("i", $reg_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash_msg'] = "Registration deleted successfully.";
            header("Location: conference-registrations.php");
            exit;
        }
    }
}

// Stats queries
$total_reg = $conn->query("SELECT COUNT(*) as cnt FROM conference_registrations")->fetch_assoc()['cnt'] ?? 0;
$total_checked_in = $conn->query("SELECT COUNT(*) as cnt FROM conference_registrations WHERE checked_in = 1")->fetch_assoc()['cnt'] ?? 0;
$total_pending = $total_reg - $total_checked_in;
$checkin_rate = ($total_reg > 0) ? round(($total_checked_in / $total_reg) * 100, 1) : 0;

// Search & Filter
$search = trim($_GET['search'] ?? '');
$filter = trim($_GET['filter'] ?? '');

$where_clauses = [];
if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $where_clauses[] = "(full_name LIKE '%{$s}%' OR email LIKE '%{$s}%' OR phone LIKE '%{$s}%' OR ticket_code LIKE '%{$s}%' OR occupation LIKE '%{$s}%')";
}
if ($filter === 'checked_in') {
    $where_clauses[] = "checked_in = 1";
} elseif ($filter === 'pending') {
    $where_clauses[] = "checked_in = 0";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT * FROM conference_registrations {$where_sql} ORDER BY id DESC";
$registrations = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Registrations - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'includes/topbar.php'; ?>
        
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="mb-1 fw-bold"><i class="bi bi-ticket-perforated text-warning me-2"></i> Conference Registrations</h2>
                        <p class="text-muted mb-0">Governor Crest Real Estate Conference 2026 Attendees</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="conference-scanner.php" class="btn btn-warning rounded-pill fw-bold">
                            <i class="bi bi-qr-code-scan me-1"></i> Gate Scanner
                        </a>
                        <a href="conference-registrations.php?export=csv" class="btn btn-success rounded-pill fw-bold">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export to CSV
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-white rounded-3 shadow-sm border-start border-4 border-warning">
                            <div class="stat-details">
                                <h3 class="fw-bold mb-0 text-dark"><?php echo $total_reg; ?></h3>
                                <p class="text-muted mb-0 small">Total Registrations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-white rounded-3 shadow-sm border-start border-4 border-success">
                            <div class="stat-details">
                                <h3 class="fw-bold mb-0 text-success"><?php echo $total_checked_in; ?></h3>
                                <p class="text-muted mb-0 small">Checked In At Gate</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-white rounded-3 shadow-sm border-start border-4 border-info">
                            <div class="stat-details">
                                <h3 class="fw-bold mb-0 text-info"><?php echo $checkin_rate; ?>%</h3>
                                <p class="text-muted mb-0 small">Attendance Rate</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 bg-white rounded-3 shadow-sm border-start border-4 border-secondary">
                            <div class="stat-details">
                                <h3 class="fw-bold mb-0 text-secondary"><?php echo $total_pending; ?></h3>
                                <p class="text-muted mb-0 small">Pending Entry Passes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="card border-0 shadow-sm p-3 mb-4 rounded-3">
                    <form method="GET" action="conference-registrations.php" class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search by name, ticket code, email, phone..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="filter" class="form-select">
                                <option value="">All Check-in Statuses</option>
                                <option value="checked_in" <?php echo ($filter === 'checked_in') ? 'selected' : ''; ?>>Checked In Only</option>
                                <option value="pending" <?php echo ($filter === 'pending') ? 'selected' : ''; ?>>Pending Entry Only</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-dark w-100 fw-bold">Filter</button>
                            <a href="conference-registrations.php" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Registrations Table -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Ticket Code</th>
                                    <th>Attendee Name</th>
                                    <th>Contact Info</th>
                                    <th>Occupation</th>
                                    <th>Check-In Status</th>
                                    <th>Reg. Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($registrations && $registrations->num_rows > 0): ?>
                                    <?php while ($row = $registrations->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark border border-warning font-monospace fw-bold px-2 py-1">
                                                    <?php echo htmlspecialchars($row['ticket_code']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-dark d-block"><?php echo htmlspecialchars($row['full_name']); ?></strong>
                                                <?php if (!empty($row['questions'])): ?>
                                                    <small class="text-muted" title="<?php echo htmlspecialchars($row['questions']); ?>">
                                                        <i class="bi bi-chat-left-text text-warning me-1"></i> Has Question
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="d-block text-dark"><i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($row['email']); ?></small>
                                                <small class="d-block text-muted"><i class="bi bi-telephone me-1"></i> <?php echo htmlspecialchars($row['phone']); ?></small>
                                            </td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($row['occupation'] ?: 'N/A'); ?></small></td>
                                            <td>
                                                <?php if ($row['checked_in'] == 1): ?>
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Checked In
                                                    </span>
                                                    <small class="d-block text-muted mt-1" style="font-size: 0.75rem;">
                                                        <?php echo date('d M, h:i A', strtotime($row['checked_in_at'])); ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                        Pending Entry
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="../conference-ticket?code=<?php echo urlencode($row['ticket_code']); ?>" target="_blank" class="btn btn-outline-dark" title="View Digital Ticket">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    <form method="POST" action="conference-registrations.php" class="d-inline">
                                                        <input type="hidden" name="reg_id" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="action" value="toggle_checkin">
                                                        <button type="submit" class="btn btn-outline-<?php echo $row['checked_in'] ? 'warning' : 'success'; ?>" title="Toggle Check-in">
                                                            <i class="bi bi-<?php echo $row['checked_in'] ? 'dash-circle' : 'check-lg'; ?>"></i>
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="conference-registrations.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                                                        <input type="hidden" name="reg_id" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No registrations found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
