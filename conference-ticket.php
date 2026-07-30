<?php
$current_page = 'conference-ticket';
$page_title = 'Conference Ticket - Governor Crest Limited';

require_once 'config/database.php';
require_once 'includes/qrcode.php';

$ticket_code = trim($_GET['code'] ?? '');
$ticket = null;

if (!empty($ticket_code)) {
    $stmt = $conn->prepare("SELECT * FROM conference_registrations WHERE ticket_code = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $ticket_code);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $ticket = $res->fetch_assoc();
        }
        $stmt->close();
    }
}

// Generate QR Code URL
$qr_url = "";
if ($ticket) {
    // Generate verification payload URL or code for scanner
    $verification_data = $ticket['ticket_code'];
    $qr_url = generate_qr_data_uri($verification_data);
}

include 'includes/head.php';
include 'includes/header.php';
?>

<style>
@media print {
    .navbar, footer, .no-print {
        display: none !important;
    }
    body {
        background: #ffffff !important;
        padding: 0 !important;
    }
    .ticket-container {
        box-shadow: none !important;
        border: 2px solid #2c2c2c !important;
        margin: 0 auto !important;
    }
}
</style>

<section class="py-5 bg-light style="min-height: 80vh;">
    <div class="container py-5 mt-4">
        <?php if ($ticket): ?>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <!-- Ticket Actions Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                        <a href="conference" class="btn btn-outline-dark rounded-pill px-4 p-2">
                            <i class="bi bi-arrow-left me-2"></i> Back to Conference
                        </a>
                        <button onclick="window.print()" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm p-2">
                            <i class="bi bi-printer-fill me-2"></i> Print / Download Ticket
                        </button>
                    </div>

                    <!-- Digital Ticket Card -->
                    <div class="ticket-container bg-white rounded-4 shadow-lg overflow-hidden border border-2 border-warning">
                        <!-- Top Header -->
                        <div class="bg-dark text-white p-4 d-flex justify-content-between align-items-center border-bottom border-4 border-warning">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-0 text-warning fw-bold">Governor Crest Limited</h5>
                                    <small class="text-light opacity-75">Real Estate Conference 2026</small>
                                </div>
                            </div>
                            <span class="badge <?php echo $ticket['checked_in'] ? 'bg-success' : 'bg-warning text-dark'; ?> px-3 py-2 rounded-pill fs-6 fw-bold">
                                <?php echo $ticket['checked_in'] ? '<i class="bi bi-check-circle-fill me-1"></i> CHECKED IN' : '<i class="bi bi-ticket-fill me-1"></i> CONFIRMED PASS'; ?>
                            </span>
                        </div>

                        <!-- Ticket Body -->
                        <div class="p-4 p-md-5">
                            <div class="row align-items-center text-center text-md-start">
                                <div class="col-md-7 mb-4 mb-md-0">
                                    <span class="text-warning text-uppercase fw-bold tracking-wider small">Attendee Pass</span>
                                    <h3 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($ticket['full_name']); ?></h3>
                                    <p class="text-muted mb-3 fs-6">
                                        <i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($ticket['email']); ?><br>
                                        <i class="bi bi-telephone me-1"></i> <?php echo htmlspecialchars($ticket['phone']); ?>
                                        <?php if (!empty($ticket['occupation'])): ?>
                                            <br><i class="bi bi-briefcase me-1"></i> <?php echo htmlspecialchars($ticket['occupation']); ?>
                                        <?php endif; ?>
                                    </p>

                                    <div class="bg-light p-3 rounded-3 border mb-3">
                                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Ticket Reference Number</small>
                                        <div class="fs-4 fw-bold text-dark font-monospace tracking-wide">
                                            <?php echo htmlspecialchars($ticket['ticket_code']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 text-center">
                                    <div class="p-3 bg-light rounded-4 border border-warning d-inline-block shadow-sm">
                                        <img src="<?php echo htmlspecialchars($qr_url); ?>" alt="Ticket QR Code" class="img-fluid rounded-3" style="max-width: 180px;">
                                        <small class="d-block text-muted mt-2 fw-semibold" style="font-size: 0.75rem;">
                                            Scan at Event Entrance
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 text-secondary">

                            <!-- Event Logistics -->
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-3 text-warning me-3"><i class="bi bi-calendar-check-fill"></i></div>
                                        <div>
                                            <small class="text-muted text-uppercase fw-bold d-block">Date & Time</small>
                                            <span class="fw-bold text-dark">Saturday, August 15, 2026</span><br>
                                            <small class="text-muted">08:30 AM (Gate Opens)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-3 text-warning me-3"><i class="bi bi-geo-alt-fill"></i></div>
                                        <div>
                                            <small class="text-muted text-uppercase fw-bold d-block">Venue Location</small>
                                            <span class="fw-bold text-dark">E4 Resorts</span><br>
                                            <small class="text-muted">Off Bauchi Club Road, Bauchi State</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Footer Notice -->
                        <div class="bg-light p-3 text-center border-top border-secondary-subtle">
                            <small class="text-muted">
                                <i class="bi bi-info-circle-fill text-warning me-1"></i>
                                Please present this QR code on your mobile device or bring a printed copy to gain admission. Entry is 100% Free.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="card border-0 shadow-lg p-5 rounded-4">
                        <div class="fs-1 text-danger mb-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <h3 class="fw-bold text-dark mb-2">Ticket Not Found</h3>
                        <p class="text-muted mb-4">
                            The ticket reference number provided is invalid or has expired. Please check your email or complete a new registration.
                        </p>
                        <a href="conference" class="btn btn-warning px-4 py-2 fw-bold rounded-pill">
                            Go to Conference Registration
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
