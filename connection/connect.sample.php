<?php
$servername = "localhost";
$username   = "your_db_username";
$password   = "your_db_password";
$dbname     = "your_db_name";

$db = mysqli_connect($servername, $username, $password, $dbname);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
