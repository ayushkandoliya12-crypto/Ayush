</div> <!-- End of container div from header -->

<!-- Footer Section -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5 border-top border-secondary">
    <div class="container">
        <div class="row g-4">
            
            <!-- Hotel Info & Owner Name -->
            <div class="col-md-4">
                <h5 class="fw-bold text-warning mb-3">✨ Ayush Hotel</h5>
                <p class="text-secondary small">
                    Experience unmatched luxury, comfort, and 5-star hospitality. Book your stay with us for an unforgettable experience.
                </p>
                <p class="small text-light mb-0">
                    <strong>Owner:</strong> Ayush Kandoliya
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 text-md-center">
                <h5 class="fw-bold text-light mb-3">Quick Links</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="index.php" class="text-secondary text-decoration-none hover-warning">Home</a></li>
                    <li class="mb-2"><a href="rooms.php" class="text-secondary text-decoration-none hover-warning">Rooms & Suites</a></li>
                    <li class="mb-2"><a href="more.php" class="text-secondary text-decoration-none hover-warning">More Options</a></li>
                    <li class="mb-2"><a href="login.php" class="text-secondary text-decoration-none hover-warning">Account Login</a></li>
                </ul>
            </div>

            <!-- Social Media & Contact -->
            <div class="col-md-4 text-md-end">
                <h5 class="fw-bold text-light mb-3">Connect With Us</h5>
                <p class="text-secondary small mb-3">Follow us on social media for updates & offers:</p>
                
                <!-- Social Icons -->
                <div class="d-flex justify-content-md-end gap-3 mb-3 fs-4">
                    <a href="https://www.instagram.com/" target="_blank" class="text-light social-icon text-decoration-none" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/" target="_blank" class="text-light social-icon text-decoration-none" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>

                <small class="text-secondary d-block">📞 +91 12345 67890</small>
                <small class="text-secondary d-block">✉️ support@ayushhotel.com</small>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <!-- Copyright Info -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small text-secondary mb-0">
                    &copy; <?= date('Y'); ?> <strong>Ayush Hotel</strong>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <p class="small text-secondary mb-0">
                    Designed & Developed by <strong>Ayush Kandoliya</strong>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons CDNs -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Styling for Hover Effects -->
<style>
    .hover-warning:hover {
        color: #ffc107 !important;
        transition: color 0.2s ease-in-out;
    }
    .social-icon {
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .social-icon:hover {
        transform: translateY(-3px);
        color: #ffc107 !important;
    }
</style>

</body>
</html>