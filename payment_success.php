<?php
session_start();
include 'connection/connect.php';

$success    = false;
$error      = '';
$booking_id = null;

if (!isset($_GET['data'])) {
    $error = 'No payment data received.';
} else {
    $decoded  = base64_decode($_GET['data']);
    $response = json_decode($decoded, true);

    if (!$response) {
        $error = 'Invalid payment response. Please contact support.';
    } else {
        $transaction_code = $response['transaction_code'] ?? '';
        $status           = $response['status']           ?? '';
        $transaction_uuid = $response['transaction_uuid'] ?? '';

        $booking = $_SESSION['pending_booking'] ?? null;

        if (!$booking && $transaction_uuid) {
            // Check if booking exists in booked table by transaction_uuid
            $check = mysqli_prepare($db, "SELECT booking_id FROM booked WHERE transaction_id = ? LIMIT 1");
            mysqli_stmt_bind_param($check, 's', $transaction_uuid);
            mysqli_stmt_execute($check);
            $check_result = mysqli_stmt_get_result($check);
            mysqli_stmt_close($check);
            if ($check_result && mysqli_num_rows($check_result) > 0) {
                $booking = ['transaction_uuid' => $transaction_uuid];
            }
        }

        if (!$booking) {
            $error = 'Session expired. If you were charged, contact support with code: ' . htmlspecialchars($transaction_code);
        } elseif ($status !== 'COMPLETE') {
            // Delete the pending booking if payment failed
            mysqli_query($db, "DELETE FROM booked WHERE transaction_id = '$transaction_uuid' AND payment_status = 'pending'");
            $error = 'Payment was not completed. Please try again.';
        } else {
            // Update the existing pending booking to confirmed
            $dup = mysqli_prepare($db, "SELECT booking_id, payment_status FROM booked WHERE transaction_id = ? LIMIT 1");
            mysqli_stmt_bind_param($dup, 's', $transaction_uuid);
            mysqli_stmt_execute($dup);
            $dup_result = mysqli_stmt_get_result($dup);
            mysqli_stmt_close($dup);

            if ($dup_result && mysqli_num_rows($dup_result) > 0) {
                $dup_row    = mysqli_fetch_assoc($dup_result);
                $booking_id = $dup_row['booking_id'];

                if ($dup_row['payment_status'] !== 'paid') {
                    $upd = mysqli_prepare($db, "UPDATE booked SET payment_status = 'paid' WHERE booking_id = ?");
                    mysqli_stmt_bind_param($upd, 'i', $booking_id);
                    mysqli_stmt_execute($upd);
                    mysqli_stmt_close($upd);
                }
                unset($_SESSION['pending_booking']);
                $success = true;
            } else {
                $error = 'Booking not found. Contact support with code: ' . htmlspecialchars($transaction_code);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $success ? 'Booking Confirmed' : 'Payment Issue'; ?> - TourStay</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .icon-circle {
            width: 85px; height: 85px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 36px;
        }
        .icon-circle.success { background: #e8f5e9; color: #2e7d32; }
        .icon-circle.error   { background: #fdecea; color: #c62828; }
        h2 { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        h2.success { color: #2e7d32; }
        h2.error   { color: #c62828; }
        p  { color: #666; font-size: 14px; margin-bottom: 6px; }
        .booking-ref {
            background: #f0f7f3; border: 1px dashed #4B795D;
            border-radius: 8px; padding: 12px 20px; margin: 18px 0; font-size: 14px; color: #333;
        }
        .booking-ref span { font-weight: 700; color: #4B795D; font-size: 20px; }
        .partial-note {
            background: #fff3cd; border-radius: 8px;
            padding: 12px 15px; margin: 12px 0; font-size: 13px; color: #856404; text-align: left;
        }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .btn-main {
            background: #4B795D; color: white; border: none; padding: 12px 28px;
            border-radius: 8px; font-size: 14px; font-weight: 600;
            text-decoration: none; display: inline-block; margin: 5px; transition: background 0.3s;
        }
        .btn-main:hover { background: #3a5f47; color: white; text-decoration: none; }
        .btn-outline {
            background: white; color: #4B795D; border: 2px solid #4B795D;
            padding: 11px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;
            text-decoration: none; display: inline-block; margin: 5px; transition: all 0.3s;
        }
        .btn-outline:hover { background: #4B795D; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($success): ?>
            <div class="icon-circle success"><i class="fa fa-check"></i></div>
            <h2 class="success">Booking Confirmed!</h2>
            <p>Your payment was successful and your room has been reserved.</p>

            <?php if ($booking_id): ?>
            <div class="booking-ref">
                Booking Reference &nbsp;|&nbsp; <span>#<?php echo str_pad($booking_id, 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <?php endif; ?>

            <?php
            $bk = $booking ?? null;
            if ($bk && ($bk['payment_type'] ?? 'full') === 'partial'):
            ?>
            <div class="partial-note">
                <i class="fa fa-info-circle"></i>
                <strong>Partial Payment:</strong> You paid Rs. <?php echo number_format((float)$bk['amount'], 2); ?>.
                The remaining <strong>Rs. <?php echo number_format((float)$bk['remaining_amount'], 2); ?></strong> is due at check-in.
            </div>
            <?php endif; ?>

            <hr class="divider">
            <a href="my-bookings.php" class="btn-main"><i class="fa fa-list"></i> View My Bookings</a>
            <a href="hotels.php" class="btn-outline"><i class="fa fa-building"></i> Browse Hotels</a>

        <?php else: ?>
            <div class="icon-circle error"><i class="fa fa-exclamation"></i></div>
            <h2 class="error">Something Went Wrong</h2>
            <p><?php echo htmlspecialchars($error); ?></p>
            <hr class="divider">
            <a href="hotels.php" class="btn-main"><i class="fa fa-building"></i> Browse Hotels</a>
            <a href="index.php" class="btn-outline"><i class="fa fa-home"></i> Back to Home</a>
        <?php endif; ?>
    </div>
</body>
</html>
