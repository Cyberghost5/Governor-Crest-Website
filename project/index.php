<?php
// Front controller for pretty project URLs like /project/project-1
// Extract slug from the request URI and include root project.php
$request = $_SERVER['REQUEST_URI'] ?? '';
$script = $_SERVER['SCRIPT_NAME'] ?? '';

// Remove query string
$request = strtok($request, '?');

// Expected pattern: /project/<slug>
$parts = explode('/', trim($request, '/'));
$slug = '';
if (isset($parts[0]) && $parts[0] === 'project' && isset($parts[1])) {
    $slug = $parts[1];
}

if ($slug) {
    // Set GET param and include project.php from root
    $_GET['slug'] = $slug;
    include __DIR__ . '/../project.php';
    exit;
}

// fallback
header("HTTP/1.0 404 Not Found");
echo "Project not found.";
exit;
