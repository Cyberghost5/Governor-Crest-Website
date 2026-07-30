<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/database.php';

// Handle AJAX Verification Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_ticket') {
    header('Content-Type: application/json');
    $raw_code = trim($_POST['ticket_code'] ?? '');
    
    if (empty($raw_code)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid ticket code.']);
        exit;
    }

    // Clean code string if full URL was scanned
    $ticket_code = $raw_code;
    if (strpos($raw_code, 'code=') !== false) {
        $parts = parse_url($raw_code);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query_params);
            if (isset($query_params['code'])) {
                $ticket_code = $query_params['code'];
            }
        }
    }

    $stmt = $conn->prepare("SELECT * FROM conference_registrations WHERE ticket_code = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $ticket_code);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res && $res->num_rows > 0) {
            $ticket = $res->fetch_assoc();
            $stmt->close();

            if ($ticket['checked_in'] == 1) {
                // Already checked in
                echo json_encode([
                    'status' => 'already_checked_in',
                    'message' => 'ATTENTION: Ticket has ALREADY been checked in!',
                    'ticket' => [
                        'full_name' => $ticket['full_name'],
                        'ticket_code' => $ticket['ticket_code'],
                        'email' => $ticket['email'],
                        'phone' => $ticket['phone'],
                        'occupation' => $ticket['occupation'],
                        'checked_in_at' => date('d M Y, h:i A', strtotime($ticket['checked_in_at']))
                    ]
                ]);
                exit;
            } else {
                // Perform Check-in
                $now = date('Y-m-d H:i:s');
                $update_stmt = $conn->prepare("UPDATE conference_registrations SET checked_in = 1, checked_in_at = ? WHERE id = ?");
                $update_stmt->bind_param("si", $now, $ticket['id']);
                $update_stmt->execute();
                $update_stmt->close();

                echo json_encode([
                    'status' => 'success',
                    'message' => 'VALID TICKET! Entry Granted.',
                    'ticket' => [
                        'full_name' => $ticket['full_name'],
                        'ticket_code' => $ticket['ticket_code'],
                        'email' => $ticket['email'],
                        'phone' => $ticket['phone'],
                        'occupation' => $ticket['occupation'],
                        'checked_in_at' => date('d M Y, h:i A', strtotime($now))
                    ]
                ]);
                exit;
            }
        } else {
            $stmt->close();
            echo json_encode(['status' => 'invalid', 'message' => 'INVALID TICKET: Ticket code not found in database.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Ticket Scanner - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin-style.css">
    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .scanner-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 2px solid #ffc107;
        }
        #reader {
            border-radius: 12px;
            overflow: hidden;
            border: 2px dashed #ffc107;
            background: #000;
        }
        #reader video {
            object-fit: cover !important;
            border-radius: 10px;
        }
        .result-box {
            display: none;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
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
                        <h2 class="mb-1 fw-bold"><i class="bi bi-qr-code-scan text-warning me-2"></i> Gate Ticket Scanner</h2>
                        <p class="text-muted mb-0">Governor Crest Real Estate Conference 2026 • Entrance Verification</p>
                    </div>
                    <a href="conference-registrations.php" class="btn btn-outline-dark rounded-pill">
                        <i class="bi bi-list-ul me-1"></i> View Registrations
                    </a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="scanner-card p-4 p-md-5">
                            <!-- Camera Controls -->
                            <div class="text-center mb-4">
                                <button id="btnStartScan" class="btn btn-warning btn-lg fw-bold rounded-pill px-4 shadow-sm mb-2">
                                    <i class="bi bi-camera-fill me-2"></i> Start Camera Scanner
                                </button>
                                <button id="btnStopScan" class="btn btn-outline-secondary btn-lg fw-bold rounded-pill px-4 mb-2 d-none">
                                    <i class="bi bi-camera-video-off me-2"></i> Stop Camera
                                </button>
                                <p class="text-muted small mb-0">Point your smartphone camera at the attendee's QR code ticket</p>
                            </div>

                            <!-- Camera View Area -->
                            <div id="reader" style="width: 100%; min-height: 280px;" class="mx-auto mb-4"></div>

                            <!-- Manual Ticket Code Input Fallback -->
                            <div class="card bg-light border-0 p-3 rounded-3 mb-3">
                                <label for="manualCode" class="form-label fw-bold text-dark mb-1">
                                    <i class="bi bi-keyboard me-1"></i> Manual Ticket Code Lookup
                                </label>
                                <div class="input-group">
                                    <input type="text" id="manualCode" class="form-control form-control-lg font-monospace text-uppercase" placeholder="e.g. GCR-CONF-8F92A1">
                                    <button id="btnManualVerify" class="btn btn-dark btn-lg px-4 fw-bold">
                                        Verify Ticket
                                    </button>
                                </div>
                            </div>

                            <!-- Live Scan Results Display -->
                            <div id="resultBox" class="result-box text-center">
                                <div id="resultIcon" class="fs-1 mb-2"></div>
                                <h3 id="resultTitle" class="fw-bold mb-2"></h3>
                                <p id="resultMsg" class="fs-6 mb-3"></p>
                                
                                <div id="attendeeDetails" class="bg-white p-3 rounded-3 border text-start d-none">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">Attendee Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> <span id="attName"></span></p>
                                    <p class="mb-1"><strong>Ticket Code:</strong> <span id="attCode" class="font-monospace fw-bold text-warning"></span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="attEmail"></span></p>
                                    <p class="mb-1"><strong>Phone:</strong> <span id="attPhone"></span></p>
                                    <p class="mb-1"><strong>Occupation:</strong> <span id="attOcc"></span></p>
                                    <p class="mb-0"><strong>Check-In Time:</strong> <span id="attTime" class="badge bg-dark"></span></p>
                                </div>

                                <button id="btnScanNext" class="btn btn-dark w-100 py-3 fw-bold rounded-3 mt-3">
                                    <i class="bi bi-arrow-repeat me-2"></i> Scan Next Ticket
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
let html5QrcodeScanner = null;

document.addEventListener('DOMContentLoaded', function() {
    const btnStart = document.getElementById('btnStartScan');
    const btnStop = document.getElementById('btnStopScan');
    const btnManual = document.getElementById('btnManualVerify');
    const btnNext = document.getElementById('btnScanNext');
    const manualCodeInput = document.getElementById('manualCode');

    btnStart.addEventListener('click', startScanner);
    btnStop.addEventListener('click', stopScanner);
    btnManual.addEventListener('click', function() {
        const code = manualCodeInput.value.trim();
        if (code) {
            verifyTicketCode(code);
        }
    });

    manualCodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnManual.click();
        }
    });

    btnNext.addEventListener('click', function() {
        document.getElementById('resultBox').style.display = 'none';
        manualCodeInput.value = '';
    });
});

function startScanner() {
    document.getElementById('btnStartScan').classList.add('d-none');
    document.getElementById('btnStopScan').classList.remove('d-none');

    html5QrcodeScanner = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    html5QrcodeScanner.start(
        { facingMode: "environment" }, 
        config, 
        onScanSuccess
    ).catch(err => {
        console.error("Camera access error:", err);
        alert("Camera error: Could not access camera. Please make sure camera permissions are enabled.");
        stopScanner();
    });
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
        }).catch(err => console.error(err));
    }
    document.getElementById('btnStartScan').classList.remove('d-none');
    document.getElementById('btnStopScan').classList.add('d-none');
}

function onScanSuccess(decodedText, decodedResult) {
    // Beep sound feedback option
    try {
        let ctx = new (window.AudioContext || window.webkitAudioContext)();
        let osc = ctx.createOscillator();
        osc.connect(ctx.destination);
        osc.frequency.value = 800;
        osc.start();
        osc.stop(ctx.currentTime + 0.15);
    } catch(e){}

    verifyTicketCode(decodedText);
}

function verifyTicketCode(code) {
    const resultBox = document.getElementById('resultBox');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');
    const attendeeDetails = document.getElementById('attendeeDetails');

    resultBox.style.display = 'block';
    resultBox.className = 'result-box text-center bg-light border p-4';
    resultIcon.innerHTML = '<span class="spinner-border spinner-border-lg text-warning" role="status"></span>';
    resultTitle.innerText = 'Verifying Ticket...';
    resultMsg.innerText = 'Checking ticket reference against database';
    attendeeDetails.classList.add('d-none');

    const formData = new FormData();
    formData.append('action', 'verify_ticket');
    formData.append('ticket_code', code);

    fetch('conference-scanner.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            resultBox.className = 'result-box text-center bg-success bg-opacity-10 border border-success p-4';
            resultIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
            resultTitle.className = 'fw-bold text-success mb-2';
            resultTitle.innerText = 'VALID TICKET! ENTRY GRANTED';
            resultMsg.innerText = data.message;

            document.getElementById('attName').innerText = data.ticket.full_name;
            document.getElementById('attCode').innerText = data.ticket.ticket_code;
            document.getElementById('attEmail').innerText = data.ticket.email;
            document.getElementById('attPhone').innerText = data.ticket.phone;
            document.getElementById('attOcc').innerText = data.ticket.occupation || 'N/A';
            document.getElementById('attTime').innerText = data.ticket.checked_in_at;
            attendeeDetails.classList.remove('d-none');
        } else if (data.status === 'already_checked_in') {
            resultBox.className = 'result-box text-center bg-warning bg-opacity-10 border border-warning p-4';
            resultIcon.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-warning"></i>';
            resultTitle.className = 'fw-bold text-dark mb-2';
            resultTitle.innerText = 'ALREADY CHECKED IN';
            resultMsg.innerText = data.message;

            document.getElementById('attName').innerText = data.ticket.full_name;
            document.getElementById('attCode').innerText = data.ticket.ticket_code;
            document.getElementById('attEmail').innerText = data.ticket.email;
            document.getElementById('attPhone').innerText = data.ticket.phone;
            document.getElementById('attOcc').innerText = data.ticket.occupation || 'N/A';
            document.getElementById('attTime').innerText = data.ticket.checked_in_at;
            attendeeDetails.classList.remove('d-none');
        } else {
            resultBox.className = 'result-box text-center bg-danger bg-opacity-10 border border-danger p-4';
            resultIcon.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
            resultTitle.className = 'fw-bold text-danger mb-2';
            resultTitle.innerText = 'INVALID TICKET';
            resultMsg.innerText = data.message || 'Ticket code not found.';
            attendeeDetails.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        resultBox.className = 'result-box text-center bg-danger bg-opacity-10 border border-danger p-4';
        resultIcon.innerHTML = '<i class="bi bi-exclamation-octagon-fill text-danger"></i>';
        resultTitle.innerText = 'Server Error';
        resultMsg.innerText = 'Failed to verify ticket. Please check connection.';
    });
}
</script>
</body>
</html>
