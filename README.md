# Governor Crest Website - Admin Dashboard Setup Guide

## Installation Instructions

### Step 1: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database or import the SQL file:
   - Click "New" to create a database named `governor_crest`
   - Click "Import" and select `database.sql` from the project root
   - Click "Go" to import all tables and default data

### Step 2: Database Configuration
1. Open `config/database.php`
2. Update the database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Your MySQL password
   define('DB_NAME', 'governor_crest');
   ```

### Step 3: Access the Website
- **Frontend**: http://localhost/governor-crest/
- **Admin Panel**: http://localhost/governor-crest/admin/

### Default Admin Credentials
- **Username**: admin
- **Password**: admin123

**IMPORTANT**: Change these credentials after first login!

## Admin Dashboard Features

### 1. Dashboard
- View statistics (services, projects, messages)
- See recent contact messages
- Quick overview of website activity

### 2. Site Settings
- Update company information
- Edit contact details (email, phone, address)
- Manage social media links
- Update tagline and description

### 3. About Content
- Edit "Who We Are" section
- Update Mission statement
- Update Vision statement

### 4. Services Management
- Add/Edit/Delete services
- Toggle service active/inactive status
- Reorder services (display order)
- Update service icons, descriptions, and features

### 5. Projects Management
- Add/Edit/Delete projects
- Organize by category
- Toggle project visibility
- Upload project images

### 6. Messages
- View all contact form submissions
- Mark messages as read
- Delete messages
- See full message details

## File Structure

```
governor-crest/
├── admin/
│   ├── includes/
│   │   ├── sidebar.php
│   │   └── topbar.php
│   ├── index.php (Login)
│   ├── dashboard.php
│   ├── settings.php
│   ├── about.php
│   ├── services.php
│   ├── projects.php
│   ├── messages.php
│   ├── logout.php
│   └── admin-style.css
├── config/
│   └── database.php
├── includes/
│   ├── head.php
│   ├── header.php
│   ├── footer.php
│   └── contact-handler.php
├── images/
│   └── logo.png
├── index.php
├── about.php
├── services.php
├── projects.php
├── contact.php
├── styles.css
├── script.js
├── database.sql
└── .htaccess
```

## Security Notes

1. **Change Admin Password**: 
   - Login to admin panel
   - Go to database and update `admin_users` table
   - Use password_hash() for new passwords

2. **Secure Database**:
   - Use strong MySQL password
   - Update credentials in `config/database.php`

3. **File Permissions**:
   - Ensure proper file permissions on server
   - Protect `/admin` directory on production

## Troubleshooting

### Clean URLs Not Working?
1. Ensure mod_rewrite is enabled in Apache
2. Check `.htaccess` file exists in root
3. Verify AllowOverride is set in Apache config

### Database Connection Error?
1. Check MySQL is running
2. Verify database name in `config/database.php`
3. Confirm username and password

### Admin Login Issues?
1. Check database table `admin_users` exists
2. Verify session is working (check php.ini)
3. Clear browser cookies/cache

## Features

✅ Responsive design
✅ Clean URL structure (no .php extensions)
✅ Modular PHP includes
✅ Full admin dashboard
✅ Database-driven content
✅ Contact form with database storage
✅ Easy content management
✅ Bootstrap 5 framework
✅ Inter font integration
✅ **Complete SEO optimization**
✅ **Open Graph & Twitter Cards**
✅ **Schema.org structured data**
✅ **XML Sitemap & robots.txt**
✅ **Performance optimized (GZIP, caching)**
✅ **Security headers**
✅ **Semantic HTML5**
✅ **Image lazy loading**

## SEO Features

### Meta Tags & Social Media
- Unique title tags and meta descriptions for each page
- Open Graph tags for Facebook, LinkedIn sharing
- Twitter Card tags for enhanced Twitter sharing
- Proper canonical URLs to prevent duplicate content
- Nigerian locale (en_NG) and location targeting

### Structured Data (Schema.org)
- Organization schema with contact info and social profiles
- Service listings with structured data
- About page and contact page schemas
- Rich snippets ready for search results

### Performance & Security
- GZIP compression enabled (70% size reduction)
- Browser caching configured (images: 1 year, CSS/JS: 1 month)
- Security headers (XSS protection, clickjacking prevention)
- Image lazy loading for faster page loads
- Semantic HTML5 for better accessibility

### Search Engine Files
- **sitemap.xml**: All pages listed for search engines
- **robots.txt**: Proper crawl directives
- Clean URL structure for SEO-friendly URLs

### Documentation
- 📄 **SEO_GUIDE.md**: Comprehensive SEO implementation guide
- 📋 **SEO_CHECKLIST.md**: Complete checklist of implemented features
- 📊 **INTEGRATION_GUIDE.md**: Frontend-backend connection details

## Support

For issues or questions, refer to the code comments or check:
- Bootstrap docs: https://getbootstrap.com/
- PHP manual: https://www.php.net/manual/
- MySQL docs: https://dev.mysql.com/doc/

---

**Governor Crest Limited** - One Crest, Infinite Possibilities
