<?php
// ==========================================================
// ACCOUNT HISTORY PAGE
// This file shows:
// 1. Booking records of the logged-in user
// 2. Payment records of that user
// It is useful for tracking stay history and receipts.
// ==========================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'header.php';

$user_id = (int) $_SESSION['user_id'];

$bookings = $conn->query("SELECT b.booking_id, b.check_in, b.check_out, b.guests, b.total_amount, b.status, r.room_type, r.room_number
    FROM bookings b
    LEFT JOIN rooms r ON r.room_id = b.room_id
    WHERE b.user_id = $user_id
    ORDER BY b.created_at DESC");

$payments = $conn->query("SELECT p.payment_id, p.booking_id, p.amount, p.payment_mode, p.created_at, r.room_type, r.room_number
    FROM payments p
    LEFT JOIN rooms r ON r.room_id = p.room_id
    WHERE p.user_id = $user_id
    ORDER BY p.created_at DESC");
?>

<div class="my-4">
    <h2 class="fw-bold text-dark mb-1">📜 Booking History & Receipts</h2>
    <p class="text-muted">Track your past bookings and view payment transactions in one place.</p>
</div>

<ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm border" id="historyTab" role="tablist">
    <li class="nav-item flex-fill text-center" role="presentation">
        <button class="nav-link active w-100 fw-bold" id="booking-tab" data-bs-toggle="pill" data-bs-target="#booking-history" type="button" role="tab">
            🏨 Booking History
        </button>
    </li>
    <li class="nav-item flex-fill text-center" role="presentation">
        <button class="nav-link w-100 fw-bold" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment-history" type="button" role="tab">
            💳 Payment History
        </button>
    </li>
</ul>

<div class="tab-content" id="historyTabContent">
    <div class="tab-pane fade show active" id="booking-history" role="tabpanel">
        <div class="card p-4 shadow-sm border-0">
            <h5 class="fw-bold text-primary mb-3">🏨 Recent Booking Records</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Room / Service</th>
                            <th>Check-in / Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings && $bookings->num_rows > 0): ?>
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#AYUSH-<?= htmlspecialchars($booking['booking_id']); ?></strong></td>
                                    <td><?= htmlspecialchars($booking['room_type'] ?: 'Room'); ?> (<?= htmlspecialchars($booking['guests']); ?> Guests)</td>
                                    <td><?= date('M d, Y', strtotime($booking['check_in'])); ?> - <?= date('M d, Y', strtotime($booking['check_out'])); ?><br><small class="text-muted">In: 12:00 PM | Out: 11:00 AM</small></td>
                                    <td>
                                        <span class="badge <?= $booking['status'] === 'Booked' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                            <?= htmlspecialchars($booking['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Abhi koi booking record nahi hai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="payment-history" role="tabpanel">
        <div class="card p-4 shadow-sm border-0">
            <h5 class="fw-bold text-success mb-3">💳 Payment Receipts & Transactions</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Service / Room</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Date & Time</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($payments && $payments->num_rows > 0): ?>
                            <?php while ($payment = $payments->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#AYUSH-<?= htmlspecialchars($payment['booking_id']); ?></strong></td>
                                    <td><?= htmlspecialchars($payment['room_type'] ?: 'Room'); ?></td>
                                    <td class="fw-bold text-success">₹<?= number_format($payment['amount'], 2); ?></td>
                                    <td><?= htmlspecialchars($payment['payment_mode']); ?></td>
                                    <td><?= date('M d, Y - h:i A', strtotime($payment['created_at'])); ?></td>
                                    <td><button class="btn btn-sm btn-outline-dark" onclick="alert('Downloading Receipt #AYUSH-<?= htmlspecialchars($payment['booking_id']); ?>...')">📄 Download</button></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Abhi koi payment history nahi hai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>