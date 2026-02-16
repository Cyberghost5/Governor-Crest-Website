<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

$success = '';

// Handle form submission for about content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $who_we_are = $_POST['who_we_are'] ?? '';
    $mission = $_POST['mission'] ?? '';
    $vision = $_POST['vision'] ?? '';
    
    // Update Who We Are
    $stmt = $conn->prepare("UPDATE about_content SET content = ? WHERE section = 'who_we_are'");
    $stmt->bind_param("s", $who_we_are);
    $stmt->execute();
    
    // Update Mission
    $stmt = $conn->prepare("UPDATE about_content SET content = ? WHERE section = 'mission'");
    $stmt->bind_param("s", $mission);
    $stmt->execute();
    
    // Update Vision
    $stmt = $conn->prepare("UPDATE about_content SET content = ? WHERE section = 'vision'");
    $stmt->bind_param("s", $vision);
    $stmt->execute();
    
    $success = 'About content updated successfully!';
}

// Get current content
$content_query = $conn->query("SELECT * FROM about_content");
$content = [];
while ($row = $content_query->fetch_assoc()) {
    $content[$row['section']] = $row['content'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Content - Admin Panel</title>
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
                <h2 class="mb-4">About Content</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label"><strong>Who We Are</strong></label>
                                <textarea class="form-control" name="who_we_are" rows="5" required><?php echo htmlspecialchars($content['who_we_are'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label"><strong>Our Mission</strong></label>
                                <textarea class="form-control" name="mission" rows="5" required><?php echo htmlspecialchars($content['mission'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label"><strong>Our Vision</strong></label>
                                <textarea class="form-control" name="vision" rows="5" required><?php echo htmlspecialchars($content['vision'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>Update Content
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
