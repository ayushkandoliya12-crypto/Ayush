<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
?>

<!-- Header Banner -->
<div class="p-5 text-white rounded mb-4 text-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=80') center/cover;">
    <span class="badge bg-warning text-dark px-3 py-2 mb-2 fs-6">🛎️ Always At Your Service</span>
    <h1 class="display-4 fw-bold">24/7 In-Room Service & Hospitality</h1>
    <p class="lead opacity-90">Humari support team aapki khidmat ke liye 24 ghante haazir hai.</p>
</div>

<!-- Contact & Support Grid -->
<div class="row my-5">
    
    <!-- Left Column: Contact Details Cards -->
    <div class="col-lg-5 mb-4">
        <h3 class="fw-bold mb-4">📞 Direct Contact Channels</h3>
        
        <!-- Toll Free Hotline -->
        <div class="card p-3 mb-3 border-0 shadow-sm border-start border-4 border-warning">
            <div class="d-flex align-items-center">
                <div class="fs-1 text-warning me-3">📱</div>
                <div>
                    <h6 class="fw-bold mb-1">Room Service Hotline (Mobile/WhatsApp)</h6>
                    <p class="mb-1 text-dark fw-bold fs-5">+91 12345 67890</p>
                    <small class="text-muted">Instant response for room orders & cleaning</small>
                </div>
            </div>
        </div>

        <!-- Landline Number -->
        <div class="card p-3 mb-3 border-0 shadow-sm border-start border-4 border-primary">
            <div class="d-flex align-items-center">
                <div class="fs-1 text-primary me-3">☎️</div>
                <div>
                    <h6 class="fw-bold mb-1">Direct Hotel Landline</h6>
                    <p class="mb-1 text-dark fw-bold fs-5">0281-2345678 / Ext: 101</p>
                    <small class="text-muted">Dial 101 directly from your room telephone</small>
                </div>
            </div>
        </div>

        <!-- Support Email -->
        <div class="card p-3 mb-3 border-0 shadow-sm border-start border-4 border-danger">
            <div class="d-flex align-items-center">
                <div class="fs-1 text-danger me-3">✉️</div>
                <div>
                    <h6 class="fw-bold mb-1">Support & Feedback Email</h6>
                    <p class="mb-1 text-dark fw-bold">roomservice@ayushhotel.com</p>
                    <small class="text-muted">For special requests or scheduled assistance</small>
                </div>
            </div>
        </div>

        <!-- Reception Info Box -->
        <div class="bg-light p-3 rounded border">
            <h6 class="fw-bold text-success mb-1">🟢 Reception Desk Status</h6>
            <p class="small text-muted mb-0">Front desk is active 24 Hours. Dial extension <strong>'0'</strong> from your room intercom for any emergency assistance.</p>
        </div>
    </div>

    <!-- Right Column: Quick Request Form -->
    <div class="col-lg-7">
        <div class="card p-4 shadow-sm border-0">
            <h4 class="fw-bold mb-3">🛎️ Quick Room Service Request</h4>
            <p class="text-muted small mb-4">Need fresh towels, extra pillows, house-keeping, or wake-up call? Send a quick message below:</p>

            <form onsubmit="handleServiceRequest(event)">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Guest Name</label>
                        <input type="text" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Room Number</label>
                        <input type="text" class="form-control" placeholder="e.g. Room 204" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Service Type</label>
                    <select class="form-select" required>
                        <option value="">-- Select Assistance Needed --</option>
                        <option value="Housekeeping">🧹 Housekeeping & Cleaning</option>
                        <option value="Laundry">🧺 Fresh Towels & Extra Bedding</option>
                        <option value="Maintenance">🔧 AC / TV / Plumbing Issue</option>
                        <option value="Wakeup Call">⏰ Wake-up Call Service</option>
                        <option value="Luggage">🧳 Luggage Assistance</option>
                        <option value="Other">❓ Other Custom Request</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Details / Instructions</label>
                    <textarea class="form-control" rows="3" placeholder="Specify your request here..."></textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm text-dark">
                    🚀 Submit Service Request
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function handleServiceRequest(e) {
    e.preventDefault();
    alert("✅ Aapka Room Service Request receive ho gaya hai! Support staff 5-10 min mein aapke room mein pahunch jayenge.");
    e.target.reset();
}
</script>

<?php include 'footer.php'; ?>