<?php 
// 1. Database Connection & Session Start
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$cn = mysqli_connect($servername, $username, $password, $dbname);

if (!$cn) {
    die("<div style='background:red; color:white; padding:10px;'>Database Connection Failed: " . mysqli_connect_error() . "</div>");
}

session_start();
$_SESSION['spandan'] = 'ok';

// 2. Header Include (Safe check)
if (file_exists('include/header.php')) {
    include 'include/header.php';
} elseif (file_exists('header.php')) {
    include 'header.php';
}

// 3. Get Search Query from URL (Checking both GET and POST)
$scr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : (isset($_REQUEST['scr']) ? trim($_REQUEST['scr']) : '');
?>
    
    <section id="advertisement">
        <div class="container" style="text-align: center; margin: 20px 0;">
            <img src="images/shop/advertisement.jpg" alt="" style="max-width: 100%; height: auto;" />
        </div>
    </section>
    
    <section>
        <div class="page-wrap">
            <div style="margin-bottom: 15px;">
                <a href="index.php" style="display: inline-block; color: #0f172a; text-decoration: none; font-weight: 600;">
                    ← Back to Products
                </a>
            </div>
            <h2>Searched Products</h2>

            <?php
            if ($scr === '') {
                echo '
                <div style="max-width: 600px; margin: 20px auto; background: #fff3cd; color: #664d03; padding: 14px 18px; border-radius: 8px; border: 1px solid #ffecb5; text-align: center;">
                    Please enter something in the search box.
                </div>
                ';
            } else {
                $scr_safe = mysqli_real_escape_string($cn, $scr);

                // SQL Query
                $sql = "SELECT * FROM product WHERE p_name LIKE '%$scr_safe%' OR p_description LIKE '%$scr_safe%'";

                $rest = mysqli_query($cn, $sql);

                if (!$rest) {
                    echo "<div style='color:red;'>SQL Error: " . mysqli_error($cn) . "</div>";
                } else {
                    $num_results = mysqli_num_rows($rest);

                    if ($num_results > 0) {
                        ?>
                        <div class="product-grid">
                            <?php while ($rect = mysqli_fetch_assoc($rest)) { ?>
                                <div class="product-card">
                                    <div class="img-container">
                                        <?php $product_image = (!empty($rect['p_photo'])) ? 'admin/img/' . rawurlencode($rect['p_photo']) : 'images/no-image.png'; ?>
                                        <img src="<?php echo htmlspecialchars($product_image, ENT_QUOTES, 'UTF-8'); ?>" class="imgheight" alt="<?php echo htmlspecialchars($rect['p_name'] ?? 'Product image', ENT_QUOTES, 'UTF-8'); ?>" />
                                    </div>

                                    <div class="category-badge">
                                        <?php echo htmlspecialchars($rect['p_name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>

                                    <h3 class="product-title">
                                        <?php echo htmlspecialchars($rect['p_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </h3>
+
                                    <div style="margin-bottom: 8px; font-size: 13px; color: #565959; line-height: 1.4; min-height: 42px;">
                                        <?php echo htmlspecialchars(substr($rect['p_description'] ?? '', 0, 75), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>

                                    <div class="price-tag">
                                        ₹ <?php echo number_format((float)($rect['p_price'] ?? 0), 2); ?>
                                    </div>

                                    <div class="actions">
                                        <a href="view_detaile.php?id=<?php echo (int)($rect['p_id'] ?? 0); ?>" class="buy-button">
                                            <i class="fa fa-shopping-cart"></i> View Product
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    } else {
                        echo '
                        <div style="max-width: 600px; margin: 20px auto; background: #f8d7da; color: #842029; padding: 14px 18px; border-radius: 8px; border: 1px solid #f5c2c7; text-align: center;">
                            No Product Found matching "<strong>' . htmlspecialchars($scr, ENT_QUOTES, 'UTF-8') . '</strong>".
                        </div>
                        ';
                    }
                }
            }
            ?>
        </div>
    </section>

<style>
    .page-wrap { max-width: 1280px; margin: 30px auto; padding: 0 20px; flex: 1; width: 100%; }
    h2 { margin: 0 0 20px; text-align: center; font-size: 28px; color: #0f1111; }
    .product-grid { display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; }
    .product-card {
        background: #ffffff; border: 1px solid #e7e7e7; border-radius: 8px; padding: 20px;
        width: 270px; display: flex; flex-direction: column; justify-content: space-between;
    }
    .img-container { width: 100%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
    .imgheight { height: 220px; object-fit: contain; max-width: 100%; }
    .category-badge { font-size: 11px; color: #565959; text-transform: uppercase; margin-bottom: 5px; }
    .product-title { font-size: 16px; font-weight: 700; color: #0f1111; margin: 0 0 8px 0; }
    .price-tag { font-size: 20px; font-weight: 700; color: #B12704; margin-bottom: 15px; }
    .actions { display: flex; gap: 10px; }
    .buy-button {
        flex: 1; padding: 10px 0; border-radius: 6px; color: #0f1111;
        text-decoration: none; font-size: 14px; font-weight: 600; text-align: center;
        background: #FFD814; display: inline-block;
    }
</style>
    
<?php 
if (file_exists('include/footer.php')) {
    include 'include/footer.php';
} elseif (file_exists('footer.php')) {
    include 'footer.php';
}
?>