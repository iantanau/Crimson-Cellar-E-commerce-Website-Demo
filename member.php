<?php
// init_cart.php already handles session_start()
require_once 'init_cart.php';
require_once 'conn_db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch order history with product details
$sql = "
    SELECT 
        o.order_id, o.order_date, o.status, o.total_amount,
        oi.quantity, oi.subtotal,
        p.name AS product_name, p.price, p.label_url
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Organize orders by order ID
$orderHistory = [];
while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orderHistory[$orderId])) {
        $orderHistory[$orderId] = [
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'status' => $row['status'],
            'total_amount' => $row['total_amount'],
            'items' => []
        ];
    }
    $orderHistory[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'subtotal' => $row['subtotal'],
        'label_url' => $row['label_url']
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Account - CRIMSON CELLAR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- header -->
    <?php include 'header.php'; ?>

    <div class="member-container">
        <div class="member-header">
            <h1 class="member-title">My Account</h1>
            <p class="member-subtitle">Manage your profile, orders, and preferences</p>
        </div>
        
        <div class="member-content">
            <!-- Sidebar -->
            <div class="member-sidebar">
                <div class="member-profile">
                    <div class="member-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="member-name">
                        <?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?>
                    </h2>
                    <p class="member-email">
                        <?php echo $_SESSION['email']; ?>
                    </p>
                </div>
                
                <ul class="member-menu">
                    <li class="member-menu-item">
                        <a href="#profile" class="member-menu-link active">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="member-menu-item">
                        <a href="#orders" class="member-menu-link">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="member-main">
                <!-- Personal Info -->
                <div id="profile" class="member-section active">
                    <div class="section-header">
                        <h2 class="section-title">Profile Information</h2>
                        <button id="editProfileBtn" class="edit-profile-btn">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                                    
                    <div class="member-info-grid">
                        <div class="member-info-card">
                            <h3 class="member-info-card-title">Personal Details</h3>

                            <div class="member-info-item">
                                <span class="member-info-label">Name:</span>
                                <span>
                                    <?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">Email:</span>
                                <span>
                                    <?php echo $_SESSION['email']; ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">Phone:</span>
                                <span>
                                    <?php echo $_SESSION['phone']; ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">Street Address:</span>
                                <span>
                                    <?php echo htmlspecialchars($_SESSION['street_address'] ?? ''); ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">Suburb:</span>
                                <span>
                                    <?php echo htmlspecialchars($_SESSION['suburb'] ?? ''); ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">State:</span>
                                <span>
                                    <?php echo htmlspecialchars($_SESSION['state'] ?? ''); ?>
                                </span>
                            </div>
                            <div class="member-info-item">
                                <span class="member-info-label">Postal Code:</span>
                                <span>
                                    <?php echo htmlspecialchars($_SESSION['postal_code'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order History -->
                <div id="orders" class="member-section">
                    <h2 class="section-title">Order History</h2>

                    <?php if (empty($orderHistory)): ?>
                        <p>You have no orders yet.</p>
                    <?php else: ?>
                        <?php foreach ($orderHistory as $order): ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <span class="order-id">Order #<?php echo $order['order_id']; ?></span>
                                        <span class="order-date"> - <?php echo date("d M Y", strtotime($order['order_date'])); ?></span>
                                    </div>
                                    <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </div>

                                <div class="order-details">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="order-product">
                                            <img src="<?php echo $item['label_url']; ?>" 
                                                alt="Product" class="order-product-img">
                                            <div class="order-product-info">
                                                <div class="order-product-name"><?php echo $item['product_name']; ?></div>
                                                <div class="order-product-price">
                                                    $<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="order-total">Total: $<?php echo number_format($order['total_amount'], 2); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <script>
        // Tab Card Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuLinks = document.querySelectorAll('.member-menu-link');
            const sections = document.querySelectorAll('.member-section');

            function activateTab(tabId) {
                menuLinks.forEach(item => item.classList.remove('active'));
                sections.forEach(section => section.classList.remove('active'));

                const targetLink = document.querySelector(`.member-menu-link[href="#${tabId}"]`);
                const targetSection = document.getElementById(tabId);

                if (targetLink && targetSection) {
                    targetLink.classList.add('active');
                    targetSection.classList.add('active');
                }
            }

            // Add click event listeners to menu links
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    activateTab(targetId);
                });
            });

            // Activate tab based on URL parameter
            const params = new URLSearchParams(window.location.search);
            const tab = params.get("tab");
            if (tab) {
                activateTab(tab);
            }
        });

        document.getElementById('editProfileBtn').addEventListener('click', function() {
            window.location.href = 'profile_edit.php';
        });
    </script>
</body>
</html>