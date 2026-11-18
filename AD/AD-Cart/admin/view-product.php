<?php
session_start();
include 'P3.php';

if (!isset($_SESSION['sid'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM product ORDER BY p_id ASC";
$result = mysqli_query($cn, $sql);
$msg = $_SESSION['product_message'] ?? '';
unset($_SESSION['product_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #eaeded; color: #0f1111; }
        .page-wrap { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        h2 { margin: 0 0 20px; text-align: center; font-size: 28px; color: #0f1111; }
        
        .product-grid { display: flex; flex-direction: column; gap: 15px; }
        .product-card {
            background: #ffffff; border: 1px solid #e7e7e7; border-radius: 8px;
            padding: 15px; display: flex; flex-direction: row; align-items: center;
            justify-content: space-between; gap: 20px; transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08); }
        .img-container { width: 150px; height: 150px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #fff; }
        .product-image { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-info { flex: 1; display: flex; flex-direction: column; }
        .category-badge { font-size: 11px; color: #565959; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .product-title { font-size: 18px; font-weight: 700; line-height: 1.3; color: #0f1111; margin: 0 0 6px 0; }
        .product-desc { font-size: 13px; color: #565959; margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .price-tag { font-size: 20px; font-weight: 700; color: #B12704; }
        .actions { display: flex; flex-direction: column; gap: 8px; width: 120px; flex-shrink: 0; }
        .action-link, .delete-button {
            width: 100%; padding: 9px 0; border: 0; border-radius: 6px; color: #fff;
            text-decoration: none; font-size: 13px; font-weight: 600; cursor: pointer; text-align: center;
        }
        .action-link { background: #2563eb; }
        .delete-button { background: #dc2626; }
        .action-link:hover { background: #1d4ed8; }
        .delete-button:hover { background: #b91c1c; }
        .msg { margin-bottom: 16px; padding: 12px 14px; border: 1px solid #cfe0ff; border-radius: 8px; background: #eaf2ff; color: #1d4ed8; text-align: center; }
        .empty { padding: 40px; text-align: center; color: #565959; background: #fff; border-radius: 8px; }

        @media (max-width: 600px) {
            .product-card { flex-direction: column; align-items: flex-start; }
            .img-container { width: 100%; height: 180px; }
            .actions { width: 100%; flex-direction: row; }
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-wrap">
    <h2>View Products</h2>
    <?php if ($msg !== '') { ?>
        <div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>
    
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <div class="product-grid">
            <?php while ($product = mysqli_fetch_assoc($result)) { ?>
                <div class="product-card">
                    <div class="img-container">
                        <img class="product-image" src="img/<?php echo rawurlencode($product['p_photo']); ?>" alt="<?php echo htmlspecialchars($product['p_name'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    
                    <div class="product-info">
                        <div class="category-badge">Category #<?php echo htmlspecialchars($product['c_id'] ?? '1', ENT_QUOTES, 'UTF-8'); ?></div>
                        <h3 class="product-title"><?php echo htmlspecialchars($product['p_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="product-desc"><?php echo htmlspecialchars($product['p_description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="price-tag">₹<?php echo htmlspecialchars($product['p_price'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>

                    <div class="actions">
                        <a class="action-link" href="edit-product.php?id=<?php echo (int)$product['p_id']; ?>">Edit</a>
                        <form method="post" action="delete-product.php" onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="id" value="<?php echo (int)$product['p_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="delete-button" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="empty">No products found.</div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>