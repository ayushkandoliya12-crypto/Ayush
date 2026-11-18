<?php
// ==========================================================
// Beginner-Friendly Project File
// This file is included in many pages.
// Its job is to:
// 1. Start PHP session
// 2. Connect to MySQL database
// 3. Create required tables if not exist
// 4. Show common navigation menu for all pages
// ==========================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. MySQL Server se connect karein 
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "ayush_hotel";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// 2. Database agar nahi hai toh automatically banayein
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);

// 3. Essential tables agar nahi bane hain toh auto-create karein
$conn->query("CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(50) NOT NULL,
  `room_type` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `capacity` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Available',
  `description` TEXT,
  `image` TEXT
)");

$conn->query("CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `check_in` DATE NOT NULL,
  `check_out` DATE NOT NULL,
  `guests` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Booked',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$booking_status_check = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'status'");
if ($booking_status_check && $booking_status_check->num_rows == 0) {
    $conn->query("ALTER TABLE `bookings` ADD `status` VARCHAR(20) DEFAULT 'Booked' AFTER `total_amount`");
}

$conn->query("CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `booking_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_mode` VARCHAR(30) DEFAULT 'UPI',
  `payment_status` VARCHAR(20) DEFAULT 'Paid',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$payment_mode_check = $conn->query("SHOW COLUMNS FROM `payments` LIKE 'payment_mode'");
if ($payment_mode_check && $payment_mode_check->num_rows == 0) {
    $conn->query("ALTER TABLE `payments` ADD `payment_mode` VARCHAR(30) DEFAULT 'UPI' AFTER `amount`");
}

$payment_status_check = $conn->query("SHOW COLUMNS FROM `payments` LIKE 'payment_status'");
if ($payment_status_check && $payment_status_check->num_rows == 0) {
    $conn->query("ALTER TABLE `payments` ADD `payment_status` VARCHAR(20) DEFAULT 'Paid' AFTER `payment_mode`");
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ayush Hotel - Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1920&q=80');
            background-size: cover; background-position: center; color: white; padding: 90px 0; border-radius: 0 0 20px 20px; margin-bottom: 40px;
        }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-link { transition: color 0.3s ease, transform 0.2s ease; }
        .nav-link:hover { color: #ffc107 !important; transform: translateY(-1px); }
        .card { border: none; border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; }
        .card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,0.1) !important; }
        .room-img-container { overflow: hidden; }
        .room-img { height: 220px; width: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .card:hover .room-img { transform: scale(1.06); }
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
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'admin.php') ? 'active fw-semibold text-info' : 'text-info'; ?>" href="admin.php">Admin Panel</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="text-light me-3">Welcome, <strong><?= htmlspecialchars($_SESSION['name']); ?></strong></span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
                    <a href="register.php" class="btn btn-primary btn-sm px-3">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container pb-5">
