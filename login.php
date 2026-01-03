<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>

	<!-- Login Content -->
    <div class="login-container">
        <div class="login-content">
            <div class="login-hero">
                <div class="login-hero-content">
                    <h2>Welcome Back</h2>
                    <p>Sign in to access your account, track orders, and manage your wine preferences.</p>
                    <div class="hero-features">
                        <div class="feature">
                            <i class="fas fa-wine-bottle"></i>
                            <span>Track your orders</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-heart"></i>
                            <span>Save favorite wines</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-percent"></i>
                            <span>Exclusive member discounts</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="login-form-container">
                <div class="login-header">
                    <h1 class="login-title">Sign In</h1>
                    <p class="login-subtitle">Access your CRIMSON CELLAR account</p>
                </div>
                
                <!-- Show Error Message -->
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form class="login-form" method="post" action="login_action.php">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Your email address" required>
                    </div>
                    
                    <div class="form-group password-toggle">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Your password" required>
                        <button type="button" class="toggle-password">
                        </button>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn">Sign In</button>
                    
                    <div class="register-link">
                        Don't have an account? <a href="register.php">Create account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>

</body>
</html>