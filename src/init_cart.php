<?php
// init.php
require_once __DIR__ . '/product_cart_class.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}
$cart = $_SESSION['cart'];
?>