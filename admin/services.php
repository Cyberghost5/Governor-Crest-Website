<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM services WHERE id=$id");
    $success = 'Service deleted successfully!';
}

// Handle status toggle
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE services SET status = IF(status='active','inactive','active') WHERE id=$id");
    $success = 'Service status updated!';
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $icon = $_POST['icon'] ?? '';
    $description = $_POST['description'] ?? '';
    $features = $_POST['features'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $display_order = $_POST['display_order'] ?? 0;
    
    if ($id) {
        // Update
        $stmt = $conn->prepare("UPDATE services SET name=?, icon=?, description=?, features=?, image_url=?, display_order=? WHERE id=?");
        $stmt->bind_param("sssssii", $name, $icon, $description, $features, $image_url, $display_order, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO services (name, icon, description, features, image_url, display_order) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $icon, $description, $features, $image_url, $display_order);
    }
    
    if ($stmt->execute()) {
        $success = $id ? 'Service updated successfully!' : 'Service added successfully!';
    } else {
        $error = 'Error: ' . $stmt->error;
    }
}

// Get all services
$services = $conn->query("SELECT * FROM services ORDER BY display_order ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Admin Panel</title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Manage Services</h2>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#serviceModal">
                        <i class="bi bi-plus-circle me-2"></i>Add New Service
                    </button>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($service = $services->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $service['display_order']; ?></td>
                                        <td><i class="<?php echo $service['icon']; ?> fs-4"></i></td>
                                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                                        <td><?php echo substr(htmlspecialchars($service['description']), 0, 50) . '...'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $service['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($service['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick='editService(<?php echo json_encode($service); ?>)'>
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="?toggle=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-toggle-on"></i>
                                            </a>
                                            <a href="?delete=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this service?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add New Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="serviceId">
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" class="form-control" name="name" id="serviceName" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Icon Class (Bootstrap Icons)</label>
                                <input type="text" class="form-control" name="icon" id="serviceIcon" placeholder="bi-building" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" name="display_order" id="serviceOrder" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="serviceDescription" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" id="serviceFeatures" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="serviceImage">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editService(service) {
            document.getElementById('modalTitle').textContent = 'Edit Service';
            document.getElementById('serviceId').value = service.id;
            document.getElementById('serviceName').value = service.name;
            document.getElementById('serviceIcon').value = service.icon;
            document.getElementById('serviceDescription').value = service.description;
            document.getElementById('serviceFeatures').value = service.features;
            document.getElementById('serviceImage').value = service.image_url || '';
            document.getElementById('serviceOrder').value = service.display_order;
            new bootstrap.Modal(document.getElementById('serviceModal')).show();
        }
    </script>
</body>
</html>
