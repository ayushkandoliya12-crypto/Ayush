<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS `ayush_hotel`");
$conn->select_db("ayush_hotel");

// 1. Create Table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(50) NOT NULL,
  `room_type` VARCHAR(100) NOT NULL,
  `category` VARCHAR(20) DEFAULT 'AC',
  `price` DECIMAL(10,2) NOT NULL,
  `capacity` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Available',
  `description` TEXT,
  `image` TEXT
)");

$conn->query("CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `check_in` DATE NOT NULL,
  `check_out` DATE NOT NULL,
  `guests` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Booked',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$booking_status_check = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'status'");
if ($booking_status_check && $booking_status_check->num_rows == 0) {
    $conn->query("ALTER TABLE `bookings` ADD `status` VARCHAR(20) DEFAULT 'Booked' AFTER `total_amount`");
}

$conn->query("CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `booking_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_mode` VARCHAR(30) DEFAULT 'UPI',
  `payment_status` VARCHAR(20) DEFAULT 'Paid',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$payment_mode_check = $conn->query("SHOW COLUMNS FROM `payments` LIKE 'payment_mode'");
if ($payment_mode_check && $payment_mode_check->num_rows == 0) {
    $conn->query("ALTER TABLE `payments` ADD `payment_mode` VARCHAR(30) DEFAULT 'UPI' AFTER `amount`");
}

$payment_status_check = $conn->query("SHOW COLUMNS FROM `payments` LIKE 'payment_status'");
if ($payment_status_check && $payment_status_check->num_rows == 0) {
    $conn->query("ALTER TABLE `payments` ADD `payment_status` VARCHAR(20) DEFAULT 'Paid' AFTER `payment_mode`");
}

// 2. Fix: Check if 'category' column exists
$col_check = $conn->query("SHOW COLUMNS FROM `rooms` LIKE 'category'");
if ($col_check && $col_check->num_rows == 0) {
    $conn->query("ALTER TABLE `rooms` ADD `category` VARCHAR(20) DEFAULT 'AC' AFTER `room_type`");
}

// 3. Unique Room & Villa Images Variables
$img_101 = "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80"; // Single Economy
$img_102 = "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80"; // Single Executive Room 102
$img_103 = "https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80"; // Standard Double
$img_201 = "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600&q=80"; // Deluxe AC
$img_202 = "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80"; // Family Classic
$img_203 = "https://images.unsplash.com/photo-1595576508898-0ad5c179a761?auto=format&fit=crop&w=600&q=80"; // Classic Suite Annex
$img_301 = "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=600&q=80"; // Royal Family Suite
$img_302 = "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80"; // Luxury Bedroom reference for Room 302
$img_403 = "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80"; // Presidential Royal Villa
$img_501 = "https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=600&q=80"; // Pool View Villa
$img_502 = "https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=600&q=80"; // Luxury Garden Villa
$img_503 = "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=600&q=80"; // Presidential Royal Villa Alt

// Insert Sample Rooms & Villas if Table is Empty
$check_rooms = $conn->query("SELECT * FROM rooms");
if ($check_rooms && $check_rooms->num_rows == 0) {
    $conn->query("INSERT INTO rooms (room_number, room_type, category, price, capacity, status, description, image) VALUES
    ('102', 'Single Executive Room', 'AC', 10000.00, 1, 'Available', 'Modern AC room with single bed and high-speed Wi-Fi.', '$img_102'),
    ('201', 'Deluxe AC Room', 'AC', 10000.00, 2, 'Available', 'Air-conditioned luxury room for 2 persons with smart TV.', '$img_201'),
    ('301', 'Royal Family Suite', 'AC', 20000.00, 4, 'Available', 'Premium AC suite for 4 persons with sofa seating and luxury view.', '$img_301'),
    ('302', 'Deluxe Family Annex', 'AC', 21000.00, 4, 'Available', 'Spacious deluxe room with extra bedding for families.', '$img_302')");
} else {
    // Keep only the active room list and remove extra unused room entries
    $extra_rooms = [
        ['102', 'Single Executive Room', 10000.00, 1, 'Modern AC room with single bed and high-speed Wi-Fi.', $img_102],
        ['201', 'Deluxe AC Room', 10000.00, 2, 'Air-conditioned luxury room for 2 persons with smart TV.', $img_201],
        ['301', 'Royal Family Suite', 20000.00, 4, 'Premium AC suite for 4 persons with sofa seating and luxury view.', $img_301],
        ['302', 'Deluxe Family Annex', 21000.00, 4, 'Spacious deluxe room with extra bedding for families.', $img_302]
    ];

    foreach($extra_rooms as $v) {
        $chk = $conn->query("SELECT * FROM rooms WHERE room_number = '{$v[0]}'");
        if($chk && $chk->num_rows == 0) {
            $conn->query("INSERT INTO rooms (room_number, room_type, category, price, capacity, status, description, image) VALUES ('{$v[0]}', '{$v[1]}', 'AC', {$v[2]}, {$v[3]}, 'Available', '{$v[4]}', '{$v[5]}')");
        }
    }

    // Force update existing active room list only
    $conn->query("UPDATE rooms SET 
        price = CASE 
            WHEN room_number = '102' THEN 10000.00
            WHEN room_number = '201' THEN 10000.00
            WHEN room_number = '301' THEN 20000.00
            WHEN room_number = '302' THEN 21000.00
            ELSE price 
        END,
        capacity = CASE
            WHEN room_number = '102' THEN 1
            WHEN room_number = '201' THEN 2
            WHEN room_number = '301' THEN 4
            WHEN room_number = '302' THEN 4
            ELSE capacity
        END,
        image = CASE
            WHEN room_number = '102' THEN '$img_102'
            WHEN room_number = '201' THEN '$img_201'
            WHEN room_number = '301' THEN '$img_301'
            WHEN room_number = '302' THEN '$img_302'
            ELSE image
        END
    WHERE room_number IN ('102', '201', '301', '302')");
    $conn->query("UPDATE rooms SET image = '$img_302' WHERE room_number = '302'");
}

$conn->query("UPDATE rooms SET room_type = TRIM(REPLACE(REPLACE(room_type, 'Non-AC', ''), 'Non AC', '')) WHERE room_type LIKE '%Non-AC%' OR room_type LIKE '%Non AC%'");
$conn->query("UPDATE rooms SET room_type = REPLACE(room_type, '  ', ' ') WHERE room_type LIKE '%  %'");

$msg = "";

// Booking Form Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'book') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = (int) $_SESSION['user_id'];
    $room_id = (int) $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = (int) $_POST['guests'];
    $payment_mode = $_POST['payment_mode'] ?? 'UPI';

    if (empty($check_in) || empty($check_out)) {
        $msg = "Check-in aur check-out dates required hain.";
    } else {
        try {
            $date1 = new DateTime($check_in);
            $date2 = new DateTime($check_out);
            $diff = $date1->diff($date2)->days;

            if ($diff <= 0) {
                $msg = "Check-out date check-in date ke baad ki honi chahiye!";
            } else {
                $room_res = $conn->query("SELECT price, status FROM rooms WHERE room_id = $room_id");
                if (!$room_res || $room_res->num_rows === 0) {
                    $msg = "Selected room available nahi hai.";
                } else {
                    $room = $room_res->fetch_assoc();
                    if ($room['status'] === 'Booked') {
                        $msg = "Ye room already booked hai. Dusra room choose karein.";
                    } else {
                        $total_amount = $diff * $room['price'];

                        $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, 'Booked')");
                        if (!$stmt) {
                            $msg = "Booking prepare failed: " . $conn->error;
                        } else {
                            $stmt->bind_param("iissid", $user_id, $room_id, $check_in, $check_out, $guests, $total_amount);
                            if ($stmt->execute()) {
                                $booking_id = $conn->insert_id;
                                $payment_stmt = $conn->prepare("INSERT INTO payments (user_id, booking_id, room_id, amount, payment_mode, payment_status) VALUES (?, ?, ?, ?, ?, 'Paid')");
                                if ($payment_stmt) {
                                    $payment_stmt->bind_param("iiids", $user_id, $booking_id, $room_id, $total_amount, $payment_mode);
                                    $payment_stmt->execute();
                                    $payment_stmt->close();
                                }

                                $conn->query("UPDATE rooms SET status='Booked' WHERE room_id=$room_id");
                                header("Location: rooms.php?booked=1");
                                exit();
                            } else {
                                $msg = "Booking failed: " . $stmt->error;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $msg = "Booking error: " . $e->getMessage();
        }
    }
}

include 'header.php';

// Category Filter Query with ORDER BY room_number ASC
$filter = isset($_GET['type']) ? $_GET['type'] : 'All';
if ($filter == 'AC') {
    $query = "SELECT * FROM rooms WHERE status='Available' AND category='AC' AND room_type NOT LIKE '%Villa%' ORDER BY room_number ASC";
} else {
    $query = "SELECT * FROM rooms WHERE status='Available' AND category='AC' AND room_type NOT LIKE '%Villa%' ORDER BY room_number ASC";
}
$rooms = $conn->query($query);
?>

<div class="my-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Our Rooms & Suites</h2>
        <p class="text-muted mb-0">Choose from our comfortable AC room options with premium hotel amenities.</p>
    </div>
    
    <!-- Filter Buttons -->
    <div class="mt-3 mt-md-0">
        <div class="btn-group" role="group">
            <a href="rooms.php?type=All" class="btn btn-outline-dark <?= ($filter == 'All') ? 'active' : ''; ?>">All Options</a>
            <a href="rooms.php?type=AC" class="btn btn-outline-primary <?= ($filter == 'AC') ? 'active' : ''; ?>">❄️ AC Rooms</a>
        </div>
    </div>
</div>

<?php if($msg): ?>
    <div class="alert alert-danger shadow-sm border-0 mb-4"><?= $msg; ?></div>
<?php endif; ?>

<?php if(isset($_GET['booked'])): ?>
    <div class="alert alert-success shadow-sm border-0 mb-4">🎉 Room / Villa booked successfully!</div>
<?php endif; ?>

<div class="row">
    <?php 
    if($rooms && $rooms->num_rows > 0):
        while($room = $rooms->fetch_assoc()): 
            $isAC = (isset($room['category']) && $room['category'] == 'AC') || strpos(strtolower($room['room_type']), 'ac') !== false;
            $isVilla = strpos(strtolower($room['room_type']), 'villa') !== false;
            $display_room_name = preg_replace('/\s*Non-?\s*AC\s*/i', ' ', $room['room_type']);
            $display_room_name = preg_replace('/\s{2,}/', ' ', trim($display_room_name));
    ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm bg-white border-0 position-relative">
                
                <!-- Category Badge -->
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-1">
                    <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill shadow-sm">❄️ AC</span>
                </div>

                <!-- Room / Villa Photo -->
                <div class="room-img-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= htmlspecialchars($room['image']); ?>" class="room-img w-100 h-100" style="object-fit: cover;" alt="<?= htmlspecialchars($room['room_type']); ?>" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600&q=80';">
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($display_room_name); ?></h5>
                        <span class="badge bg-light text-dark border">No. <?= htmlspecialchars($room['room_number']); ?></span>
                    </div>

                    <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($room['description']); ?></p>

                    <!-- Capacity / Person Details -->
                    <div class="p-2 bg-light rounded mb-3 d-flex align-items-center justify-content-between">
                        <span class="text-secondary small">👥 Capacity:</span>
                        <span class="fw-bold text-dark">
                            <?= htmlspecialchars($room['capacity']); ?> <?= ($room['capacity'] == 1) ? 'Person' : 'Persons'; ?>
                        </span>
                    </div>

                    <!-- Price & Booking -->
                    <div class="d-flex align-items-center justify-content-between mt-auto">
                        <div>
                            <span class="text-muted small d-block">Price / Night</span>
                            <h4 class="text-success fw-bold mb-0">₹<?= number_format($room['price'], 0); ?></h4>
                        </div>

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <button class="btn btn-dark px-4" data-bs-toggle="modal" data-bs-target="#bookModal<?= $room['room_id']; ?>">Book Now</button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-dark px-3">Login to Book</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookModal<?= $room['room_id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form method="POST" action="rooms.php" class="booking-form" data-room-price="<?= $room['price']; ?>">
                        <input type="hidden" name="action" value="book">
                        <input type="hidden" name="room_id" value="<?= $room['room_id']; ?>">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Book <?= htmlspecialchars($display_room_name); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Check-in Date</label>
                                <input type="date" name="check_in" class="form-control booking-checkin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Check-out Date</label>
                                <input type="date" name="check_out" class="form-control booking-checkout" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Number of Guests (Max <?= $room['capacity']; ?>)</label>
                                <select name="guests" class="form-select" required>
                                    <?php for($i = 1; $i <= $room['capacity']; $i++): ?>
                                        <option value="<?= $i; ?>"><?= $i; ?> <?= ($i == 1) ? 'Person' : 'Persons'; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Option</label>
                                <select name="payment_mode" class="form-select" required>
                                    <option value="UPI">UPI / GPay</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>

                            <div class="alert alert-light border mt-3 mb-0">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Rate / Night</span>
                                    <strong>₹<?= number_format($room['price'], 0); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-2">
                                    <span>Total Nights</span>
                                    <strong><span class="booking-nights">0</span></strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark">Total Payment</span>
                                    <h5 class="mb-0 text-success fw-bold">₹<span class="booking-total">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Confirm Reservation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php 
        endwhile; 
    else:
    ?>
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">Is category me koi option available nahi hai.</h4>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.booking-form').forEach(function (form) {
        const price = Number(form.dataset.roomPrice || 0);
        const checkIn = form.querySelector('.booking-checkin');
        const checkOut = form.querySelector('.booking-checkout');
        const nightsEl = form.querySelector('.booking-nights');
        const totalEl = form.querySelector('.booking-total');

        function updateTotal() {
            const inDate = checkIn.value ? new Date(checkIn.value) : null;
            const outDate = checkOut.value ? new Date(checkOut.value) : null;

            if (inDate && outDate && outDate > inDate) {
                const diffTime = outDate - inDate;
                const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = nights * price;
                nightsEl.textContent = nights;
                totalEl.textContent = total.toLocaleString('en-IN');
            } else {
                nightsEl.textContent = '0';
                totalEl.textContent = '0';
            }
        }

        checkIn.addEventListener('change', updateTotal);
        checkOut.addEventListener('change', updateTotal);
    });
});
</script>

<?php include 'footer.php'; ?>