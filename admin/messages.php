<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

// Get all messages
$messages_query = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");

// Handle status update
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $conn->query("UPDATE contact_messages SET status='read' WHERE id=$id");
    header('Location: messages.php');
    exit;
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    header('Location: messages.php');
    exit;
}

$unread_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status='unread'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Panel</title>
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
                <h2 class="mb-4">Contact Messages</h2>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($msg = $messages_query->fetch_assoc()): ?>
                                    <tr class="<?php echo $msg['status'] == 'unread' ? 'table-warning' : ''; ?>">
                                        <td><?php echo $msg['id']; ?></td>
                                        <td><?php echo htmlspecialchars($msg['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                        <td><?php echo htmlspecialchars($msg['phone'] ?? 'N/A'); ?></td>
                                        <td><?php echo substr(htmlspecialchars($msg['message']), 0, 50) . '...'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $msg['status'] == 'unread' ? 'danger' : 'success'; ?>">
                                                <?php echo ucfirst($msg['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $msg['id']; ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <?php if ($msg['status'] == 'unread'): ?>
                                            <a href="?mark_read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-success">
                                                <i class="bi bi-check"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal<?php echo $msg['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Message from <?php echo htmlspecialchars($msg['full_name']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
                                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($msg['phone'] ?? 'N/A'); ?></p>
                                                    <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?></p>
                                                    <hr>
                                                    <p><strong>Message:</strong></p>
                                                    <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
