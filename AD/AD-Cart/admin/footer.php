<style>
    html, body {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .site-footer {
        background: linear-gradient(180deg, #f0f7ff 0%, #e3f0ff 100%);
        border-top: 1px solid #bfd7ff;
        color: #1f2937;
        width: 100%;
        padding: 20px 15px;
        text-align: center;
        font-size: 14px;
        margin-top: auto;
        box-shadow: 0 -2px 10px rgba(37, 99, 235, 0.08);
    }

    .site-footer-content {
        max-width: 1100px;
        margin: 0 auto;
    }

    .site-footer a,
    .site-footer span,
    .site-footer .footer-text {
        color: #1f2937;
        text-decoration: none;
        font-weight: 600;
    }

    .site-footer a:hover {
        text-decoration: underline;
        color: #2563eb;
    }

    .footer-links {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .footer-separator {
        color: #9ca3af;
    }
</style>

<footer class="site-footer">
    <div class="site-footer-content">
        <div class="footer-text">© 2026 ShopCentral • All rights reserved</div>
        <div class="footer-links">
            <a href="home.php#">Company Policy</a>
            <span class="footer-separator">|</span>
            <a href="home.php#">Privacy Policy</a>
            <span class="footer-separator">|</span>
            <a href="home.php#">Terms &amp; Conditions</a>
            <span class="footer-separator">|</span>
            <a href="home.php#">Shipping Policy</a>
            <span class="footer-separator">|</span>
            <a href="mailto:support@shopcentral.com">support@shopcentral.com</a>
            <span class="footer-separator">|</span>
            <a href="tel:+911234567890">+91 12345 67890</a>
        </div>
    </div>
</footer>