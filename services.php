<?php 
$current_page = 'services'; 
$page_title = 'Our Services';

// SEO Configuration
$seo_title = 'Our Services - Real Estate, Car Sales, Agriculture, Logistics & More | Governor Crest';
$seo_description = 'Explore Governor Crest\'s comprehensive services: land & property sales, quality vehicles, modern agriculture, reliable logistics, fashion retail, and grocery services in Nigeria.';
$seo_keywords = 'services Governor Crest, real estate services, car dealership, agricultural services, logistics company, fashion store, grocery retail, property management Nigeria';
$canonical_url = 'https://www.governorcrestlimited.com/services';
$og_type = 'website';

require_once 'config/database.php';

// Get all active services
$services = $conn->query("SELECT * FROM services WHERE status='active' ORDER BY display_order ASC");

// Build service list for structured data
$service_list = [];
$services_temp = $conn->query("SELECT name, description FROM services WHERE status='active' ORDER BY display_order ASC");
while ($svc = $services_temp->fetch_assoc()) {
    $service_list[] = [
        '@type' => 'Service',
        'serviceType' => $svc['name'],
        'description' => $svc['description']
    ];
}
$services->data_seek(0); // Reset pointer

// Structured Data (JSON-LD)
$structured_data = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'Governor Crest Services',
    'description' => 'Comprehensive services offered by Governor Crest Limited',
    'itemListElement' => $service_list
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
    <!-- Services Hero Section -->
    <section class="services-hero-section" aria-label="Services Hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-warning mb-3">Our Services</h1>
                    <p class="text-white-50">Comprehensive solutions across six dynamic sectors, all delivered with the same commitment to quality, affordability, and customer satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Details -->
    <?php 
    $counter = 0;
    while ($service = $services->fetch_assoc()): 
        $counter++;
        $features = explode("\n", trim($service['features']));
        $sectionClass = ($counter % 2 == 0) ? 'bg-light' : '';
        $orderClass = ($counter % 2 == 0) ? 'order-lg-2' : '';
        $orderClass2 = ($counter % 2 == 0) ? 'order-lg-1' : '';
    ?>
    <section class="service-detail-section py-5 <?php echo $sectionClass; ?>">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6 <?php echo $orderClass; ?>">
                    <img src="<?php echo htmlspecialchars($service['image_url']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($service['name']); ?> - Governor Crest Limited" loading="lazy">
                </div>
                <div class="col-lg-6 <?php echo $orderClass2; ?>">
                    <div class="service-icon mb-3">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                    </div>
                    <h2 class="mb-3"><?php echo htmlspecialchars($service['name']); ?></h2>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars($service['description']); ?></p>
                    <ul class="service-list">
                        <?php foreach ($features as $feature): 
                            if (trim($feature)): ?>
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i><?php echo htmlspecialchars(trim($feature)); ?></li>
                        <?php 
                            endif;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endwhile; ?>

    <!-- CTA Section -->
    <section class="service-cta-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="text-white mb-3">Ready to Get Started?</h2>
                    <p class="text-white mb-4">Contact us today to learn more about how our services can benefit you or your business</p>
                    <a href="contact" class="btn btn-warning btn-lg px-4">
                        Request Service
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
