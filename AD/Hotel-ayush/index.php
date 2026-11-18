<?php
// ==========================================================
// HOME PAGE
// This is the landing page of the hotel website.
// It shows the main services and gives users a way to go to rooms.
// ==========================================================
include 'header.php';
?>

<!-- Hero Banner -->
<div class="hero-section text-center">
    <h1 class="display-4 fw-bold mb-3">Welcome to Ayush Hotel</h1>
    <p class="lead text-light opacity-90 mb-4">Experience unmatched luxury, comfort, and 5-star hospitality.</p>
    <a href="rooms.php" class="btn btn-warning btn-lg px-4 py-2 fw-bold text-dark shadow">Explore Our Rooms</a>
</div>

<!-- Hotel Highlights / Services -->
<div class="row my-5 text-center">
    <h2 class="fw-bold mb-4">Why Choose Ayush Hotel?</h2>
    
    <!-- 1. Luxury Rooms -->
    <div class="col-md-6 mb-4">
        <a href="rooms.php" class="text-decoration-none text-dark d-block h-100">
            <div class="card p-4 h-100 shadow-sm border-0 room-card-hover">
                <div class="fs-1 text-primary mb-2">🏨</div>
                <h5 class="fw-bold">Luxury Rooms</h5>
                <p class="text-muted small mb-2">Designed for comfort with modern amenities and high-speed Wi-Fi.</p>
                <span class="text-primary fw-semibold small">Explore Rooms &rarr;</span>
            </div>
        </a>
    </div>
    
    <!-- 2. 24/7 Room Service -->
    <div class="col-md-6 mb-4">
        <a href="room_service.php" class="text-decoration-none text-dark d-block h-100">
            <div class="card p-4 h-100 shadow-sm border-0 room-card-hover">
                <div class="fs-1 text-warning mb-2">🛎️</div>
                <h5 class="fw-bold">24/7 Room Service</h5>
                <p class="text-muted small mb-2">Dedicated support staff ready to assist you anytime during your stay.</p>
                <span class="text-warning text-dark fw-semibold small">Contact Support & Requests &rarr;</span>
            </div>
        </a>
    </div>
</div>

<!-- Featured Rooms Teaser -->
<div class="bg-white p-4 rounded shadow-sm text-center my-4">
    <h3 class="fw-bold mb-2">Ready to Book Your Stay?</h3>
    <p class="text-muted mb-3">Check out our full collection of Deluxe, Standard, Royal Suites, and Private Villas.</p>
    <a href="rooms.php" class="btn btn-dark btn-lg px-5">View All Available Rooms</a>
</div>

<?php include 'footer.php'; ?>