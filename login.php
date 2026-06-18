<?php
include 'connection/connect.php';
session_start();

$message = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin credentials first
    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_name'] = 'Administrator';
        header("location: admin/index.php");
        exit;
    }

    // Check hotel credentials
    $hotel_sql = "SELECT * FROM hotels WHERE h_username = '$username' AND h_password = '$password' AND status = 'active'";
    $hotel_result = mysqli_query($db, $hotel_sql);
    if ($hotel_result && mysqli_num_rows($hotel_result) > 0) {
        $hotel = mysqli_fetch_assoc($hotel_result);
        $_SESSION['hotel_id'] = $hotel['hotel_id'];
        $_SESSION['hotel_name'] = $hotel['hotel_name'];
        header("location: hotel-admin/index.php");
        exit;
    }

    // Check user credentials
    $user_sql = "SELECT * FROM users WHERE Email = '$username' AND Password = '$password'";
    $user_result = mysqli_query($db, $user_sql);
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user = mysqli_fetch_assoc($user_result);
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_name'] = $user['Name'];
        header("location: index.php");
        exit;
    }

    // If no match found
    $message = '<div class="alert alert-danger">Invalid credentials! Please check your username and password.</div>';
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <style>
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100vh;
    }

    .login-container {
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/hotel-cover.jpg');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 0;
    }

    .login-form {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 100%;
    }

    .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 0 15px;
    }

    .btn-login {
        background: #4B795D;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        width: 100%;
    }

    .btn-login:hover {
        background: #3a5f47;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="login-container">
        <div class="login-form">
            <h3 class="text-center mb-4" style="padding:10px; margin:10px; font-Size:26px;">Login</h3>

            <?php echo $message; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom:10px;">Username / Email</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username or email"
                        required>
                </div>

                <div class="form-group">
                    <label class="control-label" style="margin-bottom:10px;">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password"
                        required>
                </div>

                <button type="submit" name="login" class="btn btn-login">Login</button>
            </form>

            <div class="text-center mt-3" style="margin-top:10px;">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>