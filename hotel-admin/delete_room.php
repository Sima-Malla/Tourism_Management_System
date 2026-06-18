<?php
session_start();
include("../connection/connect.php");

if (!isset($_SESSION['hotel_id'])) {
    echo 0;
    exit();
}

$room_id = $_POST['id'];
$hotel_id = $_SESSION['hotel_id'];

// Verify room belongs to this hotel
$verify_query = "SELECT r_id FROM rooms WHERE r_id = $room_id AND hotel_id = $hotel_id";
$verify_result = mysqli_query($db, $verify_query);

if ($verify_result && mysqli_num_rows($verify_result) > 0) {
    $delete_query = "DELETE FROM rooms WHERE r_id = $room_id AND hotel_id = $hotel_id";
    $result = mysqli_query($db, $delete_query);
    
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 0;
}
?>