<?php
session_start();
include 'connection/connect.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    echo 0;
    exit();
}

$booking_id = $_POST['booking_id'];

// Get user ID
$user_email = $_SESSION['user_email'];
$user_query = "SELECT ID FROM users WHERE Email = '$user_email'";
$user_result = mysqli_query($db, $user_query);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['ID'];

// Verify booking belongs to user and update status
$update_query = "UPDATE booked SET booking_status = 'cancelled' WHERE booking_id = $booking_id AND ID = $user_id";
$result = mysqli_query($db, $update_query);

if ($result && mysqli_affected_rows($db) > 0) {
    echo 1;
} else {
    echo 0;
}
?>