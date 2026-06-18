<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    echo 0;
    exit();
}

$hotel_id = $_POST['hotel_id'];
$hotel_name = $_POST['hotel_name'];
$h_username = $_POST['h_username'];
$h_password = $_POST['h_password'];
$hotel_description = $_POST['hotel_description'];
$hotel_address = $_POST['hotel_address'];
$hotel_phone = $_POST['hotel_phone'];
$hotel_email = $_POST['hotel_email'];
$hotel_rating = $_POST['hotel_rating'];
$status = $_POST['status'];
$latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
$longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

// Handle photo upload
$hotel_image = '';
if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['hotel_image']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    
    if (in_array(strtolower($filetype), $allowed)) {
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $filetype;
        $upload_path = 'upload/' . $new_filename;
        
        if (move_uploaded_file($_FILES['hotel_image']['tmp_name'], $upload_path)) {
            $hotel_image = $new_filename;
        }
    }
}

if ($hotel_id) {
    // Update existing hotel
    $update_fields = "hotel_name = '$hotel_name',
                      h_username = '$h_username',
                      hotel_description = '$hotel_description',
                      hotel_address = '$hotel_address',
                      hotel_phone = '$hotel_phone',
                      hotel_email = '$hotel_email',
                      hotel_rating = '$hotel_rating',
                      status = '$status',
                      latitude = " . ($latitude !== null ? $latitude : 'NULL') . ",
                      longitude = " . ($longitude !== null ? $longitude : 'NULL');
    
    if (!empty($h_password)) {
        $update_fields .= ", h_password = '$h_password'";
    }
    
    if (!empty($hotel_image)) {
        $update_fields .= ", hotel_image = '$hotel_image'";
    }
    
    $query = "UPDATE hotels SET $update_fields WHERE hotel_id = $hotel_id";
} else {
    // Insert new hotel
    $query = "INSERT INTO hotels (hotel_name, h_username, h_password, hotel_description, hotel_address, hotel_phone, hotel_email, hotel_rating, status, latitude, longitude" . (!empty($hotel_image) ? ", hotel_image" : "") . ") 
              VALUES ('$hotel_name', '$h_username', '$h_password', '$hotel_description', '$hotel_address', '$hotel_phone', '$hotel_email', '$hotel_rating', '$status', " . ($latitude !== null ? $latitude : 'NULL') . ", " . ($longitude !== null ? $longitude : 'NULL') . (!empty($hotel_image) ? ", '$hotel_image'" : "") . ")";
}

$result = mysqli_query($conn, $query);

if ($result) {
    echo 1;
} else {
    echo 0;
}
?>