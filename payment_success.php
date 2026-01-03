<?php
include_once 'conn_db.php';

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$stmt = $conn->prepare("SELECT o.*, u.first_name, u.last_name, u.email 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.user_id 
                       WHERE o.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Fetch order items
$items_stmt = $conn->prepare("SELECT oi.*, p.name, p.label_url 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.product_id 
                             WHERE oi.order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$items_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Payment Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="register-success-container">
    <div class="register-success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <h1 class="register-success-title">Payment Successful!</h1>
    <p class="register-success-message">Thank you for your order. Your payment has been processed successfully.</p>
    
    <div class="register-user-info">
        <h3>Order Details</h3>
        <div class="register-info-grid">
            <div class="register-info-item">
                <span class="register-info-label">Order Number:</span>
                <span>#<?php echo $order['order_id']; ?></span>
            </div>
            <div class="register-info-item">
                <span class="register-info-label">Order Date:</span>
                <span><?php echo date('F j, Y', strtotime($order['order_date'])); ?></span>
            </div>
            <div class="register-info-item">
                <span class="register-info-label">Total Amount:</span>
                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
            <div class="register-info-item">
                <span class="register-info-label">Status:</span>
                <span><?php echo $order['status']; ?></span>
            </div>
        </div>
    </div>
    
    <div class="next-steps">
        <h3>Order Items</h3>
        <div class="order-items-list">
            <?php foreach ($items as $item): ?>
                <div class="order-preview-item">
                    <img src="<?php echo htmlspecialchars($item['label_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         style="width: 50px; height: 50px; object-fit: contain; margin-right: 1rem;">
                    <div>
                        <div><?php echo htmlspecialchars($item['name']); ?></div>
                        <div>Quantity: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['subtotal'] / $item['quantity'], 2); ?></div>
                    </div>
                    <div style="margin-left: auto; font-weight: bold;">
                        $<?php echo number_format($item['subtotal'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="register-action-buttons">
        <a href="member.php?tab=orders" class="success-btn success-btn-outline">View Order History</a>
        <a href="shop.php" class="success-btn success-btn-outline">Continue Shopping</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>