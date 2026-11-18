<?php
// ==========================================================
// LOGIN PAGE
// This page allows a user to log in.
// Step-by-step flow:
// 1. Read email and password from form
// 2. Search user in database
// 3. Verify password using password_verify()
// 4. Save user info in session
// 5. Redirect to home page
// ==========================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "ayush_hotel";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS `form` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$check_col = $conn->query("SHOW COLUMNS FROM `form` LIKE 'user_id'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE `form` ADD COLUMN `user_id` INT AUTO_INCREMENT PRIMARY KEY FIRST");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT user_id, name, password, role FROM form WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password! Please try again.";
            }
        } else {
            $error = "No account found with this email address.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ayush Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-link { transition: color 0.3s ease, transform 0.2s ease; }
        .nav-link:hover { color: #ffc107 !important; transform: translateY(-1px); }
        .card { border: none; border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 12px 25px rgba(0,0,0,0.1) !important; }
        .btn { transition: all 0.25s ease-in-out; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 12px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">✨ Ayush Hotel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'index.php') ? 'active fw-semibold text-warning' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'rooms.php') ? 'active fw-semibold text-warning' : ''; ?>" href="rooms.php">Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'more.php') ? 'active fw-semibold text-warning' : ''; ?>" href="more.php">More Options</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <a href="login.php" class="btn btn-outline-light btn-sm me-2 active">Login</a>
                <a href="register.php" class="btn btn-primary btn-sm px-3">Register</a>
            </div>
        </div>
    </div>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Welcome Back</h3>
                    <p class="text-muted small">Please sign in to continue to Ayush Hotel</p>
                </div>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                        <?= $error; ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">Email Address</label>
                        <input type="email" class="form-control form-control-sm py-2" id="email" name="email" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label fw-semibold small">Password</label>
                        </div>
                        <input type="password" class="form-control form-control-sm py-2" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm py-2 fw-semibold">Sign In</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p class="text-muted small mb-0">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-semibold">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>