<?php
session_start();
include 'P3.php';

if (!isset($_SESSION['sid'])) {
    header('Location: index.php');
    exit();
}

$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$product_id || $product_id < 1) {
    header('Location: view-product.php');
    exit();
}

$find = mysqli_prepare($cn, 'SELECT * FROM product WHERE p_id = ?');
mysqli_stmt_bind_param($find, 'i', $product_id);
mysqli_stmt_execute($find);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($find));
mysqli_stmt_close($find);

if (!$product) {
    header('Location: view-product.php');
    exit();
}

$msg = '';

if (isset($_POST['btn'])) {
    $product_name = trim($_POST['p_name'] ?? '');
    $product_price = trim($_POST['p_price'] ?? '');
    $product_description = trim($_POST['p_description'] ?? '');
    $category_id = filter_var($_POST['c_id'] ?? '', FILTER_VALIDATE_INT);
    $photo_error = $_FILES['p_photo']['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($product_name === '' || $product_price === '' || $product_description === '' || !$category_id || $category_id < 1) {
        $msg = 'Please fill all the fields with valid values!';
    } elseif ($photo_error !== UPLOAD_ERR_OK && $photo_error !== UPLOAD_ERR_NO_FILE) {
        $msg = 'The product photo could not be uploaded.';
    } else {
        $check = mysqli_prepare($cn, 'SELECT p_id FROM product WHERE p_name = ? AND p_price = ? AND p_description = ? AND c_id = ? AND p_id != ?');
        mysqli_stmt_bind_param($check, 'sssii', $product_name, $product_price, $product_description, $category_id, $product_id);
        mysqli_stmt_execute($check);
        $exact_exists = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);

        if ($exact_exists) {
            $msg = 'A product with these exact details already exists!';
        } else {
            $folder = 'img/';
            $final_photo = $product['p_photo'];
            $new_photo_path = null;

            if ($photo_error === UPLOAD_ERR_OK) {
                $photo_tmp_name = $_FILES['p_photo']['tmp_name'];
                $new_image_hash = md5_file($photo_tmp_name);
                $all_products = mysqli_query($cn, 'SELECT p_id, p_photo FROM product WHERE p_id != ' . (int)$product_id);
                $duplicate_photo = false;

                while ($row = mysqli_fetch_assoc($all_products)) {
                    $existing_photo_path = $folder . basename($row['p_photo']);
                    if (is_file($existing_photo_path) && md5_file($existing_photo_path) === $new_image_hash) {
                        $duplicate_photo = true;
                        break;
                    }
                }

                if ($duplicate_photo) {
                    $msg = 'This photo is already uploaded for another product!';
                } else {
                    $extension = pathinfo($_FILES['p_photo']['name'], PATHINFO_EXTENSION);
                    $final_photo = time() . '_' . bin2hex(random_bytes(4)) . ($extension !== '' ? '.' . $extension : '');
                    $new_photo_path = $folder . $final_photo;

                    if (!move_uploaded_file($photo_tmp_name, $new_photo_path)) {
                        $msg = 'Failed to upload new image!';
                        $new_photo_path = null;
                    }
                }
            }

            if ($msg === '') {
                $update = mysqli_prepare($cn, 'UPDATE product SET p_name = ?, p_price = ?, p_photo = ?, p_description = ?, c_id = ? WHERE p_id = ?');
                mysqli_stmt_bind_param($update, 'ssssii', $product_name, $product_price, $final_photo, $product_description, $category_id, $product_id);

                if (mysqli_stmt_execute($update)) {
                    mysqli_stmt_close($update);
                    $old_photo_path = $folder . basename($product['p_photo']);
                    if ($new_photo_path !== null && is_file($old_photo_path)) {
                        unlink($old_photo_path);
                    }
                    $_SESSION['product_message'] = 'Product updated successfully!';
                    header('Location: view-product.php');
                    exit();
                }

                mysqli_stmt_close($update);
                if ($new_photo_path !== null && is_file($new_photo_path)) {
                    unlink($new_photo_path);
                }
                $msg = 'Product update failed.';
            }
        }
    }
}

$form_product = [
    'p_name' => $_POST['p_name'] ?? $product['p_name'],
    'p_price' => $_POST['p_price'] ?? $product['p_price'],
    'p_description' => $_POST['p_description'] ?? $product['p_description'],
    'c_id' => $_POST['c_id'] ?? $product['c_id'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #edf4ff; color: #111827; }
        .page-wrap { max-width: 700px; margin: 60px auto; padding: 0 20px; }
        .card { background: #fff; border: 1px solid #dfe3e8; border-radius: 14px; box-shadow: 0 12px 28px rgba(37, 99, 235, .08); padding: 30px; }
        h2 { margin: 0 0 20px; text-align: center; font-size: 30px; }
        .msg { background: #eaf2ff; color: #1d4ed8; padding: 12px 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cfe0ff; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input, textarea { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 15px; }
        textarea { min-height: 110px; resize: vertical; }
        .current-image { width: 100px; height: 100px; object-fit: cover; display: block; margin-bottom: 10px; border-radius: 8px; border: 1px solid #dfe3e8; }
        .btn { width: 100%; padding: 12px 14px; border: none; border-radius: 8px; background: #2563eb; color: #fff; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .cancel { display: block; margin-top: 12px; color: #2563eb; text-align: center; text-decoration: none; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="page-wrap">
    <section class="card">
        <h2>Edit Product</h2>
        <?php if ($msg !== '') { ?>
            <div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="p_name">Product Name</label>
                <input id="p_name" type="text" name="p_name" value="<?php echo htmlspecialchars($form_product['p_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="p_price">Price</label>
                <input id="p_price" type="text" name="p_price" value="<?php echo htmlspecialchars($form_product['p_price'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="p_photo">Photo</label>
                <img class="current-image" src="img/<?php echo rawurlencode(basename($product['p_photo'])); ?>" alt="Current product photo">
                <input id="p_photo" type="file" name="p_photo" accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div class="form-group">
                <label for="p_description">Description</label>
                <textarea id="p_description" name="p_description" required><?php echo htmlspecialchars($form_product['p_description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="form-group">
                <label for="c_id">Category Number</label>
                <input id="c_id" type="number" name="c_id" value="<?php echo htmlspecialchars((string)$form_product['c_id'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <button type="submit" name="btn" value="Update" class="btn">Update Product</button>
            <a class="cancel" href="view-product.php">Cancel</a>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
