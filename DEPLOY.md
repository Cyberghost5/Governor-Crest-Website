Deployment & Testing Guide

1) Prerequisites
- PHP 8+ (recommended) with composer installed
- MySQL (database)
- A working SMTP server (or local sendmail)

2) Install dependencies
Run in project root:

```bash
composer install
```

This installs PHPMailer (used for SMTP sending).

3) Database
- Ensure `governorcrest` database exists and import `database.sql` if starting fresh.
- The application will auto-create `appointments` and `email_logs` tables if missing, but importing the SQL ensures initial site settings.

4) Configure SMTP
- Insert SMTP keys into `site_settings` table or via admin settings UI if available. Keys used:
  - `smtp_host` (e.g., smtp.example.com)
  - `smtp_port` (e.g., 587)
  - `smtp_user`
  - `smtp_pass`
  - `smtp_secure` (either `tls`, `ssl`, or empty)
- Also ensure `email` and `company_name` entries are set in `site_settings`.

5) Testing email flow
- Create a booking via `/book` page.
- Login to admin (`/admin/index.php`) and approve/disapprove the appointment via `Appointments`.
- Check the `email_logs` table for send attempts and inspect `method`, `success`, and `error_message` columns.
- If emails do not arrive, check SMTP credentials and server connectivity. For local testing you can use services like MailHog or Mailtrap.

6) Troubleshooting
- If PHPMailer not found, run `composer install` in project root.
- Check PHP error logs for warnings about mail sending or database errors.
- For SMTP connectivity issues, use `telnet smtp.example.com 587` or `openssl s_client -starttls smtp -connect smtp.example.com:587` to debug.

7) Optional improvements
- Move SMTP settings to a secure config file or environment variables instead of database for production.
- Add monitoring/log rotation for `email_logs`.

