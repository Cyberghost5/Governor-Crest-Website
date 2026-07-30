<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS `conference_guests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `designation` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `image_url` VARCHAR(500) NOT NULL,
  `display_order` INT(11) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Process CRUD POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $id = intval($_POST['guest_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

        if (!empty($name) && !empty($designation) && !empty($image_url)) {
            if ($action === 'add') {
                $stmt = $conn->prepare("INSERT INTO conference_guests (name, designation, company, bio, image_url, display_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssis", $name, $designation, $company, $bio, $image_url, $display_order, $status);
                $stmt->execute();
                $stmt->close();
                $_SESSION['flash_msg'] = "Guest speaker added successfully!";
            } else if ($action === 'edit' && $id > 0) {
                $stmt = $conn->prepare("UPDATE conference_guests SET name = ?, designation = ?, company = ?, bio = ?, image_url = ?, display_order = ?, status = ? WHERE id = ?");
                $stmt->bind_param("sssssisi", $name, $designation, $company, $bio, $image_url, $display_order, $status, $id);
                $stmt->execute();
                $stmt->close();
                $_SESSION['flash_msg'] = "Guest speaker profile updated!";
            }
        }
        header("Location: conference-guests.php");
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_POST['guest_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM conference_guests WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash_msg'] = "Guest speaker removed.";
        }
        header("Location: conference-guests.php");
        exit;
    }
}

// Fetch all guests
$guests = $conn->query("SELECT * FROM conference_guests ORDER BY display_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Special Guests - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin-style.css">
    <style>
        .guest-avatar-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ffc107;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'includes/topbar.php'; ?>
        
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1 fw-bold"><i class="bi bi-person-badge text-warning me-2"></i> Special Guests & Speakers</h2>
                        <p class="text-muted mb-0">Manage guest speaker profiles displayed on the conference landing page</p>
                    </div>
                    <button class="btn btn-warning rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#guestModal" onclick="openAddModal()">
                        <i class="bi bi-plus-lg me-1"></i> Add Special Guest
                    </button>
                </div>

                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Guests Grid / Table -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order</th>
                                    <th>Photo</th>
                                    <th>Speaker Name</th>
                                    <th>Designation / Role</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($guests && $guests->num_rows > 0): ?>
                                    <?php while ($row = $guests->fetch_assoc()): ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark border"><?php echo $row['display_order']; ?></span></td>
                                            <td>
                                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Speaker Photo" class="guest-avatar-preview">
                                            </td>
                                            <td>
                                                <strong class="text-dark d-block"><?php echo htmlspecialchars($row['name']); ?></strong>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                                    <?php echo htmlspecialchars($row['bio']); ?>
                                                </small>
                                            </td>
                                            <td><small class="fw-semibold text-warning"><?php echo htmlspecialchars($row['designation']); ?></small></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($row['company'] ?: 'Governor Crest'); ?></small></td>
                                            <td>
                                                <span class="badge <?php echo $row['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?> rounded-pill">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-dark" onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                    <form method="POST" action="conference-guests.php" class="d-inline" onsubmit="return confirm('Remove this guest speaker?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="guest_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No guest speakers added yet.
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

    <!-- Add/Edit Guest Modal -->
    <div class="modal fade" id="guestModal" tabindex="-1" aria-labelledby="guestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title text-warning fw-bold" id="guestModalLabel">Add Special Guest</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="guestForm" method="POST" action="conference-guests.php">
                    <input type="hidden" name="action" id="modalAction" value="add">
                    <input type="hidden" name="guest_id" id="modalGuestId" value="0">
                    
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="guest_name" class="form-label fw-semibold">Speaker Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guest_name" name="name" required placeholder="e.g. Arc. Ibrahim Bello">
                            </div>

                            <div class="col-md-6">
                                <label for="guest_designation" class="form-label fw-semibold">Designation / Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guest_designation" name="designation" required placeholder="e.g. Keynote Speaker & Real Estate Strategist">
                            </div>

                            <div class="col-md-6">
                                <label for="guest_company" class="form-label fw-semibold">Company / Organization</label>
                                <input type="text" class="form-control" id="guest_company" name="company" placeholder="e.g. Governor Crest Limited">
                            </div>

                            <div class="col-md-6">
                                <label for="guest_image_url" class="form-label fw-semibold">Photo Image URL <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="guest_image_url" name="image_url" required placeholder="https://images.unsplash.com/... or relative path">
                            </div>

                            <div class="col-md-6">
                                <label for="guest_display_order" class="form-label fw-semibold">Display Order</label>
                                <input type="number" class="form-control" id="guest_display_order" name="display_order" value="1" min="1">
                            </div>

                            <div class="col-md-6">
                                <label for="guest_status" class="form-label fw-semibold">Visibility Status</label>
                                <select class="form-select" id="guest_status" name="status">
                                    <option value="active">Active (Visible on Landing Page)</option>
                                    <option value="inactive">Inactive (Hidden)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="guest_bio" class="form-label fw-semibold">Speaker Bio / Overview</label>
                                <textarea class="form-control" id="guest_bio" name="bio" rows="3" placeholder="Brief background of the speaker's real estate expertise..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-3 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Save Speaker Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
function openAddModal() {
    document.getElementById('modalAction').value = 'add';
    document.getElementById('modalGuestId').value = '0';
    document.getElementById('guestModalLabel').innerText = 'Add Special Guest Speaker';
    document.getElementById('guestForm').reset();
}

function openEditModal(guest) {
    document.getElementById('modalAction').value = 'edit';
    document.getElementById('modalGuestId').value = guest.id;
    document.getElementById('guestModalLabel').innerText = 'Edit Special Guest Speaker';

    document.getElementById('guest_name').value = guest.name;
    document.getElementById('guest_designation').value = guest.designation;
    document.getElementById('guest_company').value = guest.company || '';
    document.getElementById('guest_image_url').value = guest.image_url;
    document.getElementById('guest_display_order').value = guest.display_order;
    document.getElementById('guest_status').value = guest.status;
    document.getElementById('guest_bio').value = guest.bio || '';

    let modal = new bootstrap.Modal(document.getElementById('guestModal'));
    modal.show();
}
</script>
</body>
</html>
