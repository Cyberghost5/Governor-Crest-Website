<!-- Footer -->
<footer id="contact" class="footer bg-dark text-white py-5">
    <?php
    // Get settings if not already loaded
    if (!isset($settings)) {
        require_once 'config/database.php';
        $settings_query = $conn->query("SELECT * FROM site_settings");
        $settings = [];
        while ($row = $settings_query->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="images/logo.png" alt="Governor Crest Logo" height="30" class="me-2">
                    <!-- <h5 class="mb-0">Governor Crest</h5> -->
                </div>
                <p class="text-muted">
                    A multi-sector company driven by innovation and integrity, providing quality solutions that enhance everyday life.
                </p>
            </div>

            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-warning mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="./" class="text-muted text-decoration-none">Home</a></li>
                    <li><a href="about" class="text-muted text-decoration-none">About Us</a></li>
                    <li><a href="services" class="text-muted text-decoration-none">Our Services</a></li>
                    <li><a href="conference" class="text-muted text-decoration-none">Real Estate Conference 2026</a></li>
                    <li><a href="contact" class="text-muted text-decoration-none">Contact Us</a></li>
                    <li><a href="book" class="text-muted text-decoration-none">Book Appointment</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-warning mb-3">Contact Us</h6>
                <ul class="list-unstyled contact-info">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span class="text-muted"><?php echo htmlspecialchars($settings['address'] ?? 'Bauchi State, Nigeria'); ?></span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <span class="text-muted"><a href="mailto:<?php echo htmlspecialchars($settings['email'] ?? 'info@governorcrest.com'); ?>" target="_blank" class="text-muted" style="text-decoration: none"><?php echo htmlspecialchars($settings['email'] ?? 'info@governorcrest.com'); ?></a></span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <span class="text-muted"><a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? '+234 XXX XXX XXXX'); ?>" target="_blank" class="text-muted" style="text-decoration: none"><?php echo htmlspecialchars($settings['phone'] ?? '+234 XXX XXX XXXX'); ?></a></span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-warning mb-3">Follow Us</h6>
                <div class="social-links">
                    <a href="<?php echo htmlspecialchars($settings['facebook'] ?? '#'); ?>" class="text-white me-3" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['twitter'] ?? '#'); ?>" class="text-white me-3" target="_blank"><i class="bi bi-twitter"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['instagram'] ?? '#'); ?>" class="text-white me-3" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['linkedin'] ?? '#'); ?>" class="text-white" target="_blank"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['company_name'] ?? 'Governor Crest Limited'); ?>. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
