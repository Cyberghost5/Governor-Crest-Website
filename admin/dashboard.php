<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

// Get statistics
$total_services = $conn->query("SELECT COUNT(*) as count FROM services WHERE status='active'")->fetch_assoc()['count'];
$total_projects = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status='active'")->fetch_assoc()['count'];
$total_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
$unread_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status='unread'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
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
                <h2 class="mb-4">Dashboard</h2>
                
                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-primary">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div class="stat-details">
                                <h3><?php echo $total_services; ?></h3>
                                <p>Active Services</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-success">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="stat-details">
                                <h3><?php echo $total_projects; ?></h3>
                                <p>Projects</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-warning">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="stat-details">
                                <h3><?php echo $total_messages; ?></h3>
                                <p>Total Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-danger">
                                <i class="bi bi-bell"></i>
                            </div>
                            <div class="stat-details">
                                <h3><?php echo $unread_messages; ?></h3>
                                <p>Unread Messages</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Contact Messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
                                    while ($msg = $messages->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($msg['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                        <td><?php echo substr(htmlspecialchars($msg['message']), 0, 50) . '...'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $msg['status'] == 'unread' ? 'danger' : 'success'; ?>">
                                                <?php echo ucfirst($msg['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="messages.php" class="btn btn-sm btn-primary">View All Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
