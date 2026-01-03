<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>

    <!-- Register Content -->
    <div class="register-container">
        <div class="register-content">
            <div class="register-header">
                <h1 class="register-title">Create Account</h1>
                <p class="register-subtitle">Join CRIMSON CELLAR to explore our premium wine collection</p>
            </div>

            <!-- Show Error Message -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form class="register-form" name="registerForm" method="post" action="register_action.php">
                <div class="form-group">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Your first name" value="<?php echo isset($_SESSION['form_data']['firstName']) ? $_SESSION['form_data']['firstName'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Your last name" value="<?php echo isset($_SESSION['form_data']['lastName']) ? $_SESSION['form_data']['lastName'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Your email address" value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>" required>
                    <small class="form-note">Must be unique - we'll use this for your account login</small>
                </div>
                
                <div class="form-group password-toggle">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Create a password" required>
                </div>
                
                <div class="form-group password-toggle">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="Confirm your password" required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input" placeholder="Your phone number" value="<?php echo isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Street Address</label>
                    <input type="text" id="address" name="address" class="form-input" placeholder="Your street address" value="<?php echo isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="city" class="form-label">Suburb</label>
                    <input type="text" id="suburb" name="suburb" class="form-input" placeholder="Your suburb" value="<?php echo isset($_SESSION['form_data']['suburb']) ? $_SESSION['form_data']['suburb'] : ''; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state" class="form-label">State</label>
                        <select id="state" name="state" class="form-input">
                            <option value="">Select your state</option>
                            <option value="NSW" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'NSW') ? 'selected' : ''; ?>>New South Wales</option>
                            <option value="VIC" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'VIC') ? 'selected' : ''; ?>>Victoria</option>
                            <option value="ACT" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'ACT') ? 'selected' : ''; ?>>Australian Capital Territory</option>
                            <option value="SA" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'SA') ? 'selected' : ''; ?>>South Australia</option>
                            <option value="WA" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'WA') ? 'selected' : ''; ?>>Western Australia</option>
                            <option value="NT" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'NT') ? 'selected' : ''; ?>>Northern Territory</option>
                            <option value="TAS" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'TAS') ? 'selected' : ''; ?>>Tasmania</option>
                            <option value="QLD" <?php echo (isset($_SESSION['form_data']['state']) && $_SESSION['form_data']['state'] == 'QLD') ? 'selected' : ''; ?>>Queensland</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="postalCode" class="form-label">Postal Code</label>
                        <input type="text" id="postalCode" name="postalCode" class="form-input" placeholder="Postal code" value="<?php echo isset($_SESSION['form_data']['postal']) ? $_SESSION['form_data']['postal'] : ''; ?>">
                    </div>
                </div>
                
                <div class="terms-agreement">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a></label>
                </div>
                
                <div class="terms-agreement">
                    <input type="checkbox" id="newsletter">
                    <label for="newsletter">Send me news and exclusive offers from CRIMSON CELLAR</label>
                </div>
                
                <button type="submit" class="register-btn">Create Account</button>
                
                <div class="divider">
                    <span>Already have an account?</span>
                </div>
                
                <div class="login-link">
                    <a href="login.php">Sign in to your account</a>
                </div>
            </form>
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>

</body>
</html>
