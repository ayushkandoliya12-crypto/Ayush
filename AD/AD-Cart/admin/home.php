<?php
session_start();

if (!isset($_SESSION['sid'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
} else {
    $userEmail = 'User';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopCentral | Home</title>
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
        background: #edf4ff;
        color: #111827;
        display: flex;
        flex-direction: column;
    }

    .page-wrap {
        max-width: 1100px;
        width: 100%;
        margin: 40px auto;
        padding: 0 20px;
        flex: 1;
    }

    .welcome-box {
        background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                    url('https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=1200&auto=format&fit=crop') no-repeat center center/cover;
        border: 1px solid #dfe3e8;
        border-radius: 14px;
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.15);
        padding: 60px 30px;
        text-align: center;
        min-height: 420px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #ffffff;
    }

    .welcome-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-radius: 999px;
        padding: 8px 14px;
        margin-bottom: 22px;
        backdrop-filter: blur(4px);
    }

    .welcome-box h1 {
        font-size: 56px;
        line-height: 1.1;
        color: #ffffff;
        margin-bottom: 18px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .welcome-box p {
        font-size: 20px;
        line-height: 1.6;
        color: #e5e7eb;
        margin-bottom: 28px;
        max-width: 700px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .quick-links {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .quick-links a {
        text-decoration: none;
        background: #2563eb;
        color: #ffffff;
        padding: 14px 24px;
        border-radius: 8px;
        border: 1px solid #2563eb;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .quick-links a:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .nav-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .main-nav,
        .header-actions {
            width: 100%;
        }

        .header-actions {
            justify-content: space-between;
        }

        .welcome-box {
            padding: 36px 20px;
            min-height: 320px;
        }

        .welcome-box h1 {
            font-size: 36px;
        }

        .welcome-box p {
            font-size: 18px;
        }
    }
</style>

</head>
<body>

<?php include 'header.php'; ?>

<main class="page-wrap">
    <div class="welcome-box">
        <span class="welcome-badge">Dashboard</span>
        <h1>Welcome back</h1>
        <p>Hello, <?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?>. Your admin panel is ready.</p>

        <div class="quick-links">
            <a href="home.php">Home</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>