<?php
session_start();
include 'P3.php';

if (!isset($_SESSION['sid'])) {
    header('Location: index.php');
    exit();
}

$msg = '';

if (isset($_POST['btn'])) {
    $product_name = trim($_POST['p_name'] ?? '');
    $product_price = trim($_POST['p_price'] ?? '');
    $product_description = trim($_POST['p_description'] ?? '');
    $category_id = filter_var($_POST['c_id'] ?? '', FILTER_VALIDATE_INT);
    $photo_name = $_FILES['p_photo']['name'] ?? '';
    $photo_tmp_name = $_FILES['p_photo']['tmp_name'] ?? '';
    $photo_error = $_FILES['p_photo']['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($product_name === '' || $product_price === '' || $product_description === '' || !$category_id || $category_id < 1) {
        $msg = 'Please fill all the fields with valid values!';
    } elseif ($photo_error !== UPLOAD_ERR_OK) {
        $msg = 'Please choose a product photo!';
    } else {
        $folder = 'img/';
        if (!is_dir($folder) && !mkdir($folder, 0777, true) && !is_dir($folder)) {
            $msg = 'Unable to create the product image folder.';
        } else {
            $check = mysqli_prepare($cn, 'SELECT p_id FROM product WHERE p_name = ? AND p_price = ? AND p_description = ? AND c_id = ?');
            mysqli_stmt_bind_param($check, 'sssi', $product_name, $product_price, $product_description, $category_id);
            mysqli_stmt_execute($check);
            $exact_exists = mysqli_stmt_get_result($check)->num_rows > 0;
            mysqli_stmt_close($check);

            if ($exact_exists) {
                $msg = 'This exact product already exists!';
            } else {
                $new_image_hash = md5_file($photo_tmp_name);
                $all_products = mysqli_query($cn, 'SELECT p_photo FROM product');
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
                    $extension = pathinfo($photo_name, PATHINFO_EXTENSION);
                    $final_photo = time() . '_' . bin2hex(random_bytes(4)) . ($extension !== '' ? '.' . $extension : '');
                    $photo_path = $folder . $final_photo;

                    if (!move_uploaded_file($photo_tmp_name, $photo_path)) {
                        $msg = 'Failed to upload image!';
                    } else {
                        $insert = mysqli_prepare($cn, 'INSERT INTO product (p_name, p_price, p_photo, p_description, c_id) VALUES (?, ?, ?, ?, ?)');
                        mysqli_stmt_bind_param($insert, 'ssssi', $product_name, $product_price, $final_photo, $product_description, $category_id);

                        if (mysqli_stmt_execute($insert)) {
                            $_SESSION['product_message'] = 'Product inserted successfully!';
                            header('Location: view-product.php');
                            exit();
                        }

                        unlink($photo_path);
                        $msg = 'Database insert failed.';
                        mysqli_stmt_close($insert);
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
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
        .btn { width: 100%; padding: 12px 14px; border: none; border-radius: 8px; background: #2563eb; color: #fff; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="page-wrap">
    <section class="card">
        <h2>Insert Product</h2>
        <?php if ($msg !== '') { ?>
            <div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="p_name">Product Name</label>
                <input id="p_name" type="text" name="p_name" value="<?php echo htmlspecialchars($_POST['p_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="p_price">Price</label>
                <input id="p_price" type="text" name="p_price" value="<?php echo htmlspecialchars($_POST['p_price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="p_photo">Photo</label>
                <input id="p_photo" type="file" name="p_photo" accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            <div class="form-group">
                <label for="p_description">Description</label>
                <textarea id="p_description" name="p_description" required><?php echo htmlspecialchars($_POST['p_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="form-group">
                <label for="c_id">Category Number</label>
                <input id="c_id" type="number" name="c_id" value="<?php echo htmlspecialchars($_POST['c_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <button type="submit" name="btn" value="Insert" class="btn">Insert Product</button>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
