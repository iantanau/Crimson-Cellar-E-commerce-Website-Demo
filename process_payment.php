<?php
include_once 'conn_db.php';
require_once 'init_cart.php';
require_once 'gen_id.php';

// Check if user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if cart is empty
if ($cart->get_total_items() <= 0) {
    header("Location: cart.php");
    exit();
}

$payment_method = $_GET['payment_method'] ?? $_POST['payment_method'] ?? '';
$transaction_id = ''; // Store a fake transaction reference
$masked_card = '';    // Store only last 4 digits

// Handle Payment Simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $payment_method === 'credit_card') {
       
    $card_number = $_POST['card_number'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $card_name = $_POST['card_name'] ?? '';
    
    // 1. Basic Validation
    if (empty($card_number) || empty($expiry_date) || empty($cvv) || empty($card_name)) {
        $_SESSION['error'] = "Please fill all required fields";
        header("Location: payment.php");
        exit();
    }

    // 2. Format Validation (Simple Simulation)
    // Remove spaces and check if numeric
    $clean_card = str_replace(' ', '', $card_number);
    if (!is_numeric($clean_card) || strlen($clean_card) < 13) {
        $_SESSION['error'] = "Invalid card number format";
        header("Location: payment.php");
        exit();
    }

    // 3. SECURITY STEP: Masking
    // We only keep the last 4 digits for receipt display. Never store the full number.
    $masked_card = '**** **** **** ' . substr($clean_card, -4);

    // 4. SECURITY STEP: Simulate Payment Gateway Response
    // In a real app, we would send $card_number to Stripe/PayPal API here.
    // They would return a Transaction ID. We assume success and generate a mock ID.
    $transaction_id = 'TXN-' . strtoupper(bin2hex(random_bytes(8)));

    // 5. SECURITY STEP: Immediate Cleanup
    // Explicitly destroy raw sensitive data from memory
    unset($card_number);
    unset($cvv);
    unset($_POST['card_number']);
    unset($_POST['cvv']);
} 
else if ($payment_method === 'paypal') {
    // Simulate PayPal Transaction ID
    $transaction_id = 'PAYPAL-' . strtoupper(bin2hex(random_bytes(8)));
}

// Save order to database
try {
    $order_id = gen_id(8);
    $user_id = $_SESSION['user_id'];
    // Logic: If session total exists use it, otherwise calculate + simulated tax/shipping
    $total_amount = $_SESSION['total_price'] ?? $cart->get_total_price() + 12.99 + ($cart->get_total_price() * 0.1);
    
    $shipping_street_address = $_SESSION['street_address'] ?? '';
    $shipping_suburb = $_SESSION['suburb'] ?? '';
    $shipping_state = $_SESSION['state'] ?? '';
    $shipping_postal_code = $_SESSION['postal_code'] ?? '';

    // IMPROVEMENT: Prepare SQL - Note we DO NOT save card details
    // If you have a column for 'transaction_ref' in your DB, save $transaction_id there.
    // If not, we just save the standard info.
    $stmt = $conn->prepare("INSERT INTO orders (
        order_id, user_id, total_amount, 
        shipping_street_address, shipping_suburb, shipping_state, shipping_postal_code, 
        payment_method, payment_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Paid')");

    $stmt->bind_param("sidsssss", 
        $order_id, $user_id, $total_amount, 
        $shipping_street_address, $shipping_suburb, $shipping_state, $shipping_postal_code, 
        $payment_method
    );
    
    $stmt->execute();
    $db_order_id = $order_id; // Using the generated string ID

    // Insert Order Items
    foreach ($cart->get_products() as $product) {
        $product_id = $product->get_id();
        $quantity = $product->get_qty();
        $subtotal = $product->get_price() * $quantity;
        
        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        $item_stmt->bind_param("siid", $db_order_id, $product_id, $quantity, $subtotal); // Note: Changed first type to 's' if order_id is string
        $item_stmt->execute();
        $item_stmt->close();
        
        // Stock update logic (Uncomment if your DB supports it)
        /*
        $update_stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
        $update_stmt->bind_param("ii", $quantity, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
        */
    }
    
    // Clear cart
    $cart->clear();
    $_SESSION['cart'] = $cart;
    
    // Redirect to success page (Pass the Masked Card info purely for display if needed)
    header("Location: payment_success.php?order_id=" . $db_order_id);
    exit();
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = "Error processing your order. Please try again.";
    header("Location: payment.php");
    exit();
}
?>