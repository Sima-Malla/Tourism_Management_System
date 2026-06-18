<?php
session_start();
include 'header.php';
include("connection/connect.php");

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$book = isset($_GET['book']) ? (int) $_GET['book'] : 0;
if (!$book) {
    header('Location: hotels.php');
    exit();
}

$sql = "SELECT * FROM rooms WHERE r_id = $book";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header('Location: hotels.php');
    exit();
}

$r_id = $row['r_id'];
$rtype = $row['rtype'];
$rprice = $row['rprice'];
$rtext = $row['rtext'];
$rimage = $row['rimage'];

// Pre-fill dates from hotel-search if passed
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : date('Y-m-d');
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : date('Y-m-d', strtotime('+1 day'));
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Book Room - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .booking-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .booking-card h4 {
            color: #4B795D;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .room-summary {
            background: #f0f7f3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .room-summary img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dde;
            font-size: 14px;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 16px;
            color: #4B795D;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            padding: 5px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 100%;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #4B795D;
            outline: none;
            box-shadow: 0 0 0 3px rgba(75, 121, 93, 0.15);
        }

        .payment-options {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .payment-option {
            flex: 1;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .payment-option:hover { border-color: #4B795D; }
        .payment-option.active { border-color: #4B795D; background: #f0f7f3; }
        .payment-option input { display: none; }
        .payment-option .opt-title { font-weight: 700; font-size: 15px; color: #333; margin-bottom: 4px; }
        .payment-option .opt-percent { font-size: 22px; font-weight: 800; color: #4B795D; }
        .payment-option .opt-amount { font-size: 13px; color: #888; margin-top: 4px; }
        .payment-option .opt-badge { font-size: 11px; background: #4B795D; color: white; padding: 2px 8px; border-radius: 10px; display: inline-block; margin-top: 5px; }
        .remaining-note { background: #fff3cd; border-radius: 8px; padding: 12px 15px; font-size: 13px; color: #856404; margin-bottom: 15px; display: none; }

        .btn-pay {
            background: #60bb46;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .btn-pay:hover {
            background: #4a9936;
        }

        .esewa-logo {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="about-bg">
            <div class="container">
                <div class="rl-banner">
                    <h2>Book Room</h2>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><span class="active">Reservation</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <section class="booking-section">
            <div class="container">
                <div class="row">
                    <!-- Room Summary -->
                    <div class="col-md-4">
                        <div class="booking-card">
                            <h4><i class="fa fa-bed"></i> Room Summary</h4>
                            <div class="room-summary">
                                <img src="admin/upload/<?php echo $rimage; ?>" alt="<?php echo $rtype; ?>">
                                <div class="summary-row"><span>Room Type</span><span><?php echo $rtype; ?></span></div>
                                <div class="summary-row"><span>Price / Night</span><span>Rs.
                                        <?php echo number_format($rprice, 2); ?></span></div>
                                <div class="summary-row"><span>Nights</span><span id="nights_display">1</span></div>
                                <div class="summary-row"><span>Total Amount</span><span id="total_display">Rs.
                                        <?php echo number_format($rprice, 2); ?></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div class="col-md-8">
                        <div class="booking-card">
                            <h4><i class="fa fa-calendar"></i> Confirm Your Booking</h4>
                            <form action="payment.php" method="POST" id="bookingForm">
                                <input type="hidden" name="room_id" value="<?php echo $r_id; ?>">
                                <input type="hidden" name="total_amount" id="hidden_total"
                                    value="<?php echo $rprice; ?>">
                                <input type="hidden" name="price_per_night" value="<?php echo $rprice; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Check-in Date *</label>
                                            <input type="date" name="check_in" id="check_in" class="form-control"
                                                value="<?php echo $check_in; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Check-out Date *</label>
                                            <input type="date" name="check_out" id="check_out" class="form-control"
                                                value="<?php echo $check_out; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Number of Guests *</label>
                                    <select name="guests" class="form-control" required>
                                        <option value="1">1 Guest</option>
                                        <option value="2">2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Payment Option *</label>
                                    <div class="payment-options">
                                        <label class="payment-option active" id="opt_full">
                                            <input type="radio" name="payment_type" value="full" checked>
                                            <div class="opt-title">Full Payment</div>
                                            <div class="opt-percent">100%</div>
                                            <div class="opt-amount">Rs. <span id="full_amount"><?php echo number_format($rprice, 2); ?></span></div>
                                            <span class="opt-badge">No balance due</span>
                                        </label>
                                        <label class="payment-option" id="opt_partial">
                                            <input type="radio" name="payment_type" value="partial">
                                            <div class="opt-title">Partial Payment</div>
                                            <div class="opt-percent">50%</div>
                                            <div class="opt-amount">Rs. <span id="partial_amount"><?php echo number_format($rprice / 2, 2); ?></span></div>
                                            <span class="opt-badge" style="background:#f0a500;">Balance at check-in</span>
                                        </label>
                                    </div>
                                    <div class="remaining-note" id="remaining_note">
                                        <i class="fa fa-info-circle"></i>
                                        You will pay <strong>50%</strong> now and the remaining <strong>Rs. <span id="remaining_display">0</span></strong> must be paid at check-in.
                                    </div>
                                </div>

                                <input type="hidden" name="payment_type_val" id="payment_type_val" value="full">

                                <button type="submit" class="btn-pay" id="pay_btn">
                                    <i class="fa fa-lock"></i> Pay with eSewa &mdash; Rs. <span id="btn_amount"><?php echo number_format($rprice, 2); ?></span>
                                </button>
                                <p class="esewa-logo">Secured by eSewa Payment Gateway</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        const pricePerNight = <?php echo $rprice; ?>;
        let fullTotal = <?php echo $rprice; ?>;

        function calculateTotal() {
            const checkIn  = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            if (!checkIn || !checkOut) return;
            const nights = Math.round((new Date(checkOut) - new Date(checkIn)) / 86400000);
            if (nights < 1) return;
            fullTotal = pricePerNight * nights;
            document.getElementById('nights_display').textContent = nights;
            document.getElementById('total_display').textContent  = 'Rs. ' + fullTotal.toFixed(2);
            document.getElementById('full_amount').textContent    = fullTotal.toFixed(2);
            document.getElementById('partial_amount').textContent = (fullTotal * 0.5).toFixed(2);
            document.getElementById('remaining_display').textContent = (fullTotal * 0.5).toFixed(2);
            updatePaymentAmount();
        }

        function updatePaymentAmount() {
            const isPartial = document.querySelector('input[name="payment_type"]:checked').value === 'partial';
            const payAmount = isPartial ? (fullTotal * 0.5) : fullTotal;
            document.getElementById('hidden_total').value       = payAmount.toFixed(2);
            document.getElementById('btn_amount').textContent   = payAmount.toFixed(2);
            document.getElementById('payment_type_val').value   = isPartial ? 'partial' : 'full';
            document.getElementById('remaining_note').style.display = isPartial ? 'block' : 'none';
            document.getElementById('opt_full').classList.toggle('active', !isPartial);
            document.getElementById('opt_partial').classList.toggle('active', isPartial);
        }

        document.querySelectorAll('input[name="payment_type"]').forEach(function(r) {
            r.addEventListener('change', updatePaymentAmount);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const today    = new Date().toISOString().split('T')[0];
            const checkIn  = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            checkIn.min  = today;
            checkOut.min = today;
            checkIn.addEventListener('change', function () {
                const next = new Date(this.value);
                next.setDate(next.getDate() + 1);
                checkOut.min = next.toISOString().split('T')[0];
                if (checkOut.value && checkOut.value <= this.value) checkOut.value = '';
                calculateTotal();
            });
            checkOut.addEventListener('change', calculateTotal);
            calculateTotal();
        });

        document.getElementById('bookingForm').addEventListener('submit', function () {
            const btn = document.getElementById('pay_btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Redirecting to eSewa...';
        });
    </script>
</body>

</html>