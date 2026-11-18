<?php
session_start();
include 'P3.php';

if (!isset($_SESSION['sid'])) {
    header("Location: index.php");
    exit();
}

$msg = "";

// Handle Delete Category
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM category WHERE c_id = $delete_id";
    
    if (mysqli_query($cn, $delete_sql)) {
        header("Location: view.php");
        exit();
    } else {
        $msg = "Error deleting category: " . mysqli_error($cn);
    }
}

// Fetch all categories for view only
$sql = "SELECT c_id, c_name FROM category ORDER BY c_id ASC";
$result = mysqli_query($cn, $sql);

if (!$result) {
    die("Database Query Failed: " . mysqli_error($cn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #edf4ff; color: #111827; }
        .page-wrap { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .card { background: #ffffff; border: 1px solid #dfe3e8; border-radius: 14px; box-shadow: 0 12px 28px rgba(37, 99, 235, 0.08); padding: 30px; }
        h2 { margin: 0 0 20px 0; text-align: center; font-size: 26px; }
        .msg { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 600; border: 1px solid #fecaca; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dfe3e8; padding: 12px 14px; text-align: left; vertical-align: middle; }
        th { background: #eaf2ff; color: #1f2937; }
        tr:nth-child(even) { background: #f9fbff; }
        .empty { padding: 18px; text-align: center; color: #6b7280; border: 1px solid #dfe3e8; border-radius: 8px; background: #f9fbff; }
        .btn { padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 5px; cursor: pointer; border: none; }
        .btn-edit { background-color: #3b82f6; color: white; }
        .btn-edit:hover { background-color: #2563eb; }
        .btn-delete { background-color: #ef4444; color: white; }
        .btn-delete:hover { background-color: #dc2626; }
        /* Buttons side-by-side container */
        .action-btns { display: flex; gap: 8px; align-items: center; }
    </style>
</head>
<body>

<?php if(file_exists('header.php')) { include 'header.php'; } ?>

<div class="page-wrap">
    <div class="card">
        <h2>View Categories</h2>

        <?php if (!empty($msg)) { ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <tr>
                    <th style="width: 140px;">Category ID</th>
                    <th>Category Name</th>
                    <th style="width: 180px;">Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['c_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['c_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <div class="action-btns">
                                <!-- Edit Action Path -->
                                <a href="insert.php?edit_id=<?php echo $row['c_id']; ?>" class="btn btn-edit">Edit</a>
                                
                                <!-- Delete Action Path -->
                                <a href="insert.php?delete_id=<?php echo $row['c_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div class="empty">No categories found.</div>
        <?php } ?>
    </div>
</div>

<?php if(file_exists('footer.php')) { include 'footer.php'; } ?>

</body>
</html>