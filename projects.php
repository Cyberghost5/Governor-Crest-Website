<?php 
$current_page = 'projects'; 
$page_title = 'Our Projects';

// SEO Configuration
$seo_title = 'Our Projects & Portfolio - Governor Crest Limited';
$seo_description = 'View Governor Crest Limited\'s portfolio of completed and ongoing projects across real estate development, agriculture, logistics, and other sectors in Nigeria.';
$seo_keywords = 'Governor Crest projects, portfolio, real estate projects, completed projects Nigeria, development portfolio';
$canonical_url = 'https://www.governorcrestlimited.com/projects';
$og_type = 'website';
?>
<?php
require_once 'config/database.php';

$projects = [];
$proj_q = $conn->query("SELECT * FROM projects WHERE status='active' ORDER BY display_order ASC");
if ($proj_q) {
    while ($r = $proj_q->fetch_assoc()) {
        $projects[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Projects Hero Section -->
    <section class="projects-hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-warning mb-3">Projects & Achievements</h1>
                    <p class="text-white-50">Showcasing our milestones and impact across multiple sectors</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Listing -->
    <section class="projects-listing-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Projects</h2>
                <p class="text-muted">Explore some of our completed and ongoing projects across sectors.</p>
            </div>

            <?php if (empty($projects)): ?>
                <div class="row">
                    <div class="col-lg-8 mx-auto text-center">
                        <div class="coming-soon-icon mx-auto mb-4">
                            <i class="bi bi-rocket-takeoff"></i>
                        </div>
                        <h2 class="mb-4">Coming Soon</h2>
                        <p class="text-muted lead mb-5">
                            This section will showcase our completed projects, ongoing initiatives, and major 
                            achievements across all our business sectors. We're building something great and will share 
                            our milestones with you soon.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($projects as $p): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if (!empty($p['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($p['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['title']); ?>">
                                <?php endif; ?>
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($p['title']); ?></h5>
                                    <p class="text-muted small mb-3"><?php echo htmlspecialchars(substr($p['description'],0,180)); ?><?php echo strlen($p['description'])>180? '...':''; ?></p>
                                    <?php
                                        $slug = !empty($p['slug']) ? $p['slug'] : 'project-' . (int)$p['id'];
                                    ?>
                                    <a href="/project/<?php echo urlencode($slug); ?>" class="stretched-link text-warning">Read more</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- What to Expect Section -->
    <section class="what-to-expect-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">What to Expect</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">Completed Projects</h5>
                            <p class="text-muted mb-0">
                                Detailed case studies of our successful property developments, agricultural initiatives, and other 
                                ventures.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">Success Stories</h5>
                            <p class="text-muted mb-0">
                                Testimonials and stories from satisfied customers across all our service divisions.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">Ongoing Initiatives</h5>
                            <p class="text-muted mb-0">
                                Updates on current projects and our expansion plans across Bauchi State and beyond.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">Key Milestones</h5>
                            <p class="text-muted mb-0">
                                A timeline of our company's growth, achievements, and contributions to the community.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="stat-card">
                        <h2 class="text-warning mb-2">2023</h2>
                        <p class="text-muted mb-0">Year Founded</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <h2 class="text-warning mb-2">6</h2>
                        <p class="text-muted mb-0">Business Sectors</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <h2 class="text-warning mb-2">Bauchi</h2>
                        <p class="text-muted mb-0">Headquartered In</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
