<header>

    <?php
    // Include all necessary class files FIRST
    // init_cart.php already handles session_start()
    require_once 'init_cart.php';
    require_once 'conn_db.php';
    
    // Get current page for active navigation highlighting
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Logo - Clickable to home -->
    <a href="index.php" class="logo" aria-label="CRIMSON CELLAR - Go to homepage">
        CRIMSON CELLAR
    </a>

    <!-- Main Navigation -->
    <nav class="nav-center" aria-label="Main Navigation">
        <ul>
            <li>
                <a href="index.php" <?php echo ($current_page == 'index.php') ? 'class="active"' : ''; ?>>
                    Home
                </a>
            </li>
            <li>
                <a href="shop.php" <?php echo ($current_page == 'shop.php') ? 'class="active"' : ''; ?>>
                    Shop
                </a>
            </li>
            <li>
                <a href="about.php" <?php echo ($current_page == 'about.php') ? 'class="active"' : ''; ?>>
                    About
                </a>
            </li>
            <li>
                <a href="contact.php" <?php echo ($current_page == 'contact.php') ? 'class="active"' : ''; ?>>
                    Contact
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Search Container -->
    <div class="search-container-header">
        <form action="search.php" method="GET" class="search-form-header" role="search">
            <label for="search-input" class="sr-only">Search our wine collection</label>
            <input 
                type="text" 
                id="search-input"
                name="q" 
                placeholder="Search our collection..." 
                class="search-input-header" 
                value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                aria-label="Search wines"
            >
            <button type="submit" class="search-btn-header" aria-label="Submit search">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>
    </div>

    <!-- Header Right Section -->
    <div class="header-right">
        <!-- Shopping Cart -->
        <a href="cart.php" class="cart-container-header" aria-label="View shopping cart">
            <i class="fas fa-shopping-cart cart-icon-header" aria-hidden="true"></i>
            <span class="cart-count-header" aria-live="polite">
                <?php echo $cart->get_total_items(); ?>
            </span>
        </a>
        
        <?php if (isset($_SESSION['email'])): ?>
            <!-- Logged in: User Menu -->
            <div class="user-menu" role="button" tabindex="0" aria-label="User menu" aria-haspopup="true">
                <div class="user-menu-header">
                    <i class="fas fa-user user-icon" aria-hidden="true"></i>
                    <span class="user-name-mobile">
                        <?php echo htmlspecialchars($_SESSION['firstName'] ?? 'User'); ?>
                    </span>
                </div>
                <div class="user-dropdown" role="menu">
                    <div class="user-info-desktop">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?></div>
                        <div class="user-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                    </div>
                    <a href="member.php" role="menuitem">
                        <i class="fas fa-user-circle" aria-hidden="true"></i> My Profile
                    </a>
                    <a href="member.php?tab=orders" role="menuitem">
                        <i class="fas fa-receipt" aria-hidden="true"></i> Order History
                    </a>
                    <a href="logout.php" class="logout-link" role="menuitem">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Not logged in: Auth Buttons -->
            <div class="auth-buttons">
                <a href="login.php" class="auth-btn-login">Login</a>
                <a href="register.php" class="auth-btn-register">Register</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.nav-center');
    const searchContainer = document.querySelector('.search-container-header');
    
    if (menuToggle && nav) {
        menuToggle.addEventListener('click', function() {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('active');
            if (searchContainer) {
                searchContainer.classList.toggle('active');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('header')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                nav.classList.remove('active');
                if (searchContainer) {
                    searchContainer.classList.remove('active');
                }
            }
        });
    }
    
    // User menu keyboard accessibility
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const dropdown = this.querySelector('.user-dropdown');
                if (dropdown) {
                    const isVisible = dropdown.style.visibility === 'visible' || 
                                    dropdown.classList.contains('visible');
                    dropdown.style.visibility = isVisible ? 'hidden' : 'visible';
                    dropdown.style.opacity = isVisible ? '0' : '1';
                }
            }
        });
    }
});
</script>
