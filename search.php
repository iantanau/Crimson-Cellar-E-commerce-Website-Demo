<?php
require_once 'conn_db.php';
require_once 'init_cart.php';

// Obtain the search query from the URL parameter
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

// If the search query is empty, redirect to the homepage
if (empty($searchQuery)) {
    header("Location: index.php");
    exit();
}

// Prepare and execute the search query
$sql = "SELECT p.*, c.colour_name, s.style_name, co.country_name 
        FROM products p 
        LEFT JOIN colours c ON p.colour_id = c.colour_id 
        LEFT JOIN styles s ON p.style_id = s.style_id 
        LEFT JOIN countries co ON p.country_id = co.country_id 
        WHERE p.name LIKE ? OR p.description LIKE ? OR p.region LIKE ? OR c.colour_name LIKE ? OR co.country_name LIKE ?
        ORDER BY p.price DESC, p.name";

$stmt = $conn->prepare($sql);
$likeQuery = "%$searchQuery%";
$stmt->bind_param("sssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - <?php echo "Search: " . htmlspecialchars($searchQuery); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php';?>

    <div class="page-container">
        <div class="search-results-container">
            <div class="search-header">
                <h1 class="search-title">Search Results</h1>
                <div class="search-meta">
                    <p class="search-query">Results for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"</p>
                    <?php $resultsCount = count($products); ?>
                    <p class="results-count">
                        <?php echo $resultsCount; ?> product<?php echo $resultsCount !== 1 ? 's' : ''; ?> found
                    </p>
                </div>
            </div>

            <?php if (count($products) > 0): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" 
                            data-type="<?php echo htmlspecialchars($product['colour_name']); ?>"
                            data-country="<?php echo htmlspecialchars($product['country_name']); ?>"
                            data-price="<?php echo htmlspecialchars($product['price']); ?>">
                            
                            <div class="product-card-image">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-wine-bottle"></i>
                                        <span>Image not available</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="product-card-info">
                                <a href="product_detail.php?id=<?php echo urlencode($product['product_id']); ?>">
                                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <div class="product-category">
                                        <?php echo htmlspecialchars($product['colour_name']); ?> · 
                                        <?php echo htmlspecialchars($product['style_name']); ?>
                                        <?php echo htmlspecialchars($product['country_name']); ?>
                                    </div>
                                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                </a>

                                <!-- Add to Cart form -->
                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2>No products found</h2>
                    <p>We couldn't find any products matching "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"</p>
                    <p>Try adjusting your search terms or browse our <a href="shop.php" class="cta-link">full collection</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>