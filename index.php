<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRIMSON CELLAR - Premium Wines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>Discover Premium Wines</h1>
            <p>Explore our carefully curated collection of exceptional wines from around the world</p>
            <a href="#products" class="btn">Explore Wines</a>
        </div>
    </section>

    <!-- Products -->
    <section class="index-container" id="products">
        <h2 class="index-section-title">Featured Wines</h2>
        <div class="index-products">
            <div class="index-product-card">
                <a href="product_detail.php?id=7" class="product-link">
                    <img src="image/wine/Chateau_Lafite_Rothschild_2015/label.png" alt="Château Lafite Rothschild 2015" loading="lazy">
                    <div class="index-product-card-info">
                        <h3>Château Lafite Rothschild 2015</h3>
                        <p>A rich, full-bodied red with notes of dark fruit and oak</p>
                        <span class="price">$900.00</span>
                    </div>
                </a>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="7">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="index-add-to-cart">Add to Cart</button>
                </form>
            </div>
            

            <div class="index-product-card">
                <a href="product_detail.php?id=11" class="product-link">
                    <img src="image/wine/Domaine_William_Chabil_Grand_Cru_Les_Clos_2019/label.png" alt="Domaine William Fevre Les Clos 2019" loading="lazy">
                    <div class="index-product-card-info">
                        <h3>Domaine William Fevre Les Clos 2019</h3>
                        <p>A crisp, refreshing white with citrus and floral notes</p>
                        <span class="price">$120.00</span>
                    </div>
                </a>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="11">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="index-add-to-cart">Add to Cart</button>
                </form>
            </div>
            

            <div class="index-product-card">
                <a href="product_detail.php?id=6" class="product-link">
                    <img src="image/wine/Champagne_Dom_Perignon_Vintage_2012/label.png" alt="Champagne Dom Pérignon 2012" loading="lazy">
                    <div class="index-product-card-info">
                        <h3>Champagne Dom Pérignon 2012</h3>
                        <p>A delicate rosé with hints of strawberry and peach</p>
                        <span class="price">$280.00</span>
                    </div>
                </a>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="6">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="index-add-to-cart">Add to Cart</button>
                </form>
            </div>

        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>
</html>