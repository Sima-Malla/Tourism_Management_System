<?php
$hotel_id = $_SESSION['hotel_id'];
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-calendar-check"></i> Hotel Bookings
            </h2>
            <p class="mb-0" style="color: #000000;">View and manage room bookings for your hotel</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Booking Records</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> Booking ID</th>
                                    <th><i class="fas fa-user"></i> Guest Name</th>
                                    <th><i class="fas fa-bed"></i> Room Type</th>
                                    <th><i class="fas fa-calendar"></i> Check-in</th>
                                    <th><i class="fas fa-calendar"></i> Check-out</th>
                                    <th><i class="fas fa-users"></i> Guests</th>
                                    <th><i class="fas fa-info-circle"></i> Status</th>
                                    <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                    <th><i class="fas fa-clock"></i> Booked Date</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $bookings_query = "SELECT b.*, r.rtype, u.Name as guest_name, u.Email, u.Phone 
                                              FROM booked b 
                                              JOIN rooms r ON b.r_id = r.r_id 
                                              JOIN users u ON b.ID = u.ID 
                                              WHERE r.hotel_id = $hotel_id 
                                              ORDER BY b.booked_at DESC";
                            $bookings_result = mysqli_query($db, $bookings_query);
                            
                            if ($bookings_result && mysqli_num_rows($bookings_result) > 0) {
                                while ($booking = mysqli_fetch_assoc($bookings_result)) {
                            ?>
                            <tr>
                                <td><?php echo $booking['booking_id']; ?></td>
                                <td><?php echo $booking['guest_name']; ?></td>
                                <td><?php echo $booking['rtype']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                                <td><?php echo $booking['guests']; ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $booking['booking_status'] == 'confirmed' ? 'success' : 
                                            ($booking['booking_status'] == 'pending' ? 'warning' : 'danger'); 
                                    ?>" style="padding: 8px 15px; border-radius: 20px; font-weight: 500;">
                                        <?php echo ucfirst($booking['booking_status']); ?>
                                    </span>
                                </td>
                                <td>R <?php echo number_format($booking['total_amount']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['booked_at'])); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-secondary" onclick="viewBooking(<?php echo $booking['booking_id']; ?>)" title="View Details" style="margin: 2px; border-radius: 20px; padding: 8px 12px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($booking['booking_status'] == 'pending') { ?>
                                        <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $booking['booking_id']; ?>, 'confirmed')" title="Confirm Booking" style="margin: 2px; border-radius: 20px; padding: 8px 12px;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="updateStatus(<?php echo $booking['booking_id']; ?>, 'cancelled')" title="Cancel Booking" style="margin: 2px; border-radius: 20px; padding: 8px 12px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No bookings found</td></tr>';
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

<script>
function viewBooking(id) {
    uni_modal('Booking Details', 'view_booking.php?id=' + id);
}

function updateStatus(id, status) {
    if (confirm('Are you sure you want to ' + status + ' this booking?')) {
        $.ajax({
            url: 'update_booking_status.php',
            method: 'POST',
            data: {id: id, status: status},
            success: function(resp) {
                if (resp == 1) {
                    alert('Booking status updated successfully');
                    location.reload();
                } else {
                    alert('Failed to update booking status');
                }
            }
        });
    }
}
</script>