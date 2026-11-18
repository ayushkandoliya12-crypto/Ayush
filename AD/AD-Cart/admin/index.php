<?php
include "P3.php";
session_start();

$msg="";

if(isset($_POST['btn']))
{
    $email=$_POST['txt1'];
    $password=$_POST['txt2'];

    $sql="SELECT * FROM admin WHERE a_email='$email' AND a_password='$password'";
    $result=mysqli_query($cn,$sql);

    if(mysqli_num_rows($result)>0)
    {
        $record=mysqli_fetch_assoc($result);

        $_SESSION['sid']=$record['a_id'];
        $_SESSION['email']=$record['a_email'];

        header("Location:home.php");
        exit();
    }
    else
    {
        $msg="Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopCentral | Login</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }
    
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f3f4f6;
        padding: 20px;
    }
    
    .shop-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        padding: 32px 28px;
    }
    
    .brand-badge {
        width: 52px;
        height: 52px;
        background: #1f2937;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px auto;
        color: #ffffff;
        font-size: 22px;
    }
    
    .shop-card h2 {
        text-align: center;
        color: #111827;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .shop-card p.subtitle {
        text-align: center;
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 24px;
    }
    
    .input-group {
        margin-bottom: 18px;
    }
    
    .input-group label {
        display: block;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    
    .input-group input {
        width: 100%;
        padding: 12px 12px 12px 38px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        color: #111827;
        font-size: 15px;
        outline: none;
    }
    
    .input-group input::placeholder {
        color: #9ca3af;
    }
    
    .input-group input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }
    
    .btn {
        width: 100%;
        padding: 12px 14px;
        border: none;
        border-radius: 6px;
        background: #2563eb;
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 8px;
    }
    
    .btn:hover {
        background: #1d4ed8;
    }
    
    .error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 10px 12px;
        border-radius: 6px;
        text-align: center;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .extra-links {
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
        color: #6b7280;
    }
    
    .extra-links a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    
    .extra-links a:hover {
        text-decoration: underline;
    }
</style>
</head>

<body>

<div class="shop-card">
    <div class="brand-badge">
        <i class="fa-solid fa-bag-shopping"></i>
    </div>
    
    <h2>Welcome Back</h2>
    <p class="subtitle">Log in to access your cart and wishlist</p>

    <?php
    if($msg!="")
    {
        echo "<div class='error'><i class='fa-solid fa-circle-exclamation'></i> $msg</div>";
    }
    ?>

    <form method="post">
        <div class="input-group">
            <label>Email Address</label>
            <div class="input-wrapper">
                <input type="email" name="txt1" placeholder="name@shopping.com" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
        </div>

        <div class="input-group">
            <label>Password</label>
            <div class="input-wrapper">
                <input type="password" name="txt2" placeholder="••••••••" required>
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>

        <input type="submit" name="btn" value="Start Shopping" class="btn">
    </form>

    <div class="extra-links">
        Don't have an account? <a href="register.php">Create New Account</a>
    </div>
</div>

</body>
</html>