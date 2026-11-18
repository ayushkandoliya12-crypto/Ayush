<?php
// Session start karein taaki user ka data check ho sake
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PHP Logic: Check karein ki user logged-in hai ya nahi
if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
} else {
    // Agar login nahi hai, toh default 'User' show karein
    $userEmail = 'User';
}
?>

<style>
    /* Header Container Design */
    .site-header {
        background: linear-gradient(180deg, #f0f7ff 0%, #e3f0ff 100%);
        border-bottom: 1px solid #bfd7ff;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.08);
    }

    .nav-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    /* Logo / Page Title Section */
    .page-title {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #111827;
        font-weight: 700;
        font-size: 22px;
        letter-spacing: 0.3px;
    }

    .page-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1f2937 0%, #2563eb 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.18);
    }

    /* Navigation Links */
    .main-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-link,
    .dropdown-toggle {
        text-decoration: none;
        color: #1f2937;
        font-size: 14px;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .nav-link.active,
    .nav-link:hover,
    .dropdown-toggle:hover,
    .dropdown-toggle:focus {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.12);
    }

    /* Dropdown Menu Styling */
    .nav-dropdown {
        position: relative;
    }

    .nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        background: #ffffff;
        min-width: 180px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        display: none;
        z-index: 20;
    }

    /* Jab dropdown open ho */
    .nav-dropdown.open .nav-dropdown-menu {
        display: block;
    }

    .nav-dropdown-menu a {
        display: block;
        padding: 10px 12px;
        text-decoration: none;
        color: #1f2937;
        font-size: 14px;
        border-bottom: 1px solid #f3f4f6;
    }

    .nav-dropdown-menu a:last-child {
        border-bottom: none;
    }

    .nav-dropdown-menu a:hover {
        background: #f3f7ff;
        color: #2563eb;
    }

    /* User Info & Logout Button */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logout-btn {
        text-decoration: none;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.18);
    }

    .logout-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    /* Responsive design for mobile screens */
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
    }
</style>

<header class="site-header">
    <div class="nav-container">
        <!-- Logo / Title Section -->
        <div class="page-title">
            <span class="page-icon"><i class="fa-solid fa-house"></i></span>
            <span>Home</span>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="main-nav" aria-label="Main navigation">
            <a href="home.php" class="nav-link active">Home</a>

            <!-- Category Dropdown -->
            <div class="nav-dropdown">
                <button type="button" class="dropdown-toggle">Category <i class="fa-solid fa-caret-down"></i></button>
                <div class="nav-dropdown-menu">
                    <a href="insert.php">Insert Category</a>
                    <a href="view-category.php">View Category</a>
                </div>
            </div>

            <!-- Product Dropdown -->
            <div class="nav-dropdown">
                <button type="button" class="dropdown-toggle">Product <i class="fa-solid fa-caret-down"></i></button>
                <div class="nav-dropdown-menu">
                    <a href="insert-product.php">Insert Product</a>
                    <a href="view-product.php">View Product</a>
                </div>
            </div>

            <!-- Order Dropdown -->
            <div class="nav-dropdown">
                <button type="button" class="dropdown-toggle">Order <i class="fa-solid fa-caret-down"></i></button>
                <div class="nav-dropdown-menu">
                    <a href="view-orders.php">View Orders</a>
                </div>
            </div>

            <a href="settings.php" class="nav-link">Settings</a>
        </nav>

        <!-- Right Side: PHP dynamic User Email & Logout -->
        <div class="header-actions">
            <span style="font-size: 13px; color: #6b7280;">Welcome, <?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</header>

<script>
    // 1. Page ke sabhi dropdown buttons ko select karein
    const dropdownButtons = document.querySelectorAll('.dropdown-toggle');

    // 2. Har button par click event lagayein
    dropdownButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();

            const currentDropdown = this.parentElement;

            // Baaki sabhi dropdowns ko band kar dein
            document.querySelectorAll('.nav-dropdown').forEach(function (dropdown) {
                if (dropdown !== currentDropdown) {
                    dropdown.classList.remove('open');
                }
            });

            // Current dropdown ko open/close toggle karein
            currentDropdown.classList.toggle('open');
        });
    });

    // 3. Bahar click karne par dropdown band ho jaye
    document.addEventListener('click', function () {
        document.querySelectorAll('.nav-dropdown').forEach(function (dropdown) {
            dropdown.classList.remove('open');
        });
    });
</script>