<?php
session_start();
include 'connection/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to rate hotels.']);
    exit();
}

$user_email = $_SESSION['user_email'];
$user_query = "SELECT ID FROM users WHERE Email = '$user_email'";
$user_result = mysqli_query($db, $user_query);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['ID'];

$hotel_id = (int)$_POST['hotel_id'];
$rating   = (int)$_POST['rating'];
$review   = mysqli_real_escape_string($db, trim($_POST['review'] ?? ''));

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating value.']);
    exit();
}

// Check if user has a confirmed booking at this hotel
$booking_check = "SELECT booking_id FROM booked WHERE ID = $user_id AND hotel_id = $hotel_id AND booking_status = 'confirmed' LIMIT 1";
$booking_result = mysqli_query($db, $booking_check);
if (mysqli_num_rows($booking_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'You can only rate hotels you have stayed at.']);
    exit();
}

// Insert or update review
$sql = "INSERT INTO hotel_reviews (hotel_id, user_id, rating, review)
        VALUES ($hotel_id, $user_id, $rating, '$review')
        ON DUPLICATE KEY UPDATE rating = $rating, review = '$review', created_at = NOW()";

if (mysqli_query($db, $sql)) {
    // Recalculate and update hotel_rating
    $avg_query = "SELECT ROUND(AVG(rating), 1) as avg_rating FROM hotel_reviews WHERE hotel_id = $hotel_id";
    $avg_result = mysqli_query($db, $avg_query);
    $avg = mysqli_fetch_assoc($avg_result)['avg_rating'];
    mysqli_query($db, "UPDATE hotels SET hotel_rating = $avg WHERE hotel_id = $hotel_id");

    echo json_encode(['success' => true, 'message' => 'Thank you for your rating!', 'new_rating' => $avg]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit rating.']);
}
?>
