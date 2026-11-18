<?php
// Session start karein taaki check kar sakein user login hai ya nahi
session_start();
include 'P3.php'; // Database connection file

// 1. Check karein ki user logged in hai ya nahi. Agar nahi, toh login page par bhej do.
if (!isset($_SESSION['sid'])) {
    header("Location: index.php");
    exit();
}

// Variables ko declare karna
$msg = "";
$edit_mode = false;
$edit_id = "";
$edit_name = "";
$current_page = basename($_SERVER['PHP_SELF']); // Yeh current page ka naam nikalta hai


// ==========================================
// 2. INSERT CATEGORY LOGIC
// ==========================================
if (isset($_POST['sb']) && $_POST['sb'] == 'Insert') {
    // Input se category name lena aur extra spaces hatana
    $catname = trim($_POST['catname']);

    // Validation: Agar field khali hai
    if ($catname == "") {
        $msg = "Please enter category name.";
    } else {
        // Check karein ki kya yeh category pehle se database mein hai ya nahi
        $check_query = "SELECT * FROM category WHERE c_name = '$catname'";
        $result = mysqli_query($cn, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $msg = "Category Name already taken! Please use a different name.";
        } else {
            // Agar category nayi hai, toh database mein insert kar do
            $insert_query = "INSERT INTO category (c_name) VALUES ('$catname')";
            mysqli_query($cn, $insert_query);

            // Success message set karke page redirect karein
            $_SESSION['success'] = "Data Inserted Successfully";
            header("Location: view-category.php");
            exit();
        }
    }
}


// ==========================================
// 3. UPDATE CATEGORY LOGIC
// ==========================================
if (isset($_POST['sb']) && $_POST['sb'] == 'Update') {
    $catid = $_POST['catid'];
    $catname = trim($_POST['catname']);

    // Check karein ki same naam ki doosri category pehle se na ho (current ID ko chhod kar)
    $check_update = "SELECT * FROM category WHERE c_name = '$catname' AND c_id != $catid";
    $result_update = mysqli_query($cn, $check_update);

    if (mysqli_num_rows($result_update) > 0) {
        $msg = "Category Name already taken!";
    } else {
        // Database update query
        $update_query = "UPDATE category SET c_name = '$catname' WHERE c_id = $catid";
        mysqli_query($cn, $update_query);

        $_SESSION['success'] = "Data Updated Successfully";
        header("Location: view-category.php");
        exit();
    }
}


// ==========================================
// 4. DELETE CATEGORY LOGIC
// ==========================================
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $delete_query = "DELETE FROM category WHERE c_id = $delete_id";
    if (mysqli_query($cn, $delete_query)) {
        $msg = "Category deleted successfully!";
    } else {
        $msg = "Error deleting category.";
    }
}


// ==========================================
// 5. EDIT CLICK LOGIC (Form me data load karne ke liye)
// ==========================================
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    
    $fetch_query = "SELECT * FROM category WHERE c_id = $edit_id";
    $fetch_result = mysqli_query($cn, $fetch_query);

    if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
        $edit_mode = true; // Form ab Edit mode mein chalega
        $row = mysqli_fetch_assoc($fetch_result);
        $edit_name = $row['c_name']; // Purana naam variable mein store kiya
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert / Update Category</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #edf4ff; color: #111827; }
        .page-wrap { max-width: 600px; margin: 40px auto; padding: 0 20px; }
        .card { background: #ffffff; border: 1px solid #dfe3e8; border-radius: 14px; box-shadow: 0 12px 28px rgba(37, 99, 235, 0.08); padding: 30px; margin-bottom: 25px; }
        h2 { margin: 0 0 20px 0; text-align: center; font-size: 26px; }
        .msg { background: #eaf2ff; color: #1d4ed8; padding: 12px 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cfe0ff; text-align: center; font-weight: 600; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input[type="text"] { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 15px; outline: none; }
        .btn { padding: 12px; font-size: 15px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 5px; cursor: pointer; border: none; width: 100%; }
        .btn-submit { background: #2563eb; color: white; }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-cancel { background-color: #64748b; color: white; margin-top: 10px; display: inline-block; text-align: center; text-decoration: none; }
        .btn-cancel:hover { background-color: #475569; }
    </style>
</head>
<body>

<?php 
// Header include karna agar file maujud hai
if (file_exists('header.php')) { 
    include 'header.php'; 
} 
?>

<div class="page-wrap">

    <!-- Error ya Success message dikhane ke liye -->
    <?php if (!empty($msg)) { ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php } ?>

    <div class="card">
        <?php if ($edit_mode) { ?>
            <!-- ================= EDIT FORM ================= -->
            <h2>Edit Category</h2>
            <form action="<?php echo $current_page; ?>" method="POST">
                <!-- Hidden input field jo category ID ko update ke waqt bhejega -->
                <input type="hidden" name="catid" value="<?php echo $edit_id; ?>">
                
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="catname" value="<?php echo htmlspecialchars($edit_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                
                <button type="submit" name="sb" value="Update" class="btn btn-submit">Update Category</button>
                <a href="<?php echo $current_page; ?>" class="btn btn-cancel">Cancel</a>
            </form>

        <?php } else { ?>
            <!-- ================= INSERT FORM ================= -->
            <h2>Insert Category</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="catname" placeholder="Enter category name" required>
                </div>
                
                <button type="submit" name="sb" value="Insert" class="btn btn-submit">Insert Category</button>
            </form>
        <?php } ?>
    </div>

</div>

<?php 
// Footer include karna agar file maujud hai
if (file_exists('footer.php')) { 
    include 'footer.php'; 
} 
?>

</body>
</html>