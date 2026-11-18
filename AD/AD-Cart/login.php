<?php
session_start();

$redirectTo = $_GET['redirect'] ?? 'index.php';
if (strpos($redirectTo, 'http://') === 0 || strpos($redirectTo, 'https://') === 0 || strpos($redirectTo, '//') === 0) {
    $redirectTo = 'index.php';
}

if (isset($_SESSION['customer_id'])) {
    header("Location: " . $redirectTo);
    exit();
}

if (isset($_SESSION['sid'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "project") or die("Database connection failed");
mysqli_set_charset($conn, "utf8");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

  

    if (empty($errors)) {
        $customerQuery = mysqli_query($conn, "SELECT * FROM customer WHERE c_email = '" . mysqli_real_escape_string($conn, $email) . "'");

        if (mysqli_num_rows($customerQuery) == 1) {
            $row = mysqli_fetch_assoc($customerQuery);

            if (password_verify($password, $row['c_password'])) {
                $_SESSION['customer_id'] = $row['c_id'];
                $_SESSION['customer_name'] = $row['c_name'];
                $_SESSION['customer_email'] = $row['c_email'];
                header("Location: " . $redirectTo);
                exit();
            } else {
                $errors[] = 'Incorrect password.';
            }
        } else {
            $adminQuery = mysqli_query($conn, "SELECT * FROM admin WHERE a_email = '" . mysqli_real_escape_string($conn, $email) . "'");

            if (mysqli_num_rows($adminQuery) == 1) {
                $adminRow = mysqli_fetch_assoc($adminQuery);

                if ($password === $adminRow['a_password']) {
                    $_SESSION['sid'] = $adminRow['a_id'];
                    $_SESSION['email'] = $adminRow['a_email'];
                    $_SESSION['role'] = 'admin';
                    header("Location: index.php");
                    exit();
                } else {
                    $errors[] = 'Incorrect password.';
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body {
            margin: 0;
            background: #eaeded;
            color: #0f1111;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
        }

        .site-header {
            background: #131921;
            color: #ffffff;
            padding: 12px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.8px;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .logo-accent {
            color: #ff9900;
        }

        .ad-logo {
            width: 60px;
            height: 42px;
            border-radius: 10px 4px 10px 4px;
            background: linear-gradient(145deg, #c8d3d2, #2a808f);
            color: #131921;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -4px;
            box-shadow: 0 4px 10px rgba(62, 181, 211, 0.3);
        }

        .auth-page {
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-shell {
            width: 100%;
            max-width: 430px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid #e7e7e7;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            padding: 34px 28px;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 44px;
            margin: 0 auto 14px;
            border-radius: 10px 4px 10px 4px;
            background: linear-gradient(145deg, #c8d3d2, #2a808f);
            color: #131921;
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -4px;
            box-shadow: 0 4px 10px rgba(62, 181, 211, 0.3);
        }

        .auth-title {
            margin: 0 0 8px;
            text-align: center;
            color: #0f1111;
            font-size: 2rem;
            font-weight: 700;
        }

        .auth-subtitle {
            margin: 0 0 22px;
            text-align: center;
            color: #565959;
            font-size: 0.96rem;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #b91c1c;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #0f1111;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 0 14px;
            margin-bottom: 18px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            border-radius: 12px;
            background: #f8fafc;
            color: #0f1111;
            font-size: 0.96rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #ff9900;
            box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.12);
            background: #ffffff;
        }

        .btn-primary {
            width: 100%;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffb347 0%, #ff9900 100%);
            color: #131921;
            font-size: 1rem;
            font-weight: 700;
            padding: 14px 16px;
            box-shadow: 0 8px 16px rgba(255, 153, 0, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #ffa41c 0%, #f28e00 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(255, 153, 0, 0.25);
        }

        .auth-footer {
            text-align: center;
            margin-top: 18px;
            color: #565959;
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: #131921;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: #ff9900;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <a href="index.php" class="logo">
                <span class="ad-logo" aria-label="AD logo">AD</span>
                <span><span class="logo-accent">Cart</span></span>
            </a>
        </div>
    </header>

    <div class="auth-page">
        <div class="auth-shell">
            <div class="auth-card">
                <div class="brand-badge">AD</div>
                <h2 class="auth-title">Login</h2>
                <p class="auth-subtitle">Welcome back to ADCart</p>

            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="POST" action="">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <div class="auth-footer">
                New here? <a href="register.php">Create new account</a>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
