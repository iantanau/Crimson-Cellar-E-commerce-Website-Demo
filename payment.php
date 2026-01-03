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

// Calculate totals
$subtotal = $cart->get_total_price();
$shipping = 12.99;
$gst = $subtotal * 0.1;
$total = $subtotal + $shipping;
$_SESSION['total_price'] = $total;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">Payment</h1>
        <p>Complete your order with secure payment</p>
    </div>

    <div class="cart-content">
        <div class="cart-items">
            <div class="payment-form-container">
                <h2 class="form-title">Payment Options</h2>
                
                <div class="payment-methods">
                    <div class="payment-method active" data-method="paypal">
                        <i class="fab fa-paypal"></i>
                        <span>PayPal</span>
                    </div>
                    <div class="payment-method" data-method="credit-card">
                        <i class="fas fa-credit-card"></i>
                        <span>Credit Card</span>
                    </div>
                </div>

                <!-- PayPal Payment -->
                <div id="paypal-section" class="payment-section active">
                    <p class="payment-description">
                        Test Transaction - Secure payment with PayPal
                    </p>
                    <p class="total-amount">
                        Total Amount: <strong>$<?php echo number_format($total, 2); ?></strong>
                    </p>
                    <input type="hidden" id="total_price" value="<?php echo $total; ?>" />
                    <p class="terms-note">*Terms and Conditions Apply</p>
                    
                    <div id="smart-button-container">
                        <div style="text-align: center;">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Credit Card Payment -->
                <div id="credit-card-section" class="payment-section">
                    <form method="post" action="process_payment.php" class="credit-card-form">
                        <input type="hidden" name="payment_method" value="credit_card">
                        
                        <div class="form-group">
                            <label for="card_name" class="form-label">Name on Card</label>
                            <input type="text" id="card_name" name="card_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" id="card_number" name="card_number" class="form-input" 
                                   placeholder="1234 5678 9012 3456" required maxlength="19">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="text" id="expiry_date" name="expiry_date" class="form-input" 
                                       placeholder="MM/YY" required maxlength="5">
                            </div>
                            
                            <div class="form-group">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" id="cvv" name="cvv" class="form-input" 
                                       placeholder="123" required maxlength="3">
                            </div>
                        </div>
                        
                        <button type="submit" class="checkout-btn">Pay Now $<?php echo number_format($total, 2); ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="cart-summary">
            <h2 class="summary-title">Order Summary</h2>
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">$<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">GST(included)</span>
                <span class="summary-value">$<?php echo number_format($gst, 2); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Shipping</span>
                <span class="summary-value">$<?php echo number_format($shipping, 2); ?></span>
            </div>
            <div class="summary-total">
                <span class="total-label">Total</span>
                <span class="total-value">$<?php echo number_format($total, 2); ?></span>
            </div>
            
            <div class="order-items-preview">
                <h3 class="summary-title" style="font-size: 1.1rem; margin-top: 1.5rem;">Order Items</h3>
                <?php foreach ($cart->get_products() as $product): ?>
                    <div class="order-preview-item">
                        <span><?php echo htmlspecialchars($product->get_name()); ?> × <?php echo $product->get_qty(); ?></span>
                        <span>$<?php echo number_format($product->get_price() * $product->get_qty(), 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=Aaw7hQBgH2HAc-Bi0iAvQ6UY2lUb4pfEstjUYKh6oV9Q2V6tmmFJklK5uq_Gi0oaiFAiyJaF6PcC3G5L&enable-funding=venmo&currency=AUD" data-sdk-integration-source="button-factory"></script>

<script>
// Switch payment methods
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        // Remove active class from all methods and sections
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
        document.querySelectorAll('.payment-section').forEach(s => s.classList.remove('active'));
        
        // Add active class to selected method and corresponding section
        this.classList.add('active');
        const methodType = this.getAttribute('data-method');
        document.getElementById(methodType + '-section').classList.add('active');
    });
});

// PayPal Button Integration
function initPayPalButton() {
    paypal.Buttons({
        style: {
            shape: 'rect',
            color: 'gold',
            layout: 'vertical',
            label: 'paypal',
        },

        createOrder: function(data, actions) {
            var total_price = document.getElementById("total_price").value;
            return actions.order.create({
                purchase_units: [{"amount":{"currency_code":"AUD","value":total_price}}]
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {
                // Redirect to process_payment.php with order details
                window.location.href = "process_payment.php?payment_method=paypal&order_id=" + orderData.id;
            });
        },

        onError: function(err) {
            console.log(err);
            const element = document.getElementById('paypal-button-container');
            element.innerHTML = '<div class="error-message">Unable to process PayPal payment. Please try another method.</div>';
        }
    }).render('#paypal-button-container');
}

// Format card number input
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) value = value.slice(0, 16);
    
    let formatted = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) formatted += ' ';
        formatted += value[i];
    }
    
    e.target.value = formatted;
});

// Format expiry date input
document.getElementById('expiry_date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 4) value = value.slice(0, 4);
    
    if (value.length > 2) {
        e.target.value = value.slice(0, 2) + '/' + value.slice(2);
    } else {
        e.target.value = value;
    }
});

// Limit CVV input to digits only
document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

// Initialize PayPal button
initPayPalButton();
</script>

</body>
</html>