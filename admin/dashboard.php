<?php
include('../connection/connect.php');

// Get statistics with error checking
$booking_result = mysqli_query($db, "SELECT * FROM booking");
$total_rooms = $booking_result ? mysqli_num_rows($booking_result) : 0;

$users_result = mysqli_query($db, "SELECT * FROM users");
$total_users = $users_result ? mysqli_num_rows($users_result) : 0;

$contacts_result = mysqli_query($db, "SELECT * FROM contact");
$total_contacts = $contacts_result ? mysqli_num_rows($contacts_result) : 0;
?>

<div class="row">
    <div class="col-md-12">
        <h2>Admin Dashboard</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_rooms; ?></h4>
                        <p>Total Rooms</p>
                    </div>
                    <div>
                        <i class="fa fa-bed fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_users; ?></h4>
                        <p>Total Users</p>
                    </div>
                    <div>
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo $total_contacts; ?></h4>
                        <p>Contact Messages</p>
                    </div>
                    <div>
                        <i class="fa fa-envelope fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Activities</h5>
            </div>
            <div class="card-body">
                <p>Welcome to TourStay Admin Panel. Use the sidebar to manage your hotel operations.</p>
                <ul>
                    <li>Manage room listings and availability</li>
                    <li>View and handle bookings</li>
                    <li>Monitor user registrations</li>
                    <li>Respond to customer inquiries</li>
                </ul>
            </div>
        </div>
    </div>
</div>