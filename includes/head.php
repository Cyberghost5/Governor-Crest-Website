<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Primary Meta Tags -->
<title><?php echo isset($page_title) ? $page_title . ' - Governor Crest Limited' : 'Governor Crest Limited - One Crest, Infinite Possibilities'; ?></title>
<meta name="title" content="<?php echo isset($seo_title) ? $seo_title : (isset($page_title) ? $page_title . ' - Governor Crest Limited' : 'Governor Crest Limited - Multi-Sector Company in Nigeria'); ?>">
<meta name="description" content="<?php echo isset($seo_description) ? $seo_description : 'Governor Crest Limited offers quality services across real estate, car sales, agriculture, logistics, fashion, and groceries in Bauchi State, Nigeria.'; ?>">
<meta name="keywords" content="<?php echo isset($seo_keywords) ? $seo_keywords : 'Governor Crest, real estate Bauchi, car sales Nigeria, agriculture Nigeria, logistics services, fashion Nigeria, grocery retail, multi-sector company Nigeria'; ?>">
<meta name="author" content="Governor Crest Limited">
<meta name="robots" content="index, follow">
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">

<!-- Canonical URL -->
<link rel="canonical" href="<?php echo isset($canonical_url) ? $canonical_url : 'https://www.governorcrestlimited.com/' . ($current_page === 'index' ? '' : $current_page); ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?php echo isset($og_type) ? $og_type : 'website'; ?>">
<meta property="og:url" content="<?php echo isset($canonical_url) ? $canonical_url : 'https://www.governorcrestlimited.com/' . ($current_page === 'index' ? '' : $current_page); ?>">
<meta property="og:title" content="<?php echo isset($seo_title) ? $seo_title : (isset($page_title) ? $page_title . ' - Governor Crest Limited' : 'Governor Crest Limited - One Crest, Infinite Possibilities'); ?>">
<meta property="og:description" content="<?php echo isset($seo_description) ? $seo_description : 'Governor Crest Limited offers quality services across real estate, car sales, agriculture, logistics, fashion, and groceries in Bauchi State, Nigeria.'; ?>">
<meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'https://www.governorcrestlimited.com/images/favicon.png'; ?>">
<meta property="og:locale" content="en_NG">
<meta property="og:site_name" content="Governor Crest Limited">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo isset($canonical_url) ? $canonical_url : 'https://www.governorcrestlimited.com/' . ($current_page === 'index' ? '' : $current_page); ?>">
<meta property="twitter:title" content="<?php echo isset($seo_title) ? $seo_title : (isset($page_title) ? $page_title . ' - Governor Crest Limited' : 'Governor Crest Limited - One Crest, Infinite Possibilities'); ?>">
<meta property="twitter:description" content="<?php echo isset($seo_description) ? $seo_description : 'Governor Crest Limited offers quality services across real estate, car sales, agriculture, logistics, fashion, and groceries in Bauchi State, Nigeria.'; ?>">
<meta property="twitter:image" content="<?php echo isset($og_image) ? $og_image : 'https://www.governorcrestlimited.com/images/favicon.png'; ?>">

<!-- Favicon -->
<link rel="icon" type="image/png" href="images/favicon.png">
<link rel="apple-touch-icon" href="images/favicon.png">

<!-- Preconnect for Performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net">

<!-- Fonts & Styles -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="styles.css">

<?php if (isset($structured_data)): ?>
<!-- Structured Data (Schema.org) -->
<script type="application/ld+json">
<?php echo $structured_data; ?>
</script>
<?php endif; ?>
