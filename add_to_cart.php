<?php
// Include class files FIRST
// init_cart.php already handles session_start()
require_once 'conn_db.php';
require_once 'init_cart.php';

// Restore cart from session or create new
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}
$cart = $_SESSION['cart'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);

    // Fetch product details from database
    $stmt = $conn->prepare("
        SELECT p.*, c.colour_name, s.style_name, co.country_name
        FROM products p
        LEFT JOIN colours c ON p.colour_id = c.colour_id
        LEFT JOIN styles s ON p.style_id = s.style_id
        LEFT JOIN countries co ON p.country_id = co.country_id
        WHERE p.product_id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $product = new Product(
            $row['product_id'],
            $row['name'],
            $row['colour_name'],
            $row['style_name'],
            $row['country_name'],
            $row['price'],
            $qty,
            $row['image_url'],
            $row['label_url']
        );
        $cart->add_product($product);
        $_SESSION['cart'] = $cart;
    }
    $stmt->close();
    $conn->close();
}

// Redirect to cart page after adding product
header("Location: cart.php");
exit();