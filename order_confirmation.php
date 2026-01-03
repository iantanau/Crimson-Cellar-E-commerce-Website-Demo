<?php
include_once 'conn_db.php';
require_once 'init_cart.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if cart is empty
if ($cart->get_total_items() <= 0) {
    header("Location: cart.php");
    exit();
}

// Get user information
$user_email = $_SESSION['email'];
$user_query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

// Calculate totals
$subtotal = $cart->get_total_price();
$shipping = 12.99;
$gst = $subtotal * 0.1;
$total = $subtotal + $shipping;

// Handle form submission to proceed to payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_payment'])) {
    // Save order summary to session for payment page
    $_SESSION['order_summary'] = [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'gst' => $gst,
        'total' => $total,
        'item_count' => $cart->get_total_items()
    ];
    
    header("Location: payment.php");
    exit();
}

// Handle back to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['back_to_cart'])) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Order Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="order-confirm-container">
    <div class="order-confirm-header">
        <h1 class="order-confirm-title">Order Confirmation</h1>
        <p class="order-confirm-subtitle">Review your order before proceeding to payment</p>
    </div>

    <div class="order-confirm-content">
        <!-- Order Summary Section -->
        <div class="order-summary-section">
            <h2 class="order-section-title">Order Details</h2>
            
            <div class="order-items-list">
                <?php foreach ($cart->get_products() as $product): ?>
                    <div class="order-item-confirm">
                        <div class="order-item-image">
                            <img src="<?php echo htmlspecialchars($product->get_label()); ?>" 
                                 alt="<?php echo htmlspecialchars($product->get_name()); ?>">
                        </div>
                        <div class="order-item-details">
                            <div class="order-item-name"><?php echo htmlspecialchars($product->get_name()); ?></div>
                            <div class="order-item-meta">
                                <?php echo htmlspecialchars($product->get_colour()); ?> • 
                                <?php echo htmlspecialchars($product->get_style()); ?>
                            </div>
                            <div class="order-item-price">
                                $<?php echo number_format($product->get_price(), 2); ?> × <?php echo $product->get_qty(); ?>
                            </div>
                        </div>
                        <div class="order-item-total">
                            $<?php echo number_format($product->get_price() * $product->get_qty(), 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-totals-breakdown">
                <div class="totals-row">
                    <span class="totals-label">Subtotal</span>
                    <span class="totals-value">$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="totals-row">
                    <span class="totals-label">GST (10%, included in subtotal)</span>
                    <span class="totals-value">$<?php echo number_format($gst, 2); ?></span>
                </div>
                <div class="totals-row">
                    <span class="totals-label">Shipping</span>
                    <span class="totals-value">$<?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="totals-row totals-total">
                    <span class="totals-label">Total Amount</span>
                    <span class="totals-value">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Shipping & Billing Information -->
        <div class="payment-shipping-section">
            <!-- Shipping Information -->
            <div class="info-card">
                <div class="info-card-title">
                    <span>Shipping Method</span>
                </div>
                <div class="info-details-grid">
                    <div class="info-detail-row">
                        <span class="info-detail-label">Method:</span>
                        <span class="info-detail-value">Standard Shipping</span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Delivery Time:</span>
                        <span class="info-detail-value">3-5 business days</span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Shipping Cost:</span>
                        <span class="info-detail-value">$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                </div>
            </div>
                    
            <!-- Shipping Address -->
            <div class="info-card">
                <div class="info-card-title">
                    <span>Shipping Address</span>
                    <a href="profile_edit.php" class="edit-info-link">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="info-details-grid">
                    <div class="info-detail-row">
                        <span class="info-detail-label">Full Name:</span>
                        <span class="info-detail-value"><?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?></span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Street Address:</span>
                        <span class="info-detail-value">
                            <?php echo htmlspecialchars($user_data['street_address'] ?? ''); ?>
                        </span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Suburb:</span>
                        <span class="info-detail-value">
                            <?php echo htmlspecialchars($user_data['suburb'] ?? ''); ?>
                        </span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">State:</span>
                        <span class="info-detail-value">
                            <?php echo htmlspecialchars($user_data['state'] ?? ''); ?>
                        </span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Postal Code:</span>
                        <span class="info-detail-value">
                            <?php echo htmlspecialchars($user_data['postal_code'] ?? ''); ?>
                        </span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Email:</span>
                        <span class="info-detail-value"><?php echo htmlspecialchars($user_data['email']); ?></span>
                    </div>
                    <div class="info-detail-row">
                        <span class="info-detail-label">Phone:</span>
                        <span class="info-detail-value"><?php echo htmlspecialchars($user_data['phone'] ?? 'Not provided'); ?></span>
                    </div>
                </div>
            </div>            

        </div>
    </div>

    <!-- Action Buttons -->
    <form method="post" class="order-confirm-actions">
        <button type="submit" name="back_to_cart" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Cart
        </button>
        
        <button type="submit" name="proceed_to_payment" class="submit-order-btn">
            <i class="fas fa-lock"></i> Proceed to Payment
        </button>
    </form>

<?php include 'footer.php'; ?>

</body>
</html>