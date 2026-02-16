$<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/csrf.php';

$current_page = 'book';
$page_title = 'Book Appointment';

// SEO Configuration
$seo_title = 'Book an Appointment - Governor Crest Limited | Bauchi, Nigeria';
$seo_description = 'Schedule an appointment with Governor Crest Limited for our services including real estate, car sales, agriculture, logistics, salon, and more.';
$seo_keywords = 'book appointment, Governor Crest appointment, schedule meeting, Bauchi appointments';
$canonical_url = 'https://www.governorcrestlimited.com/book';
$og_type = 'website';

require_once 'config/database.php';

// Get site settings
$settings_query = $conn->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $settings_query->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Load available services for select
$services = [];
$svc_q = $conn->query("SELECT id, name FROM services WHERE status='active' ORDER BY display_order ASC");
while ($s = $svc_q->fetch_assoc()) {
    $services[] = $s;
}

include 'includes/book-handler.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
    <!-- Booking Hero Section -->
    <section class="contact-hero-section" aria-label="Book Appointment Hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-warning mb-3">Book an Appointment</h1>
                    <p class="text-white-50">Schedule a convenient time to discuss our services or visit our office.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Content Section -->
    <section class="contact-content-section py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Info Column -->
                <div class="col-lg-5">
                    <div class="contact-info-wrapper">
                        <h3 class="mb-4">Schedule With Us</h3>
                        <p class="text-muted mb-4">Choose a service and preferred date/time. We will confirm availability shortly.</p>

                        <div class="contact-item mb-4">
                            <div class="contact-item-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="contact-item-content">
                                <h5 class="mb-2">Office Address</h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($settings['address'] ?? 'Bauchi State, Nigeria'); ?></p>
                            </div>
                        </div>

                        <div class="contact-item mb-4">
                            <div class="contact-item-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="contact-item-content">
                                <h5 class="mb-2">Business Hours</h5>
                                <p class="text-muted mb-0">Mon - Fri: 8:00 AM - 6:00 PM</p>
                                <p class="text-muted mb-0">Sat: 9:00 AM - 4:00 PM</p>
                            </div>
                        </div>

                        <div class="business-hours-card">
                            <h5 class="mb-3">Contact</h5>
                            <p class="text-muted mb-0">Email: <?php echo htmlspecialchars($settings['email'] ?? 'info@governorcrest.com'); ?></p>
                            <p class="text-muted mb-0">Phone: <?php echo htmlspecialchars($settings['phone'] ?? '+234 XXX XXX XXXX'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <h3 class="mb-4">Book Your Appointment</h3>

                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">Thank you! Your appointment request has been received. We'll confirm shortly.</div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <?php echo csrf_input(); ?>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Your full name" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emailAddress" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="emailAddress" name="emailAddress" placeholder="your.email@example.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="+234 XXX XXX XXXX">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="service" class="form-label">Service</label>
                                <select class="form-select" id="service" name="service">
                                    <option value="">Select a service</option>
                                    <?php foreach ($services as $svc): ?>
                                        <option value="<?php echo htmlspecialchars($svc['name']); ?>"><?php echo htmlspecialchars($svc['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="preferredDate" class="form-label">Preferred Date</label>
                                    <input type="date" class="form-control" id="preferredDate" name="preferredDate" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="preferredTime" class="form-label">Preferred Time</label>
                                    <input type="time" class="form-control" id="preferredTime" name="preferredTime">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Notes</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Any additional information (optional)"></textarea>
                            </div>

                            <button type="submit" name="appointment_submit" class="btn btn-warning btn-lg w-100">
                                Request Appointment
                                <i class="bi bi-calendar-check ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section py-5 bg-light">
        <div class="container">
            <div class="map-wrapper">
                <iframe class="map-placeholder" width="100%" height="450px" src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d977.5268567752759!2d9.822308777270543!3d10.306533000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sNo.%2015.%20Ibnu%20Plaza%20Before%20NDIC%20Office%2C%20Bank%20Road%20Bauchi%2C%20Bauchi%20State%2C%20Nigeria.!5e1!3m2!1sen!2sng!4v1770375554579!5m2!1sen!2sng" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var dateInput = document.getElementById('preferredDate');
        var timeInput = document.getElementById('preferredTime');
        if (!dateInput || !timeInput) return;

        // Ensure server-side min is set (PHP printed), and enforce time min when date is today
        dateInput.min = '<?php echo date('Y-m-d'); ?>';

        function pad(n){ return n.toString().padStart(2, '0'); }

        function updateMinTime() {
            var todayStr = '<?php echo date('Y-m-d'); ?>';
            if (dateInput.value === todayStr) {
                var now = new Date();
                var hh = pad(now.getHours());
                var mm = pad(now.getMinutes());
                timeInput.min = hh + ':' + mm;
                if (timeInput.value && timeInput.value < timeInput.min) timeInput.value = '';
            } else {
                timeInput.min = '';
            }
        }

        // Initial check and on change
        updateMinTime();
        dateInput.addEventListener('change', updateMinTime);
    });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
