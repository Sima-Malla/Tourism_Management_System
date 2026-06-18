<?php include('../connection/connect.php'); ?>

<div class="row">
    <div class="col-md-12">
        <h2>Manage Bookings</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Room Bookings</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Room Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT b.booking_id, u.username, u.email, u.phone, r.room_type, h.hotel_name, 
                                      b.check_in, b.check_out, b.guests, b.total_amount, b.booking_status, b.payment_status 
                                      FROM booked b 
                                      LEFT JOIN users u ON b.ID = u.ID 
                                      LEFT JOIN rooms r ON b.r_id = r.r_id 
                                      LEFT JOIN hotels h ON b.hotel_id = h.hotel_id 
                                      ORDER BY b.booking_id DESC";
                            $result = mysqli_query($db, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['booking_id']; ?></td>
                                        <td><?php echo $row['username'] ?? 'N/A'; ?></td>
                                        <td><?php echo $row['email'] ?? 'N/A'; ?></td>
                                        <td><?php echo $row['phone'] ?? 'N/A'; ?></td>
                                        <td><?php echo $row['room_type'] ?? 'N/A'; ?></td>
                                        <td><?php echo $row['check_in']; ?></td>
                                        <td><?php echo $row['check_out']; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $row['booking_status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($row['booking_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="updateStatus(<?php echo $row['booking_id']; ?>)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBooking(<?php echo $row['booking_id']; ?>)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                echo '<tr><td colspan="9" class="text-center">No bookings found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Create Bookings Table</h5>
            </div>
            <div class="card-body">
                <p>If you don't have a bookings table, click the button below to create it:</p>
                <button class="btn btn-primary" onclick="createBookingsTable()">Create Bookings Table</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateStatus(id) {
        alert('Update booking status for ID: ' + id);
    }

    function deleteBooking(id) {
        if (confirm('Are you sure you want to delete this booking?')) {
            // Add delete functionality
            alert('Delete booking ID: ' + id);
        }
    }

    function createBookingsTable() {
        if (confirm('This will create a new bookings table. Continue?')) {
            window.location.href = '?page=bookings&action=create_table';
        }
    }
</script>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'create_table') {
    $sql = "CREATE TABLE IF NOT EXISTS booked (
        booking_id INT AUTO_INCREMENT PRIMARY KEY,
        ID INT NOT NULL,
        r_id INT NOT NULL,
        hotel_id INT NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        guests INT NOT NULL,
        price_per_night DECIMAL(10,2) NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        booking_status VARCHAR(20) DEFAULT 'pending',
        payment_status VARCHAR(20) DEFAULT 'pending',
        transaction_id VARCHAR(100),
        booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ID) REFERENCES users(ID),
        FOREIGN KEY (r_id) REFERENCES rooms(r_id),
        FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id)
    )";

    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Bookings table created successfully!'); window.location.href='?page=bookings';</script>";
    } else {
        echo "<script>alert('Error creating table: " . mysqli_error($db) . "');</script>";
    }
}
?>