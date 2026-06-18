<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

include 'connection/connect.php';
include 'header.php';

// Get user data
$user_email = $_SESSION['user_email'];
$user_query = "SELECT * FROM users WHERE Email = '$user_email'";
$user_result = mysqli_query($db, $user_query);
$user = mysqli_fetch_assoc($user_result);

$message = '';
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    
    $update_sql = "UPDATE users SET Name='$name', Phone='$phone' WHERE Email='$user_email'";
    if (mysqli_query($db, $update_sql)) {
        $message = '<div class="alert alert-success">Profile updated successfully!</div>';
        $user['Name'] = $name;
        $user['Phone'] = $phone;
    } else {
        $message = '<div class="alert alert-danger">Update failed!</div>';
    }
}

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($current_password == $user['Password']) {
        if ($new_password == $confirm_password) {
            $update_sql = "UPDATE users SET Password='$new_password' WHERE Email='$user_email'";
            if (mysqli_query($db, $update_sql)) {
                $message = '<div class="alert alert-success">Password changed successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Password update failed!</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">New passwords do not match!</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Current password is incorrect!</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Profile - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .profile-container {
            padding: 60px 0;
            background: #f8f9fa;
        }
        .profile-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .profile-card h3 {
            color: #4B795D;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: #4B795D;
            box-shadow: 0 0 0 0.2rem rgba(75, 121, 93, 0.25);
        }
        .btn-primary {
            background: #4B795D;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background: #3a5f47;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="about-bg">
            <div class="container">
                <div class="rl-banner">
                    <h2>My Profile</h2>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><span class="active">Profile</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <section class="profile-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <?php echo $message; ?>
                        
                        <div class="profile-card">
                            <h3><i class="fa fa-user"></i> Profile Information</h3>
                            
                            <form method="POST">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $user['Name']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" value="<?php echo $user['Email']; ?>" readonly style="background: #f5f5f5;">
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo $user['Phone']; ?>" required>
                                </div>
                                
                                <button type="submit" name="update_profile" class="btn btn-primary"><i class="fa fa-save"></i> Update Profile</button>
                                <a href="my-bookings.php" class="btn btn-secondary"><i class="fa fa-list"></i> View Bookings</a>
                            </form>
                        </div>
                        
                        <div class="profile-card">
                            <h3><i class="fa fa-lock"></i> Change Password</h3>
                            
                            <form method="POST">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                
                                <button type="submit" name="change_password" class="btn btn-primary"><i class="fa fa-key"></i> Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>