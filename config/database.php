<?php
// Database configuration // LIVE
// define('DB_HOST', 'localhost');
// define('DB_USER', 'governor_crest');
// define('DB_PASS', '?(dRyRrx!n1I');
// define('DB_NAME', 'governor_crest'); 

// Localhost configuration // LOCAL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'governorcrest'); 

// Create connection
$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection and auto-create database if it doesn't exist
if ($conn->connect_error) {
    $tmp_conn = @new mysqli(DB_HOST, DB_USER, DB_PASS);
    if (!$tmp_conn->connect_error) {
        $tmp_conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $tmp_conn->close();
        $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
}

// Check connection after retry
if ($conn->connect_error) {
    die("Database Connection Error: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Admin credentials (you can change these)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', password_hash('admin123', PASSWORD_DEFAULT)); // Change 'admin123' to your desired password

// Site settings
$site_settings = [
    'company_name' => 'Governor Crest Limited',
    'email' => 'info@governorcrestlimited.com',
    'phone' => '+234 XXX XXX XXXX',
    'address' => 'Bauchi State, Nigeria',
    'facebook' => '#',
    'twitter' => '#',
    'instagram' => '#',
    'linkedin' => '#'
];
?>
