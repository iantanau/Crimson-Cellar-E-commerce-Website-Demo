<?php
require_once 'conn_db.php';

// Fetch product details based on product ID from query parameter
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $query = "
        SELECT p.*, c.colour_name, s.style_name, co.country_name
        FROM products p
        LEFT JOIN colours c ON p.colour_id = c.colour_id
        LEFT JOIN styles s ON p.style_id = s.style_id
        LEFT JOIN countries co ON p.country_id = co.country_id
        WHERE p.product_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Product not found.");
}

// Fetch related products, excluding the current product
$relatedQuery = "
    SELECT p.*, c.colour_name, s.style_name, co.country_name
    FROM products p
    LEFT JOIN colours c ON p.colour_id = c.colour_id
    LEFT JOIN styles s ON p.style_id = s.style_id
    LEFT JOIN countries co ON p.country_id = co.country_id
    WHERE p.product_id != ? 
      AND p.product_id BETWEEN 1 AND 15
    ORDER BY RAND()
    LIMIT 4
";
$relatedStmt = $conn->prepare($relatedQuery);
$relatedStmt->bind_param("i", $productId);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - CRIMSON CELLAR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Product Detail Section -->
    <div class="product-detail-container">
        
        <div class="product-main">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         id="main-product-image">
                </div>
            </div>
            
            <div class="product-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                
                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <div class="product-specs">
                    <h3>Details</h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span class="spec-label">Region</span>
                            <span class="spec-value"><?php echo htmlspecialchars($product['country_name']); ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Style</span>
                            <span class="spec-value"><?php echo htmlspecialchars($product['style_name']); ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Colour</span>
                            <span class="spec-value"><?php echo htmlspecialchars($product['colour_name']); ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Volume</span>
                            <span class="spec-value">750ml</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Serving Temperature</span>
                            <span class="spec-value">16-18°C</span>
                        </div>
                    </div>
                </div>
                
                <div class="purchase-options">
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        
                        <div class="quantity-selector">
                            <span class="quantity-label">Quantity:</span>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn minus" aria-label="Decrease Quantity">-</button>
                                <input type="number" class="quantity-input" name="qty" value="1" min="1">
                                <button type="button" class="quantity-btn plus" aria-label="Increase Quantity">+</button>
                            </div>
                        </div>
					
					<div class="action-buttons">
						<button class="add-to-cart-btn">
							<i class="fas fa-shopping-cart"></i>
							Add to Cart
						</button>
					</div>
                    </form>
				</div>
            </div>
        </div>
        
       <div class="product-tabs">
			<input type="radio" name="tabset" id="tab1" checked>
			<input type="radio" name="tabset" id="tab2">
			<input type="radio" name="tabset" id="tab3">

			<div class="tabs-header" role="tablist">
				<label class="tab-btn" for="tab1" role="tab" aria-controls="content1">Description</label>
				<label class="tab-btn" for="tab2" role="tab" aria-controls="content2">Tasting Notes</label>
				<label class="tab-btn" for="tab3" role="tab" aria-controls="content3">Food Pairing</label>
			</div>

			<div class="tab-content" id="content1" role="tabpanel">
				<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
			</div>

			<div class="tab-content" id="content2" role="tabpanel">
				<p><strong>Appearance:</strong> Deep ruby red with violet hues</p>
				<p><strong>Nose:</strong> Notes of fruit, oak, and spice</p>
				<p><strong>Palate:</strong> Balanced tannins and long finish</p>
			</div>

			<div class="tab-content" id="content3" role="tabpanel">
				<p>Pairs well with meats, cheese, and chocolate desserts.</p>
			</div>
		</div>
        
        <div class="related-products">
            <h2 class="related-title">You Might Also Like</h2>
            <div class="related-grid">
                <?php if ($relatedResult->num_rows > 0): ?>
                    <?php while ($rel = $relatedResult->fetch_assoc()): ?>
                        <div class="product-card">
                            <a href="product_detail.php?id=<?php echo $rel['product_id']; ?>">
                                <div class="product-card-image">
                                    <img src="<?php echo htmlspecialchars($rel['image_url']); ?>" 
                                        alt="<?php echo htmlspecialchars($rel['name']); ?>" >
                                </div>
                                <div class="product-card-info">
                                    <h3><?php echo htmlspecialchars($rel['name']); ?></h3>
                                    <p><?php echo htmlspecialchars($rel['colour_name']); ?> • 
                                    <?php echo htmlspecialchars($rel['style_name']); ?> • 
                                    <?php echo htmlspecialchars($rel['country_name']); ?></p>
                                    <span class="price">$<?php echo number_format($rel['price'], 2); ?></span>
                                    <form method="post" action="add_to_cart.php" class="add-to-cart-form">
                                        <input type="hidden" name="product_id" value="<?php echo $rel['product_id']; ?>">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="index-add-to-cart">Add to Cart</button>
                                    </form>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No related products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>

<script>
// function for + and -
document.querySelectorAll('.quantity-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.quantity-input');
        let value = parseInt(input.value) || 1;

        if (this.textContent === '+') {
            value++;
        } else if (this.textContent === '-' && value > 1) {
            value--;
        }

        input.value = value;
    });
});
</script>
