<?php
session_start();
include 'P3.php';

if (!isset($_SESSION['sid'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-product.php');
    exit();
}

$product_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$product_id || $product_id < 1) {
    $_SESSION['product_message'] = 'Invalid product selected.';
    header('Location: view-product.php');
    exit();
}

$find = mysqli_prepare($cn, 'SELECT p_photo FROM product WHERE p_id = ?');
mysqli_stmt_bind_param($find, 'i', $product_id);
mysqli_stmt_execute($find);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($find));
mysqli_stmt_close($find);

if (!$product) {
    $_SESSION['product_message'] = 'Product not found.';
    header('Location: view-product.php');
    exit();
}

$delete = mysqli_prepare($cn, 'DELETE FROM product WHERE p_id = ?');
mysqli_stmt_bind_param($delete, 'i', $product_id);

if (mysqli_stmt_execute($delete)) {
    $photo_path = 'img/' . basename($product['p_photo']);
    if (is_file($photo_path)) {
        unlink($photo_path);
    }
    $_SESSION['product_message'] = 'Product deleted successfully.';
} else {
    $_SESSION['product_message'] = 'Product could not be deleted.';
}

mysqli_stmt_close($delete);
header('Location: view-product.php');
exit();
