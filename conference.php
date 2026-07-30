<?php
$current_page = 'conference';
$page_title = 'Governor Crest Real Estate Conference 2026';
$seo_title = 'Governor Crest Real Estate Conference 2026 | Double 4 International Conference Center Bauchi';
$seo_description = 'Join Governor Crest Limited on August 15, 2026 at Double 4 International Conference Center, Bauchi, Bauchi State for Nigeria\'s premier real estate conference. Free registration, expert masterclasses, and live Q&A.';
$seo_keywords = 'Governor Crest Real Estate Conference 2026, Bauchi real estate conference, real estate investment Nigeria, land title Bauchi, Double 4 International Conference Center Bauchi events, Governor Crest Limited';

$structured_data = json_encode([
    "@context" => "https://schema.org",
    "@type" => "Event",
    "name" => "Governor Crest Real Estate Conference 2026",
    "startDate" => "2026-08-15T08:30:00+01:00",
    "endDate" => "2026-08-15T15:00:00+01:00",
    "eventAttendanceMode" => "https://schema.org/OfflineEventAttendanceMode",
    "eventStatus" => "https://schema.org/EventScheduled",
    "location" => [
        "@type" => "Place",
        "name" => "Double 4 International Conference Center",
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => "Off Bauchi Club Road",
            "addressLocality" => "Bauchi",
            "addressRegion" => "Bauchi State",
            "addressCountry" => "NG"
        ]
    ],
    "image" => [
        "https://www.governorcrestlimited.com/images/logo.png"
    ],
    "description" => "A flagship real estate conference organized by Governor Crest Limited to impact knowledge about real estate and answer all questions relating to land acquisition and investment.",
    "offers" => [
        "@type" => "Offer",
        "price" => "0",
        "priceCurrency" => "NGN",
        "availability" => "https://schema.org/InStock",
        "url" => "https://www.governorcrestlimited.com/conference"
    ],
    "organizer" => [
        "@type" => "Organization",
        "name" => "Governor Crest Limited",
        "url" => "https://www.governorcrestlimited.com"
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

require_once 'config/database.php';

// Auto-migration check to ensure database tables exist seamlessly
$check_tables = $conn->query("SHOW TABLES LIKE 'conference_guests'");
if ($check_tables && $check_tables->num_rows == 0) {
    // Create tables if they do not exist
    $conn->query("CREATE TABLE IF NOT EXISTS `conference_registrations` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `ticket_code` VARCHAR(50) NOT NULL,
      `full_name` VARCHAR(255) NOT NULL,
      `email` VARCHAR(255) NOT NULL,
      `phone` VARCHAR(50) NOT NULL,
      `occupation` VARCHAR(255) DEFAULT NULL,
      `questions` TEXT DEFAULT NULL,
      `status` ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
      `checked_in` TINYINT(1) DEFAULT 0,
      `checked_in_at` DATETIME DEFAULT NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `ticket_code` (`ticket_code`),
      KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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

    // Insert initial guest speakers
    $conn->query("INSERT INTO `conference_guests` (`name`, `designation`, `company`, `bio`, `image_url`, `display_order`) VALUES
    ('Arc. Ibrahim Bello', 'Keynote Speaker & Real Estate Strategist', 'Governor Crest Limited', 'Over 18 years of pioneering sustainable urban housing projects and real estate investment masterclasses across Northern Nigeria.', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=800&auto=format&fit=crop&q=80', 1),
    ('Dr. Amina Abubakar', 'Senior Land Documentation & Investment Analyst', 'Apex Urban Developers', 'Renowned authority on land title acquisition, Governor\'s Consent procedures, and high-yield property portfolios.', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80', 2),
    ('Engr. Chukwuma Eze', 'Chief Structural Consultant & Commercial Developer', 'Crestwood Infrastructure', 'Specialist in affordable eco-friendly housing technologies, Smart Cities integration, and modern architectural engineering.', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80', 3)");
}

// Fetch Special Guests
$speakers = [];
$guests_query = $conn->query("SELECT * FROM conference_guests WHERE status = 'active' ORDER BY display_order ASC, id ASC");
if ($guests_query && $guests_query->num_rows > 0) {
    while ($row = $guests_query->fetch_assoc()) {
        $speakers[] = $row;
    }
}

include 'includes/head.php';
include 'includes/header.php';
?>

<!-- Conference Hero Section -->
<section class="conference-hero">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <span class="conference-badge">
                    <i class="bi bi-calendar-event me-2"></i> AUGUST 15, 2026 • BAUCHI, NIGERIA
                </span>
                <h1 class="display-3 fw-bold text-white mb-3">
                    Governor Crest Real Estate Conference 2026
                </h1>
                <p class="lead text-light mb-4 fs-4 opacity-90 mx-auto" style="max-width: 800px;">
                    Unlocking wealth, mastering land acquisition, and answering all your critical real estate questions with industry leaders.
                </p>
                
                <!-- Venue Badge -->
                <div class="d-inline-flex align-items-center bg-dark bg-opacity-75 border border-warning px-4 py-2 rounded-pill mb-4 text-warning">
                    <i class="bi bi-geo-alt-fill me-2 fs-5"></i>
                    <span class="fw-semibold fs-6">Venue: Double 4 International Conference Center, Off Bauchi Club Road, Bauchi, Bauchi State</span>
                </div>

                <!-- Countdown Timer -->
                <div class="countdown-container">
                    <div class="countdown-box">
                        <span class="countdown-number" id="cd-days">00</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="cd-hours">00</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="cd-minutes">00</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number" id="cd-seconds">00</span>
                        <span class="countdown-label">Seconds</span>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg" data-bs-toggle="modal" data-bs-target="#registrationModal">
                        <i class="bi bi-ticket-perforated-fill me-2"></i> Claim Free Ticket
                    </button>
                    <a href="#speakers" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold rounded-pill">
                        <i class="bi bi-person-stars me-2"></i> Meet Special Guests
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Conference Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="pe-lg-4">
                    <span class="text-warning text-uppercase fw-bold tracking-wider fs-6">Organized By Governor Crest Limited</span>
                    <h2 class="display-5 fw-bold text-dark mb-4 mt-2">Empowering Bauchi & Nigeria Through Real Estate Excellence</h2>
                    <p class="fs-6 text-muted mb-3">
                        Organized by <strong>Governor Crest Limited</strong> - one of the leading and most trusted real estate companies in Bauchi State and Nigeria at large - this landmark conference brings together property investors, first-time home buyers, commercial developers, and industry experts.
                    </p>
                    <p class="fs-6 text-muted mb-4">
                        The core aim of this conference is to <strong>impact practical, actionable knowledge</strong> about real estate investments, land documentation, property appreciation strategies, and to <strong>answer every question you have regarding real estate</strong>.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm border-start border-4 border-warning">
                                <i class="bi bi-patch-check-fill text-warning fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">100% Free Entry</h6>
                                    <small class="text-muted">Open to the Public</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm border-start border-4 border-warning">
                                <i class="bi bi-qr-code-scan text-warning fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Instant E-Ticket</h6>
                                    <small class="text-muted">QR Entry Verification</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 bg-dark text-white">
                        <h4 class="text-warning fw-bold mb-4"><i class="bi bi-stars me-2"></i> What To Expect</h4>
                        <div class="d-flex mb-4">
                            <div class="fs-3 text-warning me-3"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div>
                                <h5 class="fw-bold">In-Depth Real Estate Masterclasses</h5>
                                <p class="mb-0">Learn how to navigate land titles, avoid property fraud, and identify high-ROI real estate projects in Bauchi and across Nigeria.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="fs-3 text-warning me-3"><i class="bi bi-chat-quote-fill"></i></div>
                            <div>
                                <h5 class="fw-bold">Live Q&A Session</h5>
                                <p class="mb-0">Directly ask our panel of seasoned experts any questions relating to land acquisition, building budgets, financing, and legal compliance.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="fs-3 text-warning me-3"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <h5 class="fw-bold">High-Value Networking</h5>
                                <p class="mb-0">Connect with fellow investors, architects, government representatives, and business executives over breakfast & networking breaks.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Special Guests Section -->
<section id="speakers" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill text-uppercase mb-2">Industry Leaders</span>
            <h2 class="display-5 fw-bold text-dark">Distinguished Special Guests & Speakers</h2>
            <p class="text-muted fs-5 mx-auto" style="max-width: 650px;">
                Meet the visionary real estate pioneers, legal experts, and developers leading the discussions at the Governor Crest Real Estate Conference 2026.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (!empty($speakers)): ?>
                <?php foreach ($speakers as $index => $speaker): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="speaker-card">
                            <div class="speaker-img-wrapper">
                                <img src="<?php echo htmlspecialchars($speaker['image_url']); ?>" alt="<?php echo htmlspecialchars($speaker['name']); ?>" loading="lazy">
                                <span class="speaker-badge">
                                    <i class="bi bi-star-fill me-1"></i> Special Guest
                                </span>
                            </div>
                            <div class="speaker-info">
                                <h5><?php echo htmlspecialchars($speaker['name']); ?></h5>
                                <div class="speaker-title"><?php echo htmlspecialchars($speaker['designation']); ?></div>
                                <div class="speaker-company"><i class="bi bi-building me-1"></i> <?php echo htmlspecialchars($speaker['company']); ?></div>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($speaker['bio']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p>Guest speakers list will be updated shortly.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Conference Agenda Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <span class="badge bg-dark text-warning px-3 py-2 fs-6 rounded-pill text-uppercase mb-2">Event Schedule</span>
                <h2 class="display-5 fw-bold text-dark">Conference Agenda & Timetable</h2>
                <p class="text-muted fs-5">August 15, 2026 • Double 4 International Conference Center, Bauchi</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                    <div class="timeline-item">
                        <span class="timeline-time"><i class="bi bi-clock me-1"></i> 08:30 AM - 09:30 AM</span>
                        <h5 class="fw-bold text-dark mb-1">Delegate Arrival, QR Ticket Scan & Registration</h5>
                        <p class="text-muted mb-0">Guest check-in via QR ticket scanner at gate, welcome package collection & morning tea networking.</p>
                    </div>

                    <div class="timeline-item">
                        <span class="timeline-time"><i class="bi bi-clock me-1"></i> 09:30 AM - 10:15 AM</span>
                        <h5 class="fw-bold text-dark mb-1">Opening Address: Real Estate Dynamics in Bauchi & Beyond</h5>
                        <p class="text-muted mb-0">Delivered by Executive Management of Governor Crest Limited on market outlook and investment opportunities.</p>
                    </div>

                    <div class="timeline-item">
                        <span class="timeline-time"><i class="bi bi-clock me-1"></i> 10:15 AM - 11:45 AM</span>
                        <h5 class="fw-bold text-dark mb-1">Keynote Session: Land Acquisition, Titles & Fraud Protection</h5>
                        <p class="text-muted mb-0">Masterclass on verifying land ownership, understanding Governor's Consent, C of O, and title perfection.</p>
                    </div>

                    <div class="timeline-item">
                        <span class="timeline-time"><i class="bi bi-clock me-1"></i> 11:45 AM - 01:15 PM</span>
                        <h5 class="fw-bold text-dark mb-1">Interactive Open Q&A Session</h5>
                        <p class="text-muted mb-0">Panel of guest experts answering all attendee questions live on stage. Bring your real estate questions!</p>
                    </div>

                    <div class="timeline-item mb-0">
                        <span class="timeline-time"><i class="bi bi-clock me-1"></i> 01:15 PM - 02:30 PM</span>
                        <h5 class="fw-bold text-dark mb-1">Networking Lunch, Property Showcase & Group Photos</h5>
                        <p class="text-muted mb-0">Executive networking lunch, Governor Crest project exhibition, one-on-one consultation, and closing remarks.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Venue & Map Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="venue-card">
                    <div class="venue-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-3">Official Venue</span>
                    <h3 class="fw-bold text-white mb-3">Double 4 International Conference Center</h3>
                    <p class="fs-6 text-light mb-4">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                        Off Bauchi Club Road, Bauchi, Bauchi State, Nigeria.
                    </p>
                    <hr class="border-secondary my-4">
                    <ul class="list-unstyled text-light mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Secure Parking Space</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Air-Conditioned Conference Hall</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Fast Wi-Fi & Media Support</li>
                    </ul>
                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold rounded-3 shadow" data-bs-toggle="modal" data-bs-target="#registrationModal">
                        <i class="bi bi-ticket-detailed-fill me-2"></i> Book Free Ticket Now
                    </button>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="map-wrapper shadow-lg">
                    <iframe class="map-placeholder p-2" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.339260128106!2d9.810703174798068!3d10.314710489807378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1054d7570055358d%3A0x13638fcf583e7c42!2sDouble%204%20International%20Conference%20Center!5e0!3m2!1sen!2sng!4v1785400612409!5m2!1sen!2sng" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action Section -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-3">Seats Are Limited. Reserve Yours Today!</h2>
                <p class="fs-5 mb-4">
                    Registration is 100% Free. Secure your ticket now to get your unique QR code ticket sent directly to your email.
                </p>
                <button type="button" class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg" data-bs-toggle="modal" data-bs-target="#registrationModal">
                    <i class="bi bi-check2-circle me-2"></i> Register For Free Now
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Free Registration Modal Shell -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center">
                    <img src="images/logo.png" alt="Governor Crest Logo" height="30" class="me-2">
                    <h5 class="modal-title fw-bold text-warning mb-0" id="registrationModalLabel">
                        Conference Ticket Registration
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase">100% Free Admission</span>
                    <h4 class="fw-bold text-dark mt-2">Governor Crest Real Estate Conference 2026</h4>
                    <p class="text-muted small">August 15, 2026 • Double 4 International Conference Center, Off Bauchi Club Road, Bauchi State</p>
                </div>

                <form id="conferenceRegForm" action="includes/conference-handler.php" method="POST">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="e.g. John Doe">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="e.g. john@example.com">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" id="phone" name="phone" required placeholder="e.g. +234 800 000 0000">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="occupation" class="form-label fw-semibold">Occupation / Organization</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-briefcase"></i></span>
                                <input type="text" class="form-control" id="occupation" name="occupation" placeholder="e.g. Investor, Architect, Civil Servant">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="questions" class="form-label fw-semibold">Questions For Our Speakers (Optional)</label>
                            <textarea class="form-control" id="questions" name="questions" rows="3" placeholder="What specific question about real estate would you like our panel of experts to answer during the Q&A session?"></textarea>
                        </div>
                    </div>

                    <div id="formAlert" class="alert d-none mt-3 mb-0" role="alert"></div>

                    <div class="mt-4 pt-2">
                        <button type="submit" id="btnRegSubmit" class="btn btn-warning w-100 py-3 fw-bold rounded-3 shadow">
                            <i class="bi bi-ticket-perforated-fill me-2"></i> Register Now & Get QR Code Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Countdown & Registration JS Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Conference Date: August 15, 2026 09:00:00
    const confDate = new Date("August 15, 2026 09:00:00").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = confDate - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("cd-days").innerText = String(days).padStart(2, '0');
            document.getElementById("cd-hours").innerText = String(hours).padStart(2, '0');
            document.getElementById("cd-minutes").innerText = String(minutes).padStart(2, '0');
            document.getElementById("cd-seconds").innerText = String(seconds).padStart(2, '0');
        } else {
            document.getElementById("cd-days").innerText = "00";
            document.getElementById("cd-hours").innerText = "00";
            document.getElementById("cd-minutes").innerText = "00";
            document.getElementById("cd-seconds").innerText = "00";
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Registration Form AJAX Handler
    const regForm = document.getElementById('conferenceRegForm');
    const btnSubmit = document.getElementById('btnRegSubmit');
    const formAlert = document.getElementById('formAlert');

    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating Your QR Ticket...';
            formAlert.classList.add('d-none');

            const formData = new FormData(regForm);
            formData.append('is_ajax', '1');

            fetch('includes/conference-handler.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Non-JSON response received:", text);
                    // Check if ticket code/URL was generated in text response
                    if (text.includes("conference-ticket") || text.includes("GCR-CONF-")) {
                        const match = text.match(/conference-ticket(\.php)?\?code=([A-Za-z0-9-]+)/);
                        if (match) {
                            return { success: true, redirect: match[0] };
                        }
                    }
                    // Strip HTML tags if PHP error string was outputted
                    const cleanMsg = text.replace(/<[^>]*>?/gm, '').trim();
                    return { success: false, message: cleanMsg.substring(0, 200) || "Server returned invalid response. Please try again." };
                }
            })
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="bi bi-ticket-perforated-fill me-2"></i> Register Now & Get QR Code Ticket';
                    formAlert.className = 'alert alert-danger mt-3 mb-0';
                    formAlert.innerText = data.message || 'An error occurred. Please try again.';
                    formAlert.classList.remove('d-none');
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-ticket-perforated-fill me-2"></i> Register Now & Get QR Code Ticket';
                formAlert.className = 'alert alert-danger mt-3 mb-0';
                formAlert.innerText = 'Network error or server unavailable. Please try again.';
                formAlert.classList.remove('d-none');
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
