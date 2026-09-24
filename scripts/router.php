<?php

// Router for PHP's built-in development server.
// Usage: php -S localhost:8000 -t public public/router.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/' || $uri === '') {
    $script = 'index.php';
} else {
    $script = ltrim($uri, '/');
}

// Only route known PHP page handlers. Static files in public/ are served
// directly by the built-in server when the router returns false.
$allowed = [
    'about.php',
    'add_to_cart.php',
    'cart.php',
    'contact.php',
    'index.php',
    'login.php',
    'login_action.php',
    'logout.php',
    'member.php',
    'order_confirmation.php',
    'payment.php',
    'payment_success.php',
    'process_payment.php',
    'product_detail.php',
    'profile_edit.php',
    'register.php',
    'register_action.php',
    'search.php',
    'shop.php',
];

if (in_array($script, $allowed, true)) {
    require __DIR__ . '/../api/' . $script;
    return true;
}

return false;