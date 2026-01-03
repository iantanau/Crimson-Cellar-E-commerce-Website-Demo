<?php
include_once 'conn_db.php';
require_once 'init_cart.php';

// Handle form submissions for updating quantities or removing items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $product_id = $_POST['product_id'] ?? null;
        switch ($_POST['action']) {
            case 'update_qty':
                $qty = intval($_POST['qty']);
                // If quantity is 0 or less, remove the product
                if ($qty <= 0) {
                    $cart->remove_product($product_id);
                    $_SESSION['cart_message'] = 'Item removed from cart';
                } else {
                    $cart->update_product_qty($product_id, $qty);
                    $_SESSION['cart_message'] = 'Quantity updated';
                }
                break;
            case 'remove':
                $cart->remove_product($product_id);
                $_SESSION['cart_message'] = 'Item removed from cart';
                break;
        }
        $_SESSION['cart'] = $cart;

        header("Location: cart.php");
        exit();
    }
}

// Calculate totals
$subtotal = $cart->get_total_price();
$gst = $subtotal * 0.1;
$total = $subtotal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">
            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
            Your Shopping Cart
        </h1>
        <p class="cart-subtitle">
            <?php if ($cart->get_total_items() > 0): ?>
                <?php echo $cart->get_total_items(); ?> <?php echo $cart->get_total_items() == 1 ? 'item' : 'items'; ?> in your cart
            <?php else: ?>
                Review your items and proceed to checkout
            <?php endif; ?>
        </p>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['cart_message'])): ?>
        <div class="cart-message success" role="alert">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <span><?php echo htmlspecialchars($_SESSION['cart_message']); ?></span>
            <button class="message-close" aria-label="Close message">&times;</button>
        </div>
        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>

    <div class="cart-content <?php echo $cart->get_total_items() == 0 ? 'cart-empty' : ''; ?>">
        <?php if ($cart->get_total_items() > 0): ?>
            <div class="cart-items">
                <div class="cart-items-header">
                    <h2 class="cart-items-title">Items</h2>
                    <button type="button" class="clear-cart-btn" id="clearCartBtn">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i> Clear Cart
                    </button>
                </div>
                
                <div class="cart-items-list">
                    <?php foreach ($cart->get_products() as $product): 
                        $item_subtotal = $product->get_total_cost();
                    ?>
                        <div class="cart-item" data-product-id="<?php echo $product->get_id(); ?>">
                            <div class="cart-item-image">
                                <a href="product_detail.php?id=<?php echo $product->get_id(); ?>" aria-label="View <?php echo htmlspecialchars($product->get_name()); ?>">
                                    <img src="<?php echo htmlspecialchars($product->get_label()); ?>" 
                                        alt="<?php echo htmlspecialchars($product->get_name()); ?>" loading="lazy">
                                </a>
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-name">
                                    <a href="product_detail.php?id=<?php echo $product->get_id(); ?>">
                                        <?php echo htmlspecialchars($product->get_name()); ?>
                                    </a>
                                </h3>
                                <div class="cart-item-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-wine-glass" aria-hidden="true"></i>
                                        <?php echo htmlspecialchars($product->get_colour()); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <?php echo htmlspecialchars($product->get_style()); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-globe" aria-hidden="true"></i>
                                        <?php echo htmlspecialchars($product->get_country()); ?>
                                    </span>
                                </div>
                                <div class="cart-item-pricing">
                                    <span class="cart-item-unit-price">$<?php echo number_format($product->get_price(), 2); ?> each</span>
                                    <span class="cart-item-subtotal">
                                        Subtotal: <strong>$<?php echo number_format($item_subtotal, 2); ?></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="cart-item-controls">
                                <form method="post" class="cart-item-quantity" aria-label="Update quantity">
                                    <input type="hidden" name="action" value="update_qty">
                                    <input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
                                    <input type="hidden" name="qty" id="qty_<?php echo $product->get_id(); ?>" value="<?php echo $product->get_qty(); ?>">
                                    
                                    <label for="display_qty_<?php echo $product->get_id(); ?>" class="sr-only">Quantity</label>
                                    <div class="quantity-controls-wrapper">
                                        <button type="button" class="quantity-btn minus" 
                                                data-product-id="<?php echo $product->get_id(); ?>"
                                                aria-label="Decrease quantity">
                                            <i class="fas fa-minus" aria-hidden="true"></i>
                                        </button>
                                        <input type="number" 
                                               min="1" 
                                               max="99"
                                               class="quantity-input" 
                                               id="display_qty_<?php echo $product->get_id(); ?>" 
                                               name="qty" 
                                               value="<?php echo $product->get_qty(); ?>"
                                               aria-label="Quantity for <?php echo htmlspecialchars($product->get_name()); ?>">
                                        <button type="button" class="quantity-btn plus" 
                                                data-product-id="<?php echo $product->get_id(); ?>"
                                                aria-label="Increase quantity">
                                            <i class="fas fa-plus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <button type="submit" style="display:none;" id="submit_<?php echo $product->get_id(); ?>">Update</button>
                                </form>
                                <form method="post" class="remove-item-form">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
                                    <button type="submit" class="cart-item-remove" 
                                            data-product-name="<?php echo htmlspecialchars($product->get_name()); ?>"
                                            aria-label="Remove <?php echo htmlspecialchars($product->get_name()); ?> from cart">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                        <span class="remove-text">Remove</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                </div>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="shop.php" class="continue-shopping">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Start Shopping
                </a>
            </div>
        <?php endif; ?>

        <?php if ($cart->get_total_items() > 0): ?>
            <div class="cart-summary">
                <h2 class="summary-title">
                    <i class="fas fa-receipt" aria-hidden="true"></i>
                    Order Summary
                </h2>
                <div class="summary-content">
                    <div class="summary-row">
                        <span class="summary-label">Items (<?php echo $cart->get_total_items(); ?>)</span>
                        <span class="summary-value">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">GST (included)</span>
                        <span class="summary-value">$<?php echo number_format($gst, 2); ?></span>
                    </div>
                    <div class="summary-row summary-shipping">
                        <span class="summary-label">
                            <i class="fas fa-truck" aria-hidden="true"></i>
                            Shipping
                        </span>
                        <span class="summary-value">Calculated at checkout</span>
                    </div>
                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
                <div class="summary-actions">
                    <a href="order_confirmation.php" class="checkout-btn-link">
                        <button class="checkout-btn" type="button">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            Proceed to Confirmation
                        </button>
                    </a>
                    <a href="shop.php" class="continue-shopping">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Continue Shopping
                    </a>
                </div>
                <div class="cart-security-note">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Secure checkout • Your payment information is safe</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close message notification
    const messageClose = document.querySelector('.message-close');
    if (messageClose) {
        messageClose.addEventListener('click', function() {
            this.closest('.cart-message').style.display = 'none';
        });
        
        // Auto-hide message after 5 seconds
        setTimeout(() => {
            const message = document.querySelector('.cart-message');
            if (message) {
                message.style.opacity = '0';
                setTimeout(() => message.style.display = 'none', 300);
            }
        }, 5000);
    }

    // Quantity controls with debouncing
    let updateTimeout;
    const updateQuantity = (productId, newQty) => {
        const qtyInput = document.getElementById('qty_' + productId);
        const displayInput = document.getElementById('display_qty_' + productId);
        
        if (newQty < 1) newQty = 1;
        if (newQty > 99) newQty = 99;
        
        qtyInput.value = newQty;
        displayInput.value = newQty;
        
        // Debounce the update
        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(() => {
            document.getElementById('submit_' + productId).click();
        }, 500);
    };

    // Plus/Minus buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const displayInput = document.getElementById('display_qty_' + productId);
            let value = parseInt(displayInput.value) || 1;

            if (this.classList.contains('plus')) {
                value++;
            } else if (this.classList.contains('minus') && value > 1) {
                value--;
            }

            updateQuantity(productId, value);
        });
    });

    // Direct input changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.id.replace('display_qty_', '');
            const qty = parseInt(this.value) || 1;
            updateQuantity(productId, qty);
        });
        
        // Prevent invalid input
        input.addEventListener('keydown', function(e) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        });
    });

    // Remove item with confirmation
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const removeBtn = this.querySelector('.cart-item-remove');
            const productName = removeBtn.getAttribute('data-product-name') || 'this item';
            
            if (confirm(`Are you sure you want to remove "${productName}" from your cart?`)) {
                this.submit();
            }
        });
    });

    // Clear cart functionality
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
                // Remove all items
                const forms = document.querySelectorAll('.remove-item-form');
                forms.forEach((form, index) => {
                    setTimeout(() => {
                        form.querySelector('input[name="action"]').value = 'remove';
                        form.submit();
                    }, index * 100);
                });
            }
        });
    }

    // Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            }
        });
    });
});
</script>