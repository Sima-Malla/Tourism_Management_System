<?php
include 'connection/connect.php';
if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $result = mysqli_query($db, "SELECT ID FROM users WHERE Email = '$email'");
    echo mysqli_num_rows($result) > 0 ? '1' : '0';
}
