<?php
include 'header.php';
?>

<div class="my-4">
    <h2 class="fw-bold text-dark mb-1">More Options & Services</h2>
    <p class="text-muted">Access account records, history, and policy information.</p>
</div>

<div class="row g-4">
    <!-- Combined History Card Linked to account_history.php -->
    <div class="col-md-6">
        <div class="card p-4 shadow-sm bg-white h-100 border-0">
            <div class="d-flex align-items-center mb-3">
                <div class="fs-2 text-primary me-3">📜</div>
                <div>
                    <h5 class="fw-bold mb-0">Account & Payment History</h5>
                    <small class="text-muted">Login logs, transactions & invoices.</small>
                </div>
            </div>
            <p class="text-secondary small">View detailed logs of your account sign-ins, IP addresses, past booking receipts, and payment statuses all in one place.</p>
            <a href="account_history.php" class="btn btn-primary btn-sm mt-auto w-100 fw-bold">View History & Invoices &rarr;</a>
        </div>
    </div>

    <!-- Hotel Policies -->
    <div class="col-md-6">
        <div class="card p-4 shadow-sm bg-white h-100 border-0">
            <div class="d-flex align-items-center mb-3">
                <div class="fs-2 text-warning me-3">📜</div>
                <div>
                    <h5 class="fw-bold mb-0">Hotel Policies</h5>
                    <small class="text-muted">Rules & Check-in / Check-out timings.</small>
                </div>
            </div>
            <ul class="text-secondary small ps-3 mb-0">
                <li>Standard Check-in: 12:00 PM | Check-out: 11:00 AM</li>
                <li>Valid Govt ID proof required at registration.</li>
                <li>Free cancellation up to 24 hours before check-in.</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>