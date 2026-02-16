<?php 
$current_page = 'contact'; 
$page_title = 'Contact Us';

// SEO Configuration
$seo_title = 'Contact Governor Crest Limited - Get in Touch | Bauchi, Nigeria';
$seo_description = 'Contact Governor Crest Limited for inquiries about real estate, car sales, agriculture, logistics, fashion, and grocery services. Visit us in Bauchi State or reach out via phone and email.';
$seo_keywords = 'contact Governor Crest, customer service, Bauchi office, contact information, business inquiries Nigeria, get in touch';
$canonical_url = 'https://www.governorcrestlimited.com/contact';
$og_type = 'website';

require_once 'config/database.php';

// Get site settings
$settings_query = $conn->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $settings_query->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Structured Data (JSON-LD)
$structured_data = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ContactPage',
    'name' => 'Contact Governor Crest Limited',
    'description' => 'Contact information for Governor Crest Limited',
    'url' => 'https://www.governorcrestlimited.com/contact',
    'mainEntity' => [
        '@type' => 'Organization',
        'name' => $settings['company_name'] ?? 'Governor Crest Limited',
        'telephone' => $settings['phone'] ?? '+234-XXX-XXX-XXXX',
        'email' => $settings['email'] ?? 'info@governorcrest.com',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Bauchi',
            'addressRegion' => 'Bauchi State',
            'addressCountry' => 'NG'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

include 'includes/contact-handler.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
    <!-- Contact Hero Section -->
    <section class="contact-hero-section" aria-label="Contact Us Hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-warning mb-3">Contact Us</h1>
                    <p class="text-white-50">Get in touch with us for inquiries, service requests, or partnership opportunities</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content Section -->
    <section class="contact-content-section py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Information -->
                <div class="col-lg-5">
                    <div class="contact-info-wrapper">
                        <h3 class="mb-4">Get In Touch</h3>
                        <p class="text-muted mb-5">
                            We're here to answer your questions and discuss how we can help you. 
                            Reach out to us through any of the following channels:
                        </p>

                        <!-- Office Address -->
                        <div class="contact-item mb-4">
                            <div class="contact-item-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="contact-item-content">
                                <h5 class="mb-2">Office Address</h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($settings['address'] ?? 'Bauchi State, Nigeria'); ?></p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-item mb-4">
                            <div class="contact-item-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="contact-item-content">
                                <h5 class="mb-2">Email</h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($settings['email'] ?? 'info@governorcrest.com'); ?></p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="contact-item mb-5">
                            <div class="contact-item-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="contact-item-content">
                                <h5 class="mb-2">Phone</h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($settings['phone']); ?></p>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="business-hours-card">
                            <h5 class="mb-3">Business Hours</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Monday - Friday:</span>
                                <span class="fw-medium">8:00 AM - 6:00 PM</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Saturday:</span>
                                <span class="fw-medium">9:00 AM - 4:00 PM</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Sunday:</span>
                                <span class="fw-medium">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <h3 class="mb-4">Send Us a Message</h3>
                        
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">Thank you for your message! We will get back to you soon.</div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Your full name" required>
                            </div>

                            <div class="mb-3">
                                <label for="emailAddress" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="emailAddress" name="emailAddress" placeholder="your.email@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="+234 XXX XXX XXXX">
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                            </div>

                            <button type="submit" name="contact_submit" class="btn btn-warning btn-lg w-100">
                                Send Message
                                <i class="bi bi-send ms-2"></i>
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
                <iframe class="map-placeholder" width="100%" height="500px" src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d977.5268567752759!2d9.822308777270543!3d10.306533000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sNo.%2015.%20Ibnu%20Plaza%20Before%20NDIC%20Office%2C%20Bank%20Road%20Bauchi%2C%20Bauchi%20State%2C%20Nigeria.!5e1!3m2!1sen!2sng!4v1770375554579!5m2!1sen!2sng" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <!-- <div class="map-placeholder">
                    <div class="map-icon mb-3">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <p class="text-muted mb-2">Map integration can be added here</p>
                    <p class="text-muted small">Location: Bauchi State, Nigeria</p>
                </div> -->
            </div>
        </div>
    </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
