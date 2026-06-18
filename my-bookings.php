<?php
session_start();
include 'header.php';
include("connection/connect.php");

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Get user bookings
$user_email = $_SESSION['user_email'];
$user_query = "SELECT * FROM users WHERE Email = '$user_email'";
$user_result = mysqli_query($db, $user_query);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['ID'];

$bookings_query = "SELECT b.*, r.rtype, r.rimage, h.hotel_name 
                   FROM booked b 
                   JOIN rooms r ON b.r_id = r.r_id 
                   JOIN hotels h ON r.hotel_id = h.hotel_id 
                   WHERE b.ID = '$user_id' 
                   ORDER BY b.booking_id DESC";
$bookings_result = mysqli_query($db, $bookings_query);

if (!$bookings_result) {
    die("Query failed: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Bookings - TourStay</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <style>
        .bookings-section {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .bookings-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .bookings-table table {
            width: 100%;
            margin: 0;
        }

        .bookings-table thead {
            background: #4B795D;
            color: white;
        }

        .bookings-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .bookings-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .bookings-table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-paid {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-cancel:hover { background: #b02a37; }

        .no-bookings {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="about-bg">
            <div class="container">
                <div class="rl-banner">
                    <h2>My Bookings</h2>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><span class="active">My Bookings</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <section class="bookings-section">
            <div class="container">
                <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                    <div class="bookings-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Hotel</th>
                                    <th>Room Type</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Guests</th>
                                    <th>Amount</th>
                                    <th>Booked Date</th>
                                    <th>Booking Status</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = mysqli_fetch_assoc($bookings_result)): ?>
                                    <tr>
                                        <td>#<?php echo $booking['booking_id']; ?></td>
                                        <td><?php echo $booking['hotel_name']; ?></td>
                                        <td><?php echo $booking['rtype']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                                        <td><?php echo $booking['guests']; ?></td>
                                        <td>Rs. <?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['booked_at'])); ?></td>
                                        <td><span class="badge badge-<?php echo $booking['booking_status']; ?>"><?php echo ucfirst($booking['booking_status']); ?></span></td>
                                        <td><span class="badge badge-<?php echo $booking['payment_status']; ?>"><?php echo ucfirst($booking['payment_status']); ?></span></td>
                                        <td>
                                            <?php if (in_array($booking['booking_status'], ['pending', 'confirmed']) && $booking['check_in'] >= date('Y-m-d')): ?>
                                                <button class="btn-cancel" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">Cancel</button>
                                            <?php else: ?>
                                                <span style="color:#aaa; font-size:13px;">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-bookings">
                        <h3>No Bookings Found</h3>
                        <p>You haven't made any bookings yet.</p>
                        <a href="hotels.php" style="background: #4B795D; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px;">Browse Hotels</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php include 'footer.php'; ?>
    </div>
    <script src="js/jquery.min.js"></script>
    <script>
        function cancelBooking(bookingId) {
            if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) return;
            $.ajax({
                url: 'cancel_booking.php',
                method: 'POST',
                data: { booking_id: bookingId },
                success: function(resp) {
                    if (resp == 1) {
                        location.reload();
                    } else {
                        alert('Failed to cancel booking. Please try again.');
                    }
                }
            });
        }
    </script>
</body>

</html>