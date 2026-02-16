<?php 
$current_page = 'about'; 
$page_title = 'About Us';

// SEO Configuration
$seo_title = 'About Governor Crest Limited - Our Mission, Vision & Values';
$seo_description = 'Learn about Governor Crest Limited, a forward-thinking multi-sector enterprise in Nigeria. Discover our mission to deliver innovative solutions across real estate, automotive, agriculture, and more.';
$seo_keywords = 'about Governor Crest, company history, mission vision, Nigerian multi-sector company, corporate values, business innovation Nigeria';
$canonical_url = 'https://www.governorcrestlimited.com/about';
$og_type = 'website';

require_once 'config/database.php';

// Get about content
$about_query = $conn->query("SELECT * FROM about_content LIMIT 1");
$about = $about_query->fetch_assoc();

// Structured Data (JSON-LD)
$structured_data = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => 'About Governor Crest Limited',
    'description' => $about['who_we_are'] ?? 'Governor Crest Limited is a forward-thinking multi-sector enterprise',
    'url' => 'https://www.governorcrestlimited.com/about'
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
    <!-- About Hero Section -->
    <section class="about-hero-section" aria-label="About Us Hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-warning mb-3">About Governor Crest Limited</h1>
                    <p class="text-white-50">Building excellence across multiple sectors since 2023</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title text-center mb-4">Who We Are</h2>
                    <p class="text-muted mb-4">
                        Governor Crest Limited is a dynamic multi-sector company headquartered in Bauchi State, Nigeria. 
                        Founded in 2023, we have quickly established ourselves as a trusted partner across various 
                        industries including real estate, automotive sales, agriculture, grooming services, logistics, and 
                        fashion.
                    </p>
                    <p class="text-muted">
                        Our diverse portfolio allows us to serve our community comprehensively, providing quality solutions 
                        that enhance everyday life. We believe in building lasting relationships with our clients through 
                        exceptional service, competitive pricing, and unwavering commitment to excellence.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision-section py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mission-icon mb-3">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <h4 class="card-title mb-3">Our Mission</h4>
                            <p class="text-muted">
                                <?php echo nl2br(htmlspecialchars($about['mission'] ?? 'To deliver innovative, reliable, and affordable solutions across multiple sectors, enhancing the quality of life for individuals and businesses alike. We strive to be the preferred choice by combining integrity, excellence, and customer-centric service in everything we do.')); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="vision-icon mb-3">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h4 class="card-title mb-3">Our Vision</h4>
                            <p class="text-muted">
                                <?php echo nl2br(htmlspecialchars($about['vision'] ?? 'To become Nigeria\'s leading diversified conglomerate, recognized for excellence, innovation, and positive impact across all sectors we serve. We envision a future where Governor Crest Limited is synonymous with quality, trust, and infinite possibilities for growth and success.')); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="core-values-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Our Core Values</h2>
                <p class="text-muted">The principles that guide everything we do</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="value-card text-center">
                        <div class="value-icon mx-auto mb-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="mb-3">Integrity</h5>
                        <p class="text-muted">We conduct business with honesty and transparency in all our dealings</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="value-card text-center">
                        <div class="value-icon mx-auto mb-3">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <h5 class="mb-3">Innovation</h5>
                        <p class="text-muted">We embrace modern solutions and continuously improve our services</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="value-card text-center">
                        <div class="value-icon mx-auto mb-3">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h5 class="mb-3">Customer Focus</h5>
                        <p class="text-muted">Your satisfaction and success drive everything we do</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="value-card text-center">
                        <div class="value-icon mx-auto mb-3">
                            <i class="bi bi-hand-thumbs-up"></i>
                        </div>
                        <h5 class="mb-3">Reliability</h5>
                        <p class="text-muted">We deliver on our promises with consistency and quality</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Journey Section -->
    <section class="journey-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Journey</h2>
            </div>

            <div class="row g-4">
                <div class="col-lg-10 mx-auto">
                    <div class="timeline-item mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="mb-3">Founded in 2023</h4>
                                <p class="text-muted mb-0">
                                    Governor Crest Limited was established in 2023 with a vision to create a multi-sector 
                                    company that would serve the diverse needs of Bauchi State and Nigeria at large. From our 
                                    inception, we committed ourselves to excellence, integrity, and innovation.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="mb-3">Headquartered in Bauchi</h4>
                                <p class="text-muted mb-0">
                                    Proudly based in Bauchi State, Nigeria, we serve our local community while maintaining 
                                    standards of service that compete with the best in the nation. Our location allows us to 
                                    understand and respond to the unique needs of our region while bringing world-class solutions 
                                    to our doorstep.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="mb-3">Growing Portfolio</h4>
                                <p class="text-muted mb-0">
                                    Today, we operate across six major sectors: real estate, automotive sales, agriculture, grooming 
                                    services, logistics, and fashion. Each division is committed to delivering quality, affordability, 
                                    and reliability to our valued customers.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CEO Message Section -->
    <section class="ceo-message-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            <h3 class="text-warning mb-3">A Note from the CEO</h3>
                            <p class="text-muted fst-italic">Coming soon - A message from our leadership team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
