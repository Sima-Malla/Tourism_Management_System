<?php
session_start();
include 'connection/connect.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: hotels.php');
    exit();
}

// Resolve user ID
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
} else {
    $user_email = $_SESSION['user_email'];
    $stmt = mysqli_prepare($db, "SELECT ID FROM users WHERE Email=?");
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $u = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$u) {
        header('Location: login.php');
        exit();
    }

    $user_id = (int) $u['ID'];
    $_SESSION['user_id'] = $user_id;
}

// Get POST data safely
$room_id = (int) ($_POST['room_id'] ?? 0);
$total_amount = (float) ($_POST['total_amount'] ?? 0);
$price_per_night = (float) ($_POST['price_per_night'] ?? 0);
$check_in = $_POST['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$guests = (int) ($_POST['guests'] ?? 1);
$payment_type = (isset($_POST['payment_type_val']) && $_POST['payment_type_val'] === 'partial') ? 'partial' : 'full';

if (!$room_id || $total_amount <= 0) {
    header('Location: hotels.php');
    exit();
}

// Fetch room info
$stmt = mysqli_prepare($db, "SELECT r_id, hotel_id, rprice FROM rooms WHERE r_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $room_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$room = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$room) {
    header('Location: hotels.php');
    exit();
}

// eSewa TEST credentials
$product_code = 'EPAYTEST';
$secret_key = '8gBm/:&EnhH.1/q';

// Amount formatting (STRICT)
$amount = sprintf('%.2f', $total_amount);
$tax_amount = '0';
$service_charge = '0';
$delivery_charge = '0';
$total_amount_esewa = $amount;

// Unique transaction ID
$transaction_uuid = uniqid('TS-' . $room_id . '-');

// Signature (STRICT FORMAT)
$message = "total_amount=$total_amount_esewa,transaction_uuid=$transaction_uuid,product_code=$product_code";
$signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

// Save booking directly into booked table with pending status
$full_total        = sprintf('%.2f', $payment_type === 'partial' ? $total_amount * 2 : $total_amount);
$remaining_amount  = $payment_type === 'partial' ? sprintf('%.2f', $total_amount) : '0.00';
$price_pn          = $price_per_night ?: $room['rprice'];

$ins = mysqli_prepare($db, "INSERT INTO booked 
    (ID, r_id, hotel_id, check_in, check_out, guests, price_per_night, total_amount, paid_amount, remaining_amount, payment_type, booking_status, payment_status, transaction_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?)");
mysqli_stmt_bind_param($ins, 'iiissiddddss',
    $user_id, $room['r_id'], $room['hotel_id'],
    $check_in, $check_out, $guests,
    $price_pn, $full_total, $amount, $remaining_amount,
    $payment_type, $transaction_uuid
);

if (!mysqli_stmt_execute($ins)) {
    die('Could not save booking: ' . mysqli_stmt_error($ins));
}
mysqli_stmt_close($ins);

// Keep session as backup reference
$_SESSION['pending_booking'] = [
    'transaction_uuid' => $transaction_uuid,
];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Redirecting to eSewa</title>
</head>

<body>

    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">

        <input type="hidden" name="amount" value="<?= $amount ?>">
        <input type="hidden" name="tax_amount" value="<?= $tax_amount ?>">
        <input type="hidden" name="total_amount" value="<?= $total_amount_esewa ?>">
        <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
        <input type="hidden" name="product_code" value="<?= $product_code ?>">

        <input type="hidden" name="product_service_charge" value="<?= $service_charge ?>">
        <input type="hidden" name="product_delivery_charge" value="<?= $delivery_charge ?>">

        <!-- IMPORTANT: SAME DOMAIN -->
        <input type="hidden" name="success_url" value="http://127.0.0.1/TourStay/payment_success.php">
        <input type="hidden" name="failure_url" value="http://127.0.0.1/TourStay/payment_failure.php">

        <!-- SIGNATURE -->
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" name="signature" value="<?= $signature ?>">
        <input type="hidden" name="signature_type" value="HMAC_SHA256">

    </form>

    <script>
        document.getElementById('esewaForm').submit();
    </script>

</body>

</html>