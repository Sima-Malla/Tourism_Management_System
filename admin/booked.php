<?php include('../connection/connect.php'); ?>
<link rel="stylesheet" href="admin-style.css">

<?php
// Auto-complete confirmed bookings where check_out has passed
mysqli_query($conn, "UPDATE booked SET booking_status = 'completed' WHERE booking_status = 'confirmed' AND check_out < CURDATE()");

// Handle status update via AJAX
if (isset($_POST['update_status'])) {
    $booking_id     = (int)$_POST['booking_id'];
    $booking_status = mysqli_real_escape_string($conn, $_POST['booking_status']);
    $allowed = ['pending', 'confirmed', 'cancelled', 'completed'];
    if (in_array($booking_status, $allowed)) {
        $q = "UPDATE booked SET booking_status = '$booking_status' WHERE booking_id = $booking_id AND booking_status != 'completed'";
        echo mysqli_query($conn, $q) ? '1' : '0';
    } else {
        echo '0';
    }
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM booked WHERE booking_id = $id");
    echo "<script>alert('Booking deleted successfully!'); window.location.href='?page=booked';</script>";
}
?>

<style>
.booked-table thead th {
    background: #E8E1D8;
    color: #4B795D;
    border: none;
    padding: 15px;
    font-weight: 600;
}
.booked-table tbody tr { transition: all 0.3s ease; }
.booked-table tbody tr:hover {
    background: #fdf6f0;
}
.booked-table td {
    padding: 12px 15px;
    vertical-align: middle;
    border: none;
    border-bottom: 1px solid #f0f0f0;
}
.btn-action {
    margin: 2px;
    border-radius: 20px;
    padding: 6px 12px;
    transition: all 0.3s ease;
}
.status-select {
    border-radius: 20px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    border: 2px solid #ddd;
    cursor: pointer;
    outline: none;
    transition: all 0.3s;
}
.status-pending   { background: #fff3cd; color: #856404; border-color: #ffc107; }
.status-confirmed { background: #d4edda; color: #155724; border-color: #28a745; }
.status-cancelled { background: #f8d7da; color: #721c24; border-color: #dc3545; }
.status-completed { background: #d1ecf1; color: #0c5460; border-color: #17a2b8; }
.saving-indicator { font-size: 11px; color: #28a745; display: none; margin-left: 5px; }
</style>

<div class="container-fluid" style="padding: 20px;">
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-calendar-check"></i> Bookings Management
            </h2>
            <p class="mb-0" style="color: #64748b;">View and manage all room bookings</p>
        </div>
    </div>

    <div class="card admin-card">
        <div class="card-header">
            <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-list"></i> All Bookings
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table booked-table">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Customer</th>
                            <th>Hotel</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Guests</th>
                            <th>Paid Amount</th>
                            <th>Remaining</th>
                            <th>Payment</th>
                            <th>Booking Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query  = "SELECT b.*, u.Name, u.Email, u.Phone, r.rtype, h.hotel_name
                                   FROM booked b
                                   LEFT JOIN users u ON b.ID = u.ID
                                   LEFT JOIN rooms r ON b.r_id = r.r_id
                                   LEFT JOIN hotels h ON b.hotel_id = h.hotel_id
                                   ORDER BY b.booking_id DESC";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                $status = $row['booking_status'];
                        ?>
                        <tr id="row-<?php echo $row['booking_id']; ?>">
                            <td><strong>#<?php echo $row['booking_id']; ?></strong></td>
                            <td>
                                <div><?php echo htmlspecialchars($row['Name'] ?? 'N/A'); ?></div>
                                <small style="color:#999;"><?php echo htmlspecialchars($row['Email'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['hotel_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['rtype'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['check_in'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['check_out'])); ?></td>
                            <td><?php echo $row['guests']; ?></td>
                            <td>Rs. <?php echo number_format($row['paid_amount'], 2); ?></td>
                            <td>Rs. <?php echo number_format($row['remaining_amount'], 2); ?></td>
                            <td>
                                <?php $display_status = $row['payment_type'] == 'partial' ? 'pending' : $row['payment_status']; ?>
                                <span class="badge badge-<?php echo $display_status == 'paid' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($display_status); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($status === 'completed'): ?>
                                    <span class="status-select status-completed" style="cursor:default; padding:6px 14px; display:inline-block;">Completed</span>
                                <?php elseif ($status === 'cancelled'): ?>
                                    <span class="status-select status-cancelled" style="cursor:default; padding:6px 14px; display:inline-block;">Cancelled</span>
                                <?php else: ?>
                                    <select class="status-select status-<?php echo $status; ?>"
                                            onchange="updateStatus(<?php echo $row['booking_id']; ?>, this)">
                                        <option value="pending"   <?php echo $status == 'pending'   ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $status == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="cancelled" <?php echo $status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <span class="saving-indicator" id="saving-<?php echo $row['booking_id']; ?>">
                                        <i class="fa fa-check"></i> Saved
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-action"
                                        onclick="deleteBooking(<?php echo $row['booking_id']; ?>)"
                                        title="Delete Booking">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                            echo '<tr><td colspan="12" class="text-center" style="padding:40px;">No bookings found.</td></tr>';
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(bookingId, selectEl) {
    var newStatus = selectEl.value;

    // Update dropdown color
    selectEl.className = 'status-select status-' + newStatus;

    $.ajax({
        url: '?page=booked',
        method: 'POST',
        data: { update_status: 1, booking_id: bookingId, booking_status: newStatus },
        success: function(resp) {
            var indicator = $('#saving-' + bookingId);
            if (resp == 1) {
                indicator.html('<i class="fa fa-check"></i> Saved').css('color', '#28a745').show();
            } else {
                indicator.html('<i class="fa fa-times"></i> Failed').css('color', '#dc3545').show();
            }
            setTimeout(function() { indicator.fadeOut(); }, 2000);
        }
    });
}

function deleteBooking(id) {
    if (confirm('Are you sure you want to delete this booking? This cannot be undone.')) {
        window.location.href = '?page=booked&delete=' + id;
    }
}
</script>
