<?php
session_start();
include 'connection/connect.php';

$message = '';
if (isset($_POST['hotel_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hotel admin login
    $sql = "SELECT * FROM hotels WHERE h_username = '$username' AND h_password = '$password' AND status = 'active'";
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $hotel = mysqli_fetch_assoc($result);
        $_SESSION['hotel_id'] = $hotel['hotel_id'];
        $_SESSION['hotel_name'] = $hotel['hotel_name'];
        $_SESSION['hotel_admin'] = true;
        header("location: hotel-admin/index.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Invalid hotel credentials!</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hotel Admin Login - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/hotel-cover.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
    <div class="login-form">
        <h3 class="text-center mb-4">Hotel Admin Login</h3>
        
        <?php echo $message; ?>
        
        <form method="POST">
            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" name="hotel_login" class="btn btn-login">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="login.php">Back to Main Login</a>
        </div>
    </div>
</body>
</html>