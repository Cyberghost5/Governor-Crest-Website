<?php
// project.php - show a single project by slug or id
require_once 'config/database.php';

$slug = '';
if (!empty($_GET['slug'])) {
    $slug = trim($_GET['slug']);
} else {
    // try to parse PATH_INFO if available
    if (!empty($_SERVER['PATH_INFO'])) {
        $parts = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        if (isset($parts[0])) $slug = $parts[0];
    }
}

$project = null;
// Check if 'slug' column exists
$hasSlug = false;
$colCheck = $conn->query("SHOW COLUMNS FROM projects LIKE 'slug'");
if ($colCheck && $colCheck->num_rows > 0) $hasSlug = true;

if ($hasSlug && $slug !== '') {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE slug = ? AND status = 'active' LIMIT 1");
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) $project = $res->fetch_assoc();
}

// If not found by slug, try by id if numeric
if (!$project && is_numeric($slug)) {
    $id = intval($slug);
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND status = 'active' LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) $project = $res->fetch_assoc();
}

if (!$project) {
    http_response_code(404);
    $current_page = 'projects';
    $page_title = 'Project Not Found';
    $seo_title = $page_title . ' - Governor Crest Limited';
    $seo_description = 'Project not found';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php include 'includes/head.php'; ?>
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <main class="py-5"><div class="container"><h2>Project not found</h2><p>The project you requested could not be located.</p></div></main>
        <?php include 'includes/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

$current_page = 'projects';
$page_title = $project['title'] ?? 'Project';

// SEO fields
$seo_title = $project['title'] . ' - Governor Crest Limited';
$seo_description = substr(strip_tags($project['description'] ?? ''), 0, 160);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="project-hero py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto text-center">
                        <h1 class="text-warning"><?php echo htmlspecialchars($project['title']); ?></h1>
                        <p class="text-muted"><?php echo htmlspecialchars($project['category'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="project-content py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <?php if (!empty($project['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-fluid mb-4">
                        <?php endif; ?>
                        <div class="project-description">
                            <?php echo $project['description']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
