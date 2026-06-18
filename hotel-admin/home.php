<?php
$hotel_id = $_SESSION['hotel_id'];

// Get statistics with error checking
$rooms_result = mysqli_query($db, "SELECT COUNT(*) as count FROM rooms WHERE hotel_id = $hotel_id");
$rooms_count = $rooms_result ? mysqli_fetch_assoc($rooms_result)['count'] : 0;

$bookings_result = mysqli_query($db, "SELECT COUNT(*) as count FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id");
$bookings_count = $bookings_result ? mysqli_fetch_assoc($bookings_result)['count'] : 0;

$revenue_result = mysqli_query($db, "SELECT SUM(total_amount) as total FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'confirmed'");
$revenue = $revenue_result ? (mysqli_fetch_assoc($revenue_result)['total'] ?? 0) : 0;

$guests_result = mysqli_query($db, "SELECT COUNT(DISTINCT b.ID) as count FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id");
$guests_count = $guests_result ? mysqli_fetch_assoc($guests_result)['count'] : 0;
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-chart-line"></i> <?php echo $hotel['hotel_name']; ?> Dashboard
            </h2>
            <p class="mb-0" style="color: #000000;">Welcome back! Here's your hotel management overview.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $rooms_count; ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Total Rooms</div>
                        </div>
                        <div style="background: #4B795D; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-bed"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $bookings_count; ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Total Bookings</div>
                        </div>
                        <div style="background: #E8E1D8; color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                R <?php echo number_format($revenue); ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Total Revenue</div>
                        </div>
                        <div style="background: #4B795D; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $guests_count; ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Total Guests</div>
                        </div>
                        <div style="background: #E8E1D8; color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4B795D; font-weight: 600;">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=rooms" class="btn btn-primary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-bed mb-2" style="font-size: 1.5rem; display: block;"></i>
                                Manage Rooms
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=bookings" class="btn btn-secondary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-calendar-check mb-2" style="font-size: 1.5rem; display: block;"></i>
                                View Bookings
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=profile" class="btn btn-primary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-building mb-2" style="font-size: 1.5rem; display: block;"></i>
                                Hotel Profile
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="../login.php" class="btn btn-secondary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-sign-out-alt mb-2" style="font-size: 1.5rem; display: block;"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4B795D; font-weight: 600;">
                        <i class="fas fa-list"></i> Recent Bookings
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> Booking ID</th>
                                    <th><i class="fas fa-bed"></i> Room</th>
                                    <th><i class="fas fa-user"></i> Guest</th>
                                    <th><i class="fas fa-calendar"></i> Check-in</th>
                                    <th><i class="fas fa-calendar"></i> Check-out</th>
                                    <th><i class="fas fa-info-circle"></i> Status</th>
                                    <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $bookings_query = "SELECT b.*, r.rtype, u.Name as guest_name
                                                  FROM booked b 
                                                  JOIN rooms r ON b.r_id = r.r_id 
                                                  JOIN users u ON b.ID = u.ID 
                                                  WHERE r.hotel_id = $hotel_id 
                                                  ORDER BY b.booked_at DESC LIMIT 10";
                                $bookings_result = mysqli_query($db, $bookings_query);
                                
                                if ($bookings_result && mysqli_num_rows($bookings_result) > 0) {
                                    while ($booking = mysqli_fetch_assoc($bookings_result)) {
                                ?>
                                <tr>
                                    <td><?php echo $booking['booking_id']; ?></td>
                                    <td><?php echo $booking['rtype']; ?></td>
                                    <td><?php echo $booking['guest_name']; ?></td>
                                    <td><?php echo $booking['check_in']; ?></td>
                                    <td><?php echo $booking['check_out']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $booking['booking_status'] == 'confirmed' ? 'success' : 'warning'; ?>" style="padding: 8px 15px; border-radius: 20px; font-weight: 500;">
                                            <?php echo ucfirst($booking['booking_status']); ?>
                                        </span>
                                    </td>
                                    <td>R <?php echo number_format($booking['total_amount']); ?></td>
                                </tr>
                                <?php }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No bookings found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>