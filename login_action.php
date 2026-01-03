<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Read form data
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
$inputPassword = trim($_POST["password"]);

if (($email == '') || ($inputPassword == '')) {
    $_SESSION['error'] = "Email and Password are required.";
    header("Location: login.php");
    exit();
}
else {

    // ======================
    // Check from DB
    // ======================

    // Database connection
    require_once('conn_db.php');

    // Create a select query to select user details using the email
    $query = "SELECT * FROM users WHERE email = ?";

    // Bind the parameters and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($inputPassword, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['firstName'] = $row['first_name'];
            $_SESSION['lastName'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['street_address'] = $row['street_address'] ?? '';
            $_SESSION['suburb'] = $row['suburb'] ?? '';
            $_SESSION['state'] = $row['state'] ?? '';
            $_SESSION['postal_code'] = $row['postal_code'] ?? '';
            // Keep backward compatibility with full address
            $_SESSION['address'] = trim(($row['street_address'] ?? '') . ", " . ($row['suburb'] ?? '') . ", " . ($row['state'] ?? '') . " " . ($row['postal_code'] ?? ''), ", ");

            // Close the connection
            $conn->close();

            // Redirect to member page
            header("Location: member.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }
}

?>