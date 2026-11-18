<style>
    .site-footer {
        background: #131921;
        color: #ffffff;
        margin-top: 25px;
        text-align: left;
    }

    .footer-container {
        width: 90%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .footer-main {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
        gap: 24px;
        padding: 28px 0 22px;
    }

    .footer-column h3 {
        color: #ffffff;
        font-size: 15px;
        margin: 0 0 9px;
    }

    .footer-column p,
    .footer-column li,
    .footer-column a {
        color: #c7c9c9;
        font-size: 13px;
        line-height: 1.45;
    }

    .footer-column p {
        margin: 0;
    }

    .footer-column ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .footer-column a {
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .footer-column a:hover {
        color: #ff9900;
    }

    .footer-brand {
        color: #ffffff;
        font-size: 21px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .footer-brand span {
        color: #ff9900;
    }

    .footer-brand .ad-logo {
        width: 50px;
        height: 34px;
        margin-right: 7px;
        border-radius: 9px 4px 9px 4px;
        background: linear-gradient(145deg,  #c8d3d2, #2a808f);
        color: #131921;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        font-weight: 900;
        letter-spacing: -3px;
        box-shadow: 0 3px 8px rgba(255, 153, 0, 0.25);
    }


    .footer-contact {
        margin-top: 7px !important;
    }

    .footer-bottom {
        border-top: 1px solid #3a4553;
        color: #aab1b8;
        font-size: 12px;
        padding: 12px 0;
        text-align: center;
    }

    .footer-bottom p {
        margin: 0;
    }

    @media (max-width: 700px) {
        .footer-main {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 16px;
        }
    }

    @media (max-width: 430px) {
        .footer-main {
            grid-template-columns: 1fr;
            padding: 24px 0 18px;
        }
    }
</style>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-main">
            <div class="footer-column">
                <div class="footer-brand"><span class="ad-logo" aria-label="AD logo">AD</span><span>Cart</span></div>
                <p>Find the latest mobiles and accessories at reliable prices, with a simple shopping experience.</p>
                <p class="footer-contact">Your trusted destination for smart technology.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">All Products</a></li>
                    <li><a href="index.php">New Arrivals</a></li>
                    <li><a href="index.php">Best Sellers</a></li>
                    <li><a href="admin/index.php">Account Login</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Customer Support</h3>
                <ul>
                    <li><a href="mailto:support@mobilehub.com">Email Support</a></li>
                    <li><a href="tel:+919876543210">Order Assistance</a></li>
                    <li><a href="index.php">Delivery Information</a></li>
                    <li><a href="index.php">Return Policy</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p>ADCart</p>
                <p>Rajkot, Gujarat, India</p>
                <p><a href="tel:+919876543210">+91 98765 43210</a></p>
                <p><a href="mailto:support@mobilehub.com">support@mobilehub.com</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> ADCart. All Rights Reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>
