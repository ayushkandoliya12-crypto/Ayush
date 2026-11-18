<?php
// Connect to the MySQL database named project
$conn = mysqli_connect("localhost", "root", "", "project") or die("Database connection failed: " . mysqli_connect_error());

mysqli_set_charset($conn, "utf8");

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $number = trim($_POST['number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name == '') {
        $errors[] = 'Name is required.';
    }
    if ($number == '' || !preg_match('/^[0-9]{10}$/', $number)) {
        $errors[] = 'Enter valid 10-digit number.';
    }
    if ($email == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter valid email.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT * FROM customer WHERE c_email = '" . mysqli_real_escape_string($conn, $email) . "'");

        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customer (c_name, c_number, c_email, c_password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $number, $email, $hash);

                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Registration successful!';
                    $name = $number = $email = '';
                    $password = '';
                } else {
                    $errors[] = 'Registration failed.';
                }
            } else {
                $errors[] = 'Database error.';
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
    <title>Register</title>
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

        .main-wrap {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid #e7e7e7;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            padding: 34px 28px;
        }

        .form-box {
            width: 100%;
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

        .form-box h2 {
            margin: 0 0 8px;
            color: #0f1111;
            font-size: 2rem;
            text-align: center;
            font-weight: 700;
        }

        .subtitle {
            text-align: center;
            color: #565959;
            margin-bottom: 22px;
            font-size: 0.96rem;
        }

        .error,
        .success {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
        }

        .success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            font-weight: 600;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #0f1111;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            height: 50px;
            padding: 0 14px;
            margin-bottom: 18px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            border-radius: 12px;
            background: #f8fafc;
            font-size: 0.96rem;
            outline: none;
            transition: all 0.2s ease;
        }

        input:focus {
            border-color: #ff9900;
            box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.12);
            background: #ffffff;
        }

        button {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffb347 0%, #ff9900 100%);
            color: #131921;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            margin-top: 8px;
            box-shadow: 0 8px 16px rgba(255, 153, 0, 0.22);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(255, 153, 0, 0.25);
            background: linear-gradient(135deg, #ffa41c 0%, #f28e00 100%);
        }

        .signin-link {
            text-align: center;
            margin-top: 20px;
            color: #565959;
            font-size: 0.9rem;
        }

        .signin-link a {
            color: #131921;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s ease;
        }

        .signin-link a:hover {
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
        <div class="main-wrap">
            <div class="form-box">
                <div class="brand-badge">AD</div>
                <h2>Register</h2>
                <div class="subtitle">Create your ADCart account</div>

            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" placeholder="Enter your name" required>

                <label for="number">Number</label>
                <input type="tel" id="number" name="number" value="<?= htmlspecialchars($number ?? '') ?>" placeholder="Enter your phone number" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="Enter your email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <button type="submit">Register</button>
            </form>

            <div class="signin-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>
