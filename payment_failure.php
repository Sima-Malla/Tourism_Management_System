<?php
session_start();
$pending = $_SESSION['pending_booking'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Cancelled - TourStay</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .icon-circle {
            width: 85px; height: 85px; border-radius: 50%;
            background: #fff8e1; color: #f9a825;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 36px;
        }
        h2 { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px; }
        p  { color: #666; font-size: 14px; margin-bottom: 6px; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .btn-main {
            background: #4B795D; color: white; border: none; padding: 12px 28px;
            border-radius: 8px; font-size: 14px; font-weight: 600;
            text-decoration: none; display: inline-block; margin: 5px; transition: background 0.3s;
        }
        .btn-main:hover { background: #3a5f47; color: white; text-decoration: none; }
        .btn-outline {
            background: white; color: #4B795D; border: 2px solid #4B795D;
            padding: 11px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;
            text-decoration: none; display: inline-block; margin: 5px; transition: all 0.3s;
        }
        .btn-outline:hover { background: #4B795D; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle"><i class="fa fa-times"></i></div>
        <h2>Payment Not Completed</h2>
        <p>You cancelled the payment or did not complete the eSewa login.</p>
        <p>Your booking has <strong>not</strong> been confirmed and no charge was made.</p>
        <hr class="divider">
        <?php if ($pending): ?>
            <a href="booking.php?book=<?php echo $pending['r_id']; ?>" class="btn-main">
                <i class="fa fa-refresh"></i> Try Again
            </a>
        <?php endif; ?>
        <a href="hotels.php" class="btn-outline"><i class="fa fa-building"></i> Browse Hotels</a>
    </div>
</body>
</html>
