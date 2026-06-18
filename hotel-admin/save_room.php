<?php
session_start();
include("../connection/connect.php");

if (!isset($_SESSION['hotel_id'])) {
    echo 0;
    exit();
}

$r_id = $_POST['r_id'];
$hotel_id = $_SESSION['hotel_id'];
$rtype = $_POST['rtype'];
$rprice = $_POST['rprice'];
$rtext = $_POST['rtext'];

// Handle image upload
$rimage = '';
if (isset($_FILES['rimage']) && $_FILES['rimage']['error'] == 0) {
    $target_dir = "../admin/upload/";
    $file_extension = pathinfo($_FILES['rimage']['name'], PATHINFO_EXTENSION);
    $rimage = time() . '_' . rand(1000, 9999) . '.' . $file_extension;
    $target_file = $target_dir . $rimage;
    
    if (move_uploaded_file($_FILES['rimage']['tmp_name'], $target_file)) {
        // Image uploaded successfully
    } else {
        echo 0;
        exit();
    }
}

if ($r_id) {
    // Update existing room
    if ($rimage) {
        $query = "UPDATE rooms SET 
                  rtype = '$rtype',
                  rprice = '$rprice',
                  rtext = '$rtext',
                  rimage = '$rimage'
                  WHERE r_id = $r_id AND hotel_id = $hotel_id";
    } else {
        $query = "UPDATE rooms SET 
                  rtype = '$rtype',
                  rprice = '$rprice',
                  rtext = '$rtext'
                  WHERE r_id = $r_id AND hotel_id = $hotel_id";
    }
} else {
    // Insert new room
    if (!$rimage) {
        echo 0;
        exit();
    }
    $query = "INSERT INTO rooms (rtype, rprice, rtext, rimage, hotel_id) 
              VALUES ('$rtype', '$rprice', '$rtext', '$rimage', '$hotel_id')";
}

$result = mysqli_query($db, $query);

if ($result) {
    echo 1;
} else {
    echo 0;
}
?>