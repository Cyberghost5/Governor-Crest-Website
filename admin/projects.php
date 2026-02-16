<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM projects WHERE id=$id");
    $success = 'Project deleted successfully!';
}

// Handle status toggle
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE projects SET status = IF(status='active','inactive','active') WHERE id=$id");
    $success = 'Project status updated!';
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token.';
    } else {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $display_order = $_POST['display_order'] ?? 0;
    $slug = trim($_POST['slug'] ?? '');

    // slugify helper
    function slugify($text) {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('~[^A-Za-z0-9]+~', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        if ($text === '') return 'project';
        return $text;
    }

    // ensure slug
    if (empty($slug)) {
        $slug = slugify($title ?: 'project');
    } else {
        $slug = slugify($slug);
    }

    // ensure uniqueness
    $base = $slug;
    $i = 1;
    while (true) {
        if ($id) {
            $chk = $conn->prepare("SELECT id FROM projects WHERE slug = ? AND id != ? LIMIT 1");
            $chk->bind_param('si', $slug, $id);
        } else {
            $chk = $conn->prepare("SELECT id FROM projects WHERE slug = ? LIMIT 1");
            $chk->bind_param('s', $slug);
        }
        $chk->execute();
        $cres = $chk->get_result();
        if ($cres && $cres->num_rows === 0) break;
        $slug = $base . '-' . $i;
        $i++;
    }

    if ($id) {
        // Update (include slug)
        $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, category=?, image_url=?, display_order=?, slug=? WHERE id=?");
        $stmt->bind_param("ssssisi", $title, $description, $category, $image_url, $display_order, $slug, $id);
    } else {
        // Insert (include slug)
        $stmt = $conn->prepare("INSERT INTO projects (title, description, category, image_url, display_order, slug) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $title, $description, $category, $image_url, $display_order, $slug);
    }

    if ($stmt->execute()) {
        $success = $id ? 'Project updated successfully!' : 'Project added successfully!';
    } else {
        $error = 'Error: ' . $stmt->error;
    }
    }
}

// Get all projects
$projects = $conn->query("SELECT * FROM projects ORDER BY display_order ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Admin Panel</title>
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
                    <h2 class="mb-0">Manage Projects</h2>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#projectModal">
                        <i class="bi bi-plus-circle me-2"></i>Add New Project
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
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($project = $projects->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $project['display_order']; ?></td>
                                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                                        <td><?php echo htmlspecialchars($project['category'] ?? 'N/A'); ?></td>
                                        <td><?php echo substr(htmlspecialchars($project['description']), 0, 50) . '...'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $project['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($project['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick='editProject(<?php echo json_encode($project); ?>)'>
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="?toggle=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-toggle-on"></i>
                                            </a>
                                            <a href="?delete=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this project?')">
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

    <!-- Project Modal -->
    <div class="modal fade" id="projectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="projectId">
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" class="form-control" name="title" id="projectTitle" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control" name="category" id="projectCategory" placeholder="e.g., Real Estate, Agriculture">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" name="display_order" id="projectOrder" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="projectDescription" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="projectImage">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editProject(project) {
            document.getElementById('modalTitle').textContent = 'Edit Project';
            document.getElementById('projectId').value = project.id;
            document.getElementById('projectTitle').value = project.title;
            document.getElementById('projectCategory').value = project.category || '';
            document.getElementById('projectDescription').value = project.description;
            document.getElementById('projectImage').value = project.image_url || '';
            document.getElementById('projectOrder').value = project.display_order;
            new bootstrap.Modal(document.getElementById('projectModal')).show();
        }
    </script>
</body>
</html>
