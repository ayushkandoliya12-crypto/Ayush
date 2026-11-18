<?php
// 1. Session start karein agar pehle se start nahi hai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection aur Header file include karein
include "P3.php";
include "header.php";

// 2. URL se Product ID nikalna (Agar ID nahi mili toh 0 maan lo)
$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

// Database se us product ka data nikalna
$result = mysqli_query($cn, "SELECT * FROM product WHERE p_id = $id");
$row = mysqli_fetch_assoc($result);

// 3. User ka login email check karna (Customer session ya Admin session se)
$userEmail = $_SESSION['customer_email'] ?? $_SESSION['email'] ?? '';
$Admin1 = isset($_SESSION['sid']);
$orderMessage = '';$orderMessageType = '';

// Agar email khali hai lekin Admin logged in hai, toh admin ka email fetch kar lo
if ($userEmail === '' && isset($_SESSION['sid'])) {
    $sid = (int)$_SESSION['sid'];
    $userResult = mysqli_query($cn, "SELECT a_email FROM admin WHERE a_id = $sid");

    if ($userResult && ($userRecord = mysqli_fetch_assoc($userResult))) {
        $userEmail =$userRecord['a_email'];
        $_SESSION['email'] =$userEmail;
    }
}

// ==========================================
// 4. BUY NOW / ORDER PLACEMENT LOGIC
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $productId = (int)($_POST['p_id'] ?? 0);

    // Agar user logged in nahi hai, toh use login page par bhej do
    if ($userEmail === '') {
        $redirectUrl = 'login.php?redirect=' . urlencode('view_detaile.php?id=' . $productId);
        header('Location: ' . $redirectUrl);
        exit();
    }

    // Product ID check karein
    if ($productId <= 0) {
        $orderMessage = 'Invalid product.';$orderMessageType = 'error';
    } else {
        // Database mein order insert karne ki query (Prepared Statement)
        $orderStatement = mysqli_prepare($cn, 'INSERT INTO `order` (p1_id, u_id) VALUES (?, ?)');

        if ($orderStatement) {
            mysqli_stmt_bind_param($orderStatement, 'is', $productId,$userEmail);

            if (mysqli_stmt_execute($orderStatement)) {
                $orderMessage = 'Order placed completed.';$orderMessageType = 'success';
            } else {
                $orderMessage = 'Order could not be placed.';$orderMessageType = 'error';
            }

            mysqli_stmt_close($orderStatement);
        } else {
            $orderMessage = 'Order could not be placed.';$orderMessageType = 'error';
        }
    }
}
?>

<style>
    /* Styling for Product Details Page */
    .detail-box {
        width: 82%;
        max-width: 820px;
        margin: 30px auto;
        padding: 22px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
    }
    .detail-image {
        width: 42%;
        height: 300px;
        object-fit: contain;
        float: left;
        margin-right: 25px;
        background: #f8fafc;
    }
    .detail-info {
        min-height: 300px;
        padding: 16px;
    }
    .detail-name {
        color: #1e293b;
        font-size: 28px;
        margin-top: 10px;
    }
    .detail-price {
        color: #b12704;
        font-size: 22px;
        font-weight: bold;
    }
    .detail-description {
        color: #64748b;
        font-size: 15px;
        line-height: 1.6;
    }
    .detail-button {
        display: inline-block;
        margin-top: 14px;
        padding: 10px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }
    .back-arrow {
        display: block;
        width: 82%;
        max-width: 820px;
        margin: 25px auto 0;
        color: #2563eb;
        text-decoration: none;
        font-weight: bold;
    }
    .back-arrow:hover {
        color: #1d4ed8;
    }
    .buy-now {
        background: #ffd814;
        color: #131921;
    }
    .buy-message {
        margin-top: 14px;
        color: #15803d;
        font-weight: bold;
    }
    .buy-error {
        color: #b91c1c;
    }
    .not-found {
        width: 90%;
        max-width: 600px;
        margin: 80px auto;
        padding: 30px;
        text-align: center;
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
    }
    @media (max-width: 600px) {
        .detail-image {
            width: 100%;
            float: none;
            margin-right: 0;
        }
        .detail-info {
            padding: 10px 0;
        }
    }
</style>

<?php 
// 5. Check karein ki product database mein mil gaya ya nahi
if ($row) { 
?>
    <!-- Back Link -->
    <a href="index.php" class="back-arrow">&larr; Back to Products</a>

    <div class="detail-box">
        <!-- Product Image -->
        <img src="admin/img/<?php echo $row['p_photo']; ?>" class="detail-image" alt="Product Image">

        <div class="detail-info">
            <!-- Product Name -->
            <h1 class="detail-name"><?php echo $row['p_name']; ?></h1>

            <!-- Product Price -->
            <p class="detail-price">₹<?php echo $row['p_price']; ?></p>

            <!-- Product Description -->
            <p class="detail-description"><?php echo $row['p_description']; ?></p>

            <!-- Order Status Message Display -->
            <?php if ($orderMessage !== '') { ?>
                <p class="buy-message <?php echo $orderMessageType === 'error' ? 'buy-error' : ''; ?>">
                    <?php echo htmlspecialchars($orderMessage, ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <!-- Agar order successfully place ho gaya toh Alert dikhakar index.php par bhej do -->
                <?php if ($orderMessageType === 'success') { ?>
                    <script>
                        alert('Order placed completed.');
                        window.location.href = 'index.php';
                    </script>
                <?php } ?>
            <?php } ?>

            <!-- Buy Now Form -->
            <form id="buy-now-form" method="post" action="<?php echo ($userEmail !== '') ? 'view_detaile.php?id=' . (int)$row['p_id'] : 'login.php?redirect=' . urlencode('view_detaile.php?id=' . (int)$row['p_id']); ?>">
                <input type="hidden" name="p_id" value="<?php echo (int)$row['p_id']; ?>">
                <br>
                <button type="submit" name="buy_now" value="1" class="detail-button buy-now">
                    Buy Now
                </button>
            </form>

            <!-- Agar user logged out hai toh click karne par warning alert dena -->
            <?php if ($userEmail === '') { ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const buyForm = document.getElementById('buy-now-form');
                        if (buyForm) {
                            buyForm.addEventListener('submit', function () {
                                alert('Please login first to buy this product.');
                            });
                        }
                    });
                </script>
            <?php } ?>
        </div>
    </div>

<?php 
} else { 
?>
    <!-- Agar Product nahi mila toh yeh error box dikhega -->
    <div class="not-found">
        <h2>Product not found</h2>
        <p>This product is not available.</p>
        <a href="index.php" class="detail-button">Back to Products</a>
    </div>
<?php 
} 
?>

<?php 
// Footer include karna
include "footer.php"; 
?>