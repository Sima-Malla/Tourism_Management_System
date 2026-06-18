<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    echo 0;
    exit();
}

$hotel_id = $_POST['id'];

// Delete hotel and cascade delete rooms and bookings
$delete_query = "DELETE FROM hotels WHERE hotel_id = $hotel_id";
$result = mysqli_query($conn, $delete_query);

if ($result) {
    echo 1;
} else {
    echo 0;
}
?>