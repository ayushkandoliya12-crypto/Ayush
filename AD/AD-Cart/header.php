<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD Cart</title> 
    <style>
        * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #eaeded; color: #0f1111; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Modern Header CSS */
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

        /* Integrated Search Bar */
        .header-search {
            flex: 1;
            max-width: 550px;
            display: flex;
            align-items: center;
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(13, 179, 230, 0.1);
        }
        .header-search input {
            width: 100%;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            outline: none;
            color: #0f1111;
        }
        .header-search button {
            background: #ff9900;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .header-search button:hover {
            background: #e68a00;
        }
        .header-search button svg {
            stroke: #131921;
        }

        /* Nav Links & Badges */
        .nav-links { 
            list-style: none; 
            display: flex; 
            align-items: center;
            gap: 24px; 
            margin: 0; 
            padding: 0; 
        }
        .nav-links a { 
            color: #ffffff; 
            text-decoration: none; 
            font-size: 14px; 
            font-weight: 600; 
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 4px;
            transition: background 0.2s, color 0.2s; 
        }
        .nav-links a:hover, .nav-links a.active { 
            color: #ff9900; 
            background: rgba(255, 255, 255, 0.08);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-left: 8px;
        }
        .user-pill {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            line-height: 1.2;
        }
        .logout-btn {
            background: #e49013;
            color: #8db5e9;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            line-height: 1.2;
        }
        .logout-btn:hover {
            background: #f59e0b;
            transform: translateY(-1px);
        }

        /* Cart Icon Badge */
        .cart-icon-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #ff9900;
            color: #131921;
            font-size: 11px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 10px;
            border: 2px solid #131921;
        }

        /* Page Layout */
        .page-wrap { max-width: 1280px; margin: 30px auto; padding: 0 20px; flex: 1; width: 100%; }
        h2 { margin: 0 0 20px; text-align: center; font-size: 28px; color: #0f1111; }
        
        /* Product Card CSS */
        .product-grid { display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; }
        .product-card {
            background: #ffffff;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .img-container { width: 100%; height: 220px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .product-image { max-width: 100%; max-height: 100%; object-fit: contain; }
        .category-badge { font-size: 11px; color: #565959; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .product-title { font-size: 18px; font-weight: 700; line-height: 1.3; color: #0f1111; margin: 0 0 8px 0; }
        .product-desc { font-size: 13px; color: #565959; margin-bottom: 15px; line-height: 1.4; }
        .price-tag { font-size: 22px; font-weight: 700; color: #B12704; margin-bottom: 15px; }
        
        .actions { display: flex; gap: 10px; }
        .action-link, .buy-button {
            flex: 1;
            padding: 10px 0;
            border: 0;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }
        .action-link { background: #ffa41c; color: #0f1111; }
        .buy-button { background: #FFD814; color: #0f1111; }
        .action-link:hover { background: #fa8900; }
        .buy-button:hover { background: #f7ca00; }

        /* Footer CSS */
        .site-footer {
            background: #232f3e;
            color: #848688;
            text-align: center;
            padding: 12px 0;
            margin-top: auto;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-wrap: wrap;
            }
            .header-search {
                order: 3;
                width: 100%;
                max-width: 100%;
                margin-top: 10px;
            }
            .nav-links {
                gap: 12px;
            }
            .nav-links a span {
                display: none;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <span class="ad-logo" aria-label="AD logo">AD</span><span><span class="logo-accent">Cart</span></span>
        </a>
        <!-- Search Bar -->
        <form class="header-search" action="search.php" method="GET" role="search">
            <input type="text" name="q" placeholder="Search mobiles, accessories, brands..." aria-label="Search">
            <button type="submit" aria-label="Search Button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>

        <!-- Navigation Links -->
        <nav>
            <ul class="nav-links">
                <li>
                    <a href="index.php" class="active">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Home</span>
                    </a>
                </li>

                <?php if (isset($_SESSION['customer_name']) || isset($_SESSION['sid'])): ?>
                    <li class="user-menu">
                        <span class="user-pill"><span>👤</span> <?= htmlspecialchars($_SESSION['customer_name'] ?? $_SESSION['email'] ?? 'User') ?></span>
                        <a href="logout.php" class="logout-btn">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="login.php">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>Login</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
