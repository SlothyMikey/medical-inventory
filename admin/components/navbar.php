<?php
/**
 * Admin Navigation Bar Component
 * Include this file in admin pages to display the navigation
 */

// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

function isActive($dir) {
    global $currentDir;
    return ($currentDir === $dir) ? 'active' : '';
}

function isUsersDropdownOpen() {
    global $currentDir;
    return ($currentDir === 'nurses' || $currentDir === 'workers') ? true : false;
}
?>

<nav class="admin-navbar" id="adminNavbar">
    <div class="navbar-brand">
        <div class="brand-icon">
            <i class="fas fa-hospital"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">MedInventory</span>
            <span class="brand-role">Admin Panel</span>
        </div>
        <button class="navbar-collapse-btn" id="navbarCollapseBtn" title="Collapse menu">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <div class="navbar-menu" id="navbarMenu">
        <ul class="nav-links">
            <li class="nav-item">
                <a href="/medical-inventory/admin/dashboard/" class="nav-link <?php echo isActive('dashboard'); ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Users Dropdown -->
            <li class="nav-item has-dropdown">
                <button class="nav-link dropdown-toggle <?php echo isUsersDropdownOpen() ? 'open' : ''; ?>" id="usersDropdownBtn">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <ul class="dropdown-menu <?php echo isUsersDropdownOpen() ? 'show' : ''; ?>" id="usersDropdown">
                    <li>
                        <a href="/medical-inventory/admin/nurses/" class="dropdown-link <?php echo isActive('nurses'); ?>">
                            <i class="fas fa-user-nurse"></i>
                            <span>Nurses</span>
                        </a>
                    </li>
                    <li>
                        <a href="/medical-inventory/admin/workers/" class="dropdown-link <?php echo isActive('workers'); ?>">
                            <i class="fas fa-headset"></i>
                            <span>Workers</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Patients -->
            <li class="nav-item">
                <a href="/medical-inventory/admin/patients/" class="nav-link <?php echo isActive('patients'); ?>">
                    <i class="fas fa-procedures"></i>
                    <span>Patients</span>
                </a>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a href="/medical-inventory/admin/reports/" class="nav-link <?php echo isActive('reports'); ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
        </ul>

        <div class="nav-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-details">
                    <span class="user-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></span>
                    <span class="user-role">Administrator</span>
                </div>
            </div>
            <a href="/medical-inventory/auth/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</nav>

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Menu">
    <i class="fas fa-bars"></i>
</button>

<!-- Mobile Overlay -->
<div class="navbar-overlay" id="navbarOverlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('adminNavbar');
    const collapseBtn = document.getElementById('navbarCollapseBtn');
    const mobileToggle = document.getElementById('mobileToggle');
    const navbarOverlay = document.getElementById('navbarOverlay');
    const usersDropdownBtn = document.getElementById('usersDropdownBtn');
    const usersDropdown = document.getElementById('usersDropdown');
    const mainContent = document.querySelector('.main-content');
    
    // Check localStorage for navbar state
    const isCollapsed = localStorage.getItem('navbarCollapsed') === 'true';
    if (isCollapsed) {
        navbar.classList.add('collapsed');
        if (mainContent) mainContent.classList.add('expanded');
    }
    
    // Navbar collapse toggle
    if (collapseBtn) {
        collapseBtn.addEventListener('click', function() {
            navbar.classList.toggle('collapsed');
            if (mainContent) mainContent.classList.toggle('expanded');
            
            // Save state to localStorage
            localStorage.setItem('navbarCollapsed', navbar.classList.contains('collapsed'));
        });
    }
    
    // Mobile menu toggle
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            navbar.classList.toggle('mobile-open');
            navbarOverlay.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking overlay
    if (navbarOverlay) {
        navbarOverlay.addEventListener('click', function() {
            navbar.classList.remove('mobile-open');
            navbarOverlay.classList.remove('active');
        });
    }
    
    // Users dropdown toggle
    if (usersDropdownBtn) {
        usersDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            usersDropdown.classList.toggle('show');
            this.classList.toggle('open');
        });
    }
    
    // Check if we need to open a modal (from URL parameter)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('openModal') === 'true') {
        const modal = document.getElementById('createModal');
        if (modal) {
            modal.classList.add('active');
        }
        // Clean the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
