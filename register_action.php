<?php
// Start session for error message
require_once 'conn_db.php';

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim(preg_replace('/[^a-zA-Z]/', '',$_POST["firstName"])));
    $lastName = htmlspecialchars(trim(preg_replace('/[^a-zA-Z]/', '',$_POST["lastName"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $phone = trim(preg_replace('/[^0-9\s\-\+\(\)]/','',$_POST["phone"]));
    $address = htmlspecialchars(trim($_POST["address"]));
    $suburb = htmlspecialchars(trim($_POST["suburb"]));
    $state = htmlspecialchars(trim($_POST["state"]));
    $postal = htmlspecialchars(trim($_POST["postalCode"]));

    $_SESSION['form_data'] = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'suburb' => $suburb,
        'state' => $state,
        'postal' => $postal
    ];

    // ======================
    // Validation
    // ======================

    // 1. No blank fields
    if (
        empty($firstName) || empty($lastName) || empty($email) ||
        empty($password) || empty($confirmPassword) || empty($phone)
    ) {
        $_SESSION['error'] = "All required fields must be filled.";
        header("Location: register.php");
        exit();
    }

    // 2. Passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // 3. Telephone numeric check
    if (!is_numeric($phone)) {
        $_SESSION['error'] = "Phone number must be numeric.";
        header("Location: register.php");
        exit();
    }

    // 4. Email format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address format.";
        header("Location: register.php");
        exit();
    }

    // 5. Unique email check
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email address already registered.";
        $check->close();
        header("Location: register.php");
        exit();
    }
    $check->close();

    // 6. Postal code numeric check (if provided)
    if (!empty($postal) && !is_numeric($postal)) {
        $_SESSION['error'] = "Postal code must be numeric.";
        header("Location: register.php");
        exit();
    }

    // ======================
    // Insert into DB
    // ======================

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert with separate address fields
    $sql = "INSERT INTO users (first_name, last_name, email, password, phone, street_address, suburb, state, postal_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $hashedPassword, $phone, $address, $suburb, $state, $postal);

    if ($stmt->execute()) {
        ?>

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

            <!-- Success Content -->
            <div class="register-success-container">
                <div class="register-success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                
                <h1 class="register-success-title">Registration Successful!</h1>
                
                <p class="register-success-message">Thank you for joining CRIMSON CELLAR. Your account has been created successfully.</p>
                
                <div class="register-user-info">
                    <h3>Your Account Details:</h3>
                    <div class="register-info-grid">
                        <div class="register-info-item">
                            <span class="register-info-label">Name:</span>
                            <?php echo $firstName . ' ' . $lastName; ?>
                        </div>
                        <div class="register-info-item">
                            <span class="register-info-label">Email:</span> 
                            <?php echo $email; ?>
                        </div>
                        <div class="register-info-item">
                            <span class="register-info-label">Phone:</span> 
                            <?php echo $phone; ?>
                        </div>
                        <div class="register-info-item">
                            <span class="register-info-label">Address:</span> 
                            <?php 
                            $fullAddress = trim($address . ", " . $suburb . ", " . $state . " " . $postal, ", ");
                            echo $fullAddress; 
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="next-steps">
                    <h3>What would you like to do next?</h3>
                    
                    <div class="register-action-buttons">
                        <a href="shop.php" class="success-btn success-btn-outline">Start Shopping</a>
                        <a href="index.php" class="success-btn success-btn-outline">Go to Homepage</a>
                        <a href="login.php" class="success-btn success-btn-outline">Login Account</a>
                    </div>
                </div>
                
                <p>We've sent a confirmation email to your address. Please check your inbox to verify your email.</p>
            </div>

            <!-- footer -->
            <?php include 'footer.php'; ?>

        <?php
        unset($_SESSION['form_data']);

        $stmt->close();
        $conn->close();
    
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: register.php");
        exit();
    }
}
?>