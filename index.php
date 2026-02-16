<?php 
$current_page = 'index'; 
$page_title = 'Home';

// SEO Configuration
$seo_title = 'Governor Crest Limited - Multi-Sector Company | Real Estate, Cars, Agriculture & More';
$seo_description = 'Governor Crest Limited is a leading multi-sector company in Bauchi, Nigeria offering real estate, car sales, agriculture, logistics, fashion, and grocery services. One Crest, Infinite Possibilities.';
$seo_keywords = 'Governor Crest, multi-sector company Nigeria, real estate Bauchi, car sales Nigeria, agriculture Bauchi, logistics Nigeria, fashion retail, grocery store Bauchi, property sales Nigeria';
$canonical_url = 'https://www.governorcrestlimited.com/';
$og_type = 'website';

require_once 'config/database.php';

// Get site settings
$settings_query = $conn->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $settings_query->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get active services
$services = $conn->query("SELECT * FROM services WHERE status='active' ORDER BY display_order ASC LIMIT 6");

// Structured Data (JSON-LD)
$structured_data = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $settings['company_name'] ?? 'Governor Crest Limited',
    'url' => 'https://www.governorcrestlimited.com',
    'logo' => 'https://www.governorcrestlimited.com/images/favicon.png',
    'description' => $settings['description'] ?? 'A multi-sector company driven by innovation and integrity',
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Bauchi',
        'addressRegion' => 'Bauchi State',
        'addressCountry' => 'NG'
    ],
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'telephone' => $settings['phone'] ?? '+234-XXX-XXX-XXXX',
        'contactType' => 'customer service',
        'email' => $settings['email'] ?? 'info@governorcrest.com'
    ],
    'sameAs' => [
        $settings['facebook'] ?? '',
        $settings['twitter'] ?? '',
        $settings['instagram'] ?? '',
        $settings['linkedin'] ?? ''
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
    <!-- Hero Section -->
    <section id="home" class="hero-section" aria-label="Homepage Hero">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold text-white mb-3"><?php echo htmlspecialchars($settings['tagline'] ?? 'One Crest, Infinite Possibilities'); ?></h1>
                    <p class="lead text-white mb-4"><?php echo htmlspecialchars($settings['description'] ?? 'A multi-sector company driven by innovation and integrity'); ?></p>
                    <a href="services" class="btn btn-warning btn-lg px-4">
                        Explore Our Services
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section id="about" class="welcome-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title mb-4">Welcome to <?php echo htmlspecialchars($settings['company_name'] ?? 'Governor Crest Limited'); ?></h2>
                    <p class="text-muted">
                        <?php echo htmlspecialchars($settings['company_name'] ?? 'Governor Crest Limited'); ?> is a multi-sector company driven by innovation and integrity. We operate 
                        across real estate, car sales, agriculture, logistics, fashion, and groceries — providing quality solutions 
                        that enhance everyday life.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Sectors Section -->
    <section id="services" class="business-sectors-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Our Business Sectors</h2>
                <p class="text-muted">Diverse solutions for modern living</p>
            </div>
            
            <div class="row g-4">
                <?php while ($service = $services->fetch_assoc()): 
                    $features = explode("\n", $service['features']);
                ?>
                <article class="col-md-6">
                    <div class="card sector-card h-100">
                        <img src="<?php echo htmlspecialchars($service['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service['name']); ?> - Governor Crest Limited" loading="lazy">
                        <div class="card-body">
                            <h3 class="card-title h5"><?php echo htmlspecialchars($service['name']); ?></h3>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($service['description']); ?></p>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-5">
                <a href="services" class="btn btn-outline-dark">
                    View All Services
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Why Choose Governor Crest?</h2>
                <p class="text-muted">Excellence across all sectors</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card feature-card text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="card-title">Trust & Reliability</h5>
                            <p class="card-text text-muted">Built on integrity and commitment to excellence</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card feature-card text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <h5 class="card-title">Quality Solutions</h5>
                            <p class="card-text text-muted">Delivering premium services across all sectors</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card feature-card text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h5 class="card-title">Customer First</h5>
                            <p class="card-text text-muted">Your satisfaction is our top priority</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card feature-card text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon mx-auto mb-3">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <h5 class="card-title">Innovation</h5>
                            <p class="card-text text-muted">Embracing modern solutions and technology</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="text-white mb-3">Ready to Work With Us?</h2>
                    <p class="text-white mb-4">
                        Whether you're looking for real estate, vehicles, agricultural solutions, or any of our services, 
                        we're here to serve you!
                    </p>
                    <a href="contact" class="btn btn-warning btn-lg px-4">
                        Get In Touch
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
