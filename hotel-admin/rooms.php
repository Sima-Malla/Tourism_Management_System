<?php
$hotel_id = $_SESSION['hotel_id'];
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                    <i class="fas fa-bed"></i> My Rooms
                </h2>
                <p class="mb-0" style="color: #000000;">Manage your hotel room listings</p>
            </div>
            <button class="btn btn-primary" onclick="uni_modal('Add New Room','manage_room.php')">
                <i class="fas fa-plus"></i> Add Room
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Room Directory</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-image"></i> Image</th>
                                    <th><i class="fas fa-bed"></i> Room Type</th>
                                    <th><i class="fas fa-dollar-sign"></i> Price</th>
                                    <th><i class="fas fa-info-circle"></i> Description</th>
                                    <th><i class="fas fa-check-circle"></i> Status</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $rooms_query = "SELECT * FROM rooms WHERE hotel_id = $hotel_id ORDER BY r_id DESC";
                            $rooms_result = mysqli_query($db, $rooms_query);
                            
                            while ($room = mysqli_fetch_assoc($rooms_result)) {
                                // Check if room is currently booked
                                $booked_check = mysqli_query($db, "SELECT * FROM booked WHERE r_id = {$room['r_id']} AND booking_status = 'confirmed' AND check_out >= CURDATE()");
                                $is_booked = mysqli_num_rows($booked_check) > 0;
                            ?>
                            <tr>
                                <td>
                                    <img src="../admin/upload/<?php echo $room['rimage']; ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td><?php echo $room['rtype']; ?></td>
                                <td>R <?php echo number_format($room['rprice']); ?></td>
                                <td><?php echo substr($room['rtext'], 0, 50) . '...'; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $is_booked ? 'danger' : 'success'; ?>" style="padding: 8px 15px; border-radius: 20px; font-weight: 500;">
                                        <?php echo $is_booked ? 'Booked' : 'Available'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-primary" onclick="uni_modal('Edit Room','manage_room.php?id=<?php echo $room['r_id']; ?>')" title="Edit Room" style="margin: 2px; border-radius: 20px; padding: 8px 12px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="delete_room(<?php echo $room['r_id']; ?>)" title="Delete Room" style="margin: 2px; border-radius: 20px; padding: 8px 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function delete_room(id) {
    if (confirm('Are you sure you want to delete this room?')) {
        $.ajax({
            url: 'delete_room.php',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                if (resp == 1) {
                    alert('Room deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete room');
                }
            }
        });
    }
}
</script>