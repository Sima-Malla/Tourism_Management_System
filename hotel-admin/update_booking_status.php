<?php
session_start();
include("../connection/connect.php");

if (!isset($_SESSION['hotel_id'])) {
    echo 0;
    exit();
}

$booking_id = $_POST['id'];
$status = $_POST['status'];
$hotel_id = $_SESSION['hotel_id'];

// Verify booking belongs to this hotel
$verify_query = "SELECT b.booking_id FROM booked b 
                JOIN rooms r ON b.r_id = r.r_id 
                WHERE b.booking_id = $booking_id AND r.hotel_id = $hotel_id";
$verify_result = mysqli_query($db, $verify_query);

if ($verify_result && mysqli_num_rows($verify_result) > 0) {
    $update_query = "UPDATE booked SET booking_status = '$status' WHERE booking_id = $booking_id";
    $result = mysqli_query($db, $update_query);
    
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 0;
}
?>