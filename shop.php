<?php
require_once 'conn_db.php';
require_once 'init_cart.php';

// --- Filters: read from GET ---
$filter_year_from = isset($_GET['year_from']) ? trim($_GET['year_from']) : '';
$filter_year_to   = isset($_GET['year_to']) ? trim($_GET['year_to']) : '';
$filter_colour_id = isset($_GET['colour_id']) ? trim($_GET['colour_id']) : '';
$filter_style_id  = isset($_GET['style_id']) ? trim($_GET['style_id']) : '';
$filter_country_id = isset($_GET['country_id']) ? trim($_GET['country_id']) : '';
$filter_price_min = isset($_GET['price_min']) ? trim($_GET['price_min']) : '';
$filter_price_max = isset($_GET['price_max']) ? trim($_GET['price_max']) : '';

// --- Build products query with optional WHERE conditions ---
$query = "
SELECT p.*, c.colour_name, s.style_name, co.country_name
FROM products p
LEFT JOIN colours c ON p.colour_id = c.colour_id
LEFT JOIN styles s ON p.style_id = s.style_id
LEFT JOIN countries co ON p.country_id = co.country_id
";

$conditions = [];
$params = [];
$types = '';

if ($filter_year_from !== '') {
    $conditions[] = "p.vintage >= ?";
    $params[] = (int)$filter_year_from;
    $types .= 'i';
}

if ($filter_year_to !== '') {
    $conditions[] = "p.vintage <= ?";
    $params[] = (int)$filter_year_to;
    $types .= 'i';
}

if ($filter_colour_id !== '') {
    $conditions[] = "p.colour_id = ?";
    $params[] = (int)$filter_colour_id;
    $types .= 'i';
}

if ($filter_style_id !== '') {
    $conditions[] = "p.style_id = ?";
    $params[] = (int)$filter_style_id;
    $types .= 'i';
}

if ($filter_country_id !== '') {
    $conditions[] = "p.country_id = ?";
    $params[] = (int)$filter_country_id;
    $types .= 'i';
}

if ($filter_price_min !== '') {
    $conditions[] = "p.price >= ?";
    $params[] = (float)$filter_price_min;
    $types .= 'd';
}

if ($filter_price_max !== '') {
    $conditions[] = "p.price <= ?";
    $params[] = (float)$filter_price_max;
    $types .= 'd';
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get attributes for filter dropdowns
$countriesQuery = "SELECT country_id, country_name FROM countries ORDER BY country_name";
$countriesResult = $conn->query($countriesQuery);
$countries = [];
while ($row = $countriesResult->fetch_assoc()) {
    $countries[] = $row;
}

$coloursQuery = "SELECT colour_id, colour_name FROM colours ORDER BY colour_name";
$coloursResult = $conn->query($coloursQuery);
$colours = [];
while ($row = $coloursResult->fetch_assoc()) {
    $colours[] = $row;
}

$stylesQuery = "SELECT style_id, style_name FROM styles ORDER BY style_name";
$stylesResult = $conn->query($stylesQuery);
$styles = [];
while ($row = $stylesResult->fetch_assoc()) {
    $styles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Shopping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Shopping Content -->
    <div class="shop-container">
        <div class="shop-header">
            <h1 class="shop-title">Our Wine Collection</h1>
            <p class="shop-subtitle">Discover our carefully curated selection of premium wines from around the world</p>
        </div>

        <div class="shop-content">
            <!-- Filters Sidebar -->
            <aside class="shop-filters-sidebar">
                <h2 class="filters-title">Filters</h2>
                <form method="get" class="filters-form">
                    <div class="filter-group">
                        <label for="year_from">Year</label>
                        <div class="filter-range">
                            <input type="number" name="year_from" id="year_from" placeholder="From" min="1900" max="2100"
                                   value="<?php echo htmlspecialchars($filter_year_from); ?>">
                            <span>–</span>
                            <input type="number" name="year_to" id="year_to" placeholder="To" min="1900" max="2100"
                                   value="<?php echo htmlspecialchars($filter_year_to); ?>">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="colour_id">Colour</label>
                        <select name="colour_id" id="colour_id">
                            <option value="">All</option>
                            <?php foreach ($colours as $colour): ?>
                                <option value="<?php echo $colour['colour_id']; ?>" <?php echo ($filter_colour_id === (string)$colour['colour_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($colour['colour_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="style_id">Style</label>
                        <select name="style_id" id="style_id">
                            <option value="">All</option>
                            <?php foreach ($styles as $style): ?>
                                <option value="<?php echo $style['style_id']; ?>" <?php echo ($filter_style_id === (string)$style['style_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($style['style_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="country_id">Origin</label>
                        <select name="country_id" id="country_id">
                            <option value="">All</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country['country_id']; ?>" <?php echo ($filter_country_id === (string)$country['country_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country['country_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="price_min">Price</label>
                        <div class="filter-range">
                            <input type="number" step="0.01" name="price_min" id="price_min" placeholder="Min"
                                   value="<?php echo htmlspecialchars($filter_price_min); ?>">
                            <span>–</span>
                            <input type="number" step="0.01" name="price_max" id="price_max" placeholder="Max"
                                   value="<?php echo htmlspecialchars($filter_price_max); ?>">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn filter-btn">Apply Filters</button>
                        <a href="shop.php" class="btn filter-reset-btn">Reset</a>
                    </div>
                </form>
            </aside>

            <!-- Products Section -->
            <main class="products-section">                
                <div class="products-grid" id="products-grid">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="product-card" 
                                data-type="<?php echo htmlspecialchars($row['colour_name']); ?>"
                                data-country="<?php echo htmlspecialchars($row['country_name']); ?>"
                                data-price="<?php echo htmlspecialchars($row['price']); ?>">
                                <div class="product-card-image">
                                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                        alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <div class="product-card-info">
                                    <a href="product_detail.php?id=<?php echo urlencode($row['product_id']); ?>">
                                        <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                                        <div class="product-category">
                                            <?php echo htmlspecialchars($row['colour_name']); ?> • 
                                            <?php echo htmlspecialchars($row['style_name']); ?> • 
                                            <?php echo htmlspecialchars($row['country_name']); ?>
                                        </div>
                                        <div class="product-price">$<?php echo number_format($row['price'], 2); ?></div>
                                    </a>
                                    <!-- Add to Cart form -->
                                    <form method="post" action="add_to_cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </div>
            </main>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
