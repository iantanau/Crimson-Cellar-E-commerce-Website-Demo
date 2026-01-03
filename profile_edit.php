<?php
// init_cart.php already handles session_start()
require_once 'init_cart.php';
require_once 'conn_db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Deal with form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? '';
    // 注意：这里读取的是表单提交的 name 属性，保持驼峰没问题，因为下面 input name="firstName"
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $street_address = $_POST['street_address'] ?? '';
    $suburb = $_POST['suburb'] ?? '';
    $state = $_POST['state'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    
    // Update user information in the database
    // 数据库字段名是下划线分隔 (first_name, last_name)
    $sql = "UPDATE users SET first_name=?, last_name=?, phone=?, street_address=?, suburb=?, state=?, postal_code=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $firstName, $lastName, $phone, $street_address, $suburb, $state, $postal_code, $userId);
    
    if ($stmt->execute()) {
        // Update session variables (可选，保持与你原逻辑一致)
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['phone'] = $phone;
        
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: member.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile. Please try again.";
        // 建议重定向回当前页面而不是递归跳转
        header("Location: profile_edit.php");
        exit();
    }
    
    $stmt->close();
}

// Fetch current user data
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // $user 数组的键名将与数据库字段名一致 (first_name, last_name 等)
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CRIMSON CELLAR</title>
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
                <h1 class="register-title">Edit Profile</h1>
                <p class="register-subtitle">Update your personal information</p>
            </div>

            <!-- Show Error Message -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form class="register-form" name="editProfileForm" method="post" action="profile_edit.php">
                <div class="form-group">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="form-input" 
                    value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>" required> 
                </div>
                
                <div class="form-group">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Your last name" 
                    value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Your email address" 
                    value="<?= htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                    <small class="form-note">It cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input" placeholder="Your phone number" 
                    value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="street_address" class="form-label">Street Address</label>
                    <input type="text" id="street_address" name="street_address" class="form-input" placeholder="Your street address" 
                    value="<?= htmlspecialchars($user['street_address'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="suburb" class="form-label">Suburb</label>
                    <input type="text" id="suburb" name="suburb" class="form-input" placeholder="Your suburb" 
                    value="<?= htmlspecialchars($user['suburb'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state" class="form-label">State</label>
                        <select id="state" name="state" class="form-input" required>
                            <option value="">Select your state</option>
                            <option value="NSW" <?= (($user['state'] ?? '') == 'NSW') ? 'selected' : ''; ?>>New South Wales</option>
                            <option value="VIC" <?= (($user['state'] ?? '') == 'VIC') ? 'selected' : ''; ?>>Victoria</option>
                            <option value="ACT" <?= (($user['state'] ?? '') == 'ACT') ? 'selected' : ''; ?>>Australian Capital Territory</option>
                            <option value="SA"  <?= (($user['state'] ?? '') == 'SA') ? 'selected' :  ''; ?>>South Australia</option>
                            <option value="WA"  <?= (($user['state'] ?? '') == 'WA') ? 'selected' :  ''; ?>>Western Australia</option>
                            <option value="NT"  <?= (($user['state'] ?? '') == 'NT') ? 'selected' :  ''; ?>>Northern Territory</option>
                            <option value="TAS" <?= (($user['state'] ?? '') == 'TAS') ? 'selected' : ''; ?>>Tasmania</option>
                            <option value="QLD" <?= (($user['state'] ?? '') == 'QLD') ? 'selected' : ''; ?>>Queensland</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-input" placeholder="Postal code" 
                        value="<?= htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <button type="submit" class="register-btn">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>

</body>
</html>