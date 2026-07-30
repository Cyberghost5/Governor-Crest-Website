<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="./">
            <img src="images/logo.png" alt="Governor Crest Logo" height="40" class="me-2">
            <!-- <span>Governor Crest</span> -->
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="./">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'services') ? 'active' : ''; ?>" href="services">Our Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'projects') ? 'active' : ''; ?>" href="projects">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'conference') ? 'active' : ''; ?>" href="conference">Conference 2026</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'book') ? 'active' : ''; ?>" href="book">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>" href="contact">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
