<?php
// Run this script once (php scripts/migrate_add_project_slug.php) to add a 'slug' column
// and populate it from titles. It will try to avoid collisions.
require_once __DIR__ . '/../config/database.php';

function slugify($text) {
    $text = preg_replace('~[\pL0-9]+~u', '-', $text);
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) return 'project';
    return $text;
}

// Add column if it doesn't exist
$colCheck = $conn->query("SHOW COLUMNS FROM projects LIKE 'slug'");
if ($colCheck && $colCheck->num_rows === 0) {
    echo "Adding slug column...\n";
    $conn->query("ALTER TABLE projects ADD COLUMN slug VARCHAR(255) DEFAULT NULL");
    $conn->query("ALTER TABLE projects ADD UNIQUE KEY (slug)");
}

// Populate slugs
$res = $conn->query("SELECT id, title, slug FROM projects");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        if (!empty($row['slug'])) continue;
        $base = slugify($row['title'] ?? 'project-' . $row['id']);
        $slug = $base;
        $i = 1;
        // ensure unique
        $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM projects WHERE slug = ?");
        while (true) {
            $stmt->bind_param('s', $slug);
            $stmt->execute();
            $r = $stmt->get_result()->fetch_assoc();
            if ($r['cnt'] == 0) break;
            $slug = $base . '-' . $i;
            $i++;
        }
        $u = $conn->prepare("UPDATE projects SET slug = ? WHERE id = ?");
        $u->bind_param('si', $slug, $row['id']);
        $u->execute();
        echo "Set slug for id {$row['id']} => $slug\n";
    }
}

echo "Done.\n";
?>