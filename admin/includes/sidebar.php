<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-check me-2"></i>Admin Panel
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        </li>
        <li>
            <a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="bi bi-gear"></i>Site Settings
            </a>
        </li>
        <li>
            <a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                <i class="bi bi-info-circle"></i>About Content
            </a>
        </li>
        <li>
            <a href="services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="bi bi-briefcase"></i>Services
            </a>
        </li>
        <li>
            <a href="projects.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'active' : ''; ?>">
                <i class="bi bi-folder"></i>Projects
            </a>
        </li>
        <li>
            <a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                <i class="bi bi-envelope"></i>Messages
                <?php if (isset($unread_messages) && $unread_messages > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo $unread_messages; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="appointments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : ''; ?>">
                <i class="bi bi-calendar-check"></i>Appointments
            </a>
        </li>
        <li>
            <a href="conference-registrations.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'conference-registrations.php' ? 'active' : ''; ?>">
                <i class="bi bi-ticket-perforated"></i>Conf. Registrations
            </a>
        </li>
        <li>
            <a href="conference-guests.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'conference-guests.php' ? 'active' : ''; ?>">
                <i class="bi bi-person-badge"></i>Conf. Special Guests
            </a>
        </li>
        <li>
            <a href="conference-scanner.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'conference-scanner.php' ? 'active' : ''; ?>">
                <i class="bi bi-qr-code-scan"></i>Gate Ticket Scanner
            </a>
        </li>
        <li>
            <a href="../" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>View Website
            </a>
        </li>
        <li>
            <a href="logout.php">
                <i class="bi bi-box-arrow-right"></i>Logout
            </a>
        </li>
    </ul>
</div>
