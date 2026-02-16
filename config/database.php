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
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
