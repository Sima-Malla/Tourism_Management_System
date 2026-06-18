<?php
include 'db_connect.php';

$hotel_id = $_GET['id'];
$query = "SELECT * FROM hotels WHERE hotel_id = $hotel_id";
$result = mysqli_query($conn, $query);
$hotel = mysqli_fetch_assoc($result);

// Get room count
$rooms_query = "SELECT COUNT(*) as count FROM rooms WHERE hotel_id = $hotel_id";
$rooms_result = mysqli_query($conn, $rooms_query);
$rooms_count = mysqli_fetch_assoc($rooms_result)['count'];

// Get booking count
$bookings_query = "SELECT COUNT(*) as count FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id";
$bookings_result = mysqli_query($conn, $bookings_query);
$bookings_count = mysqli_fetch_assoc($bookings_result)['count'];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <th>Hotel Name:</th>
                    <td><?php echo $hotel['hotel_name']; ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td><?php echo $hotel['h_username']; ?></td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td><?php echo $hotel['hotel_description']; ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo $hotel['hotel_address']; ?></td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td><?php echo $hotel['hotel_phone']; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $hotel['hotel_email']; ?></td>
                </tr>
                <tr>
                    <th>Rating:</th>
                    <td>
                        <div class="text-warning">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $hotel['hotel_rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                            }
                            echo " ({$hotel['hotel_rating']})";
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <span class="badge badge-<?php echo $hotel['status'] == 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($hotel['status']); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Created:</th>
                    <td><?php echo date('M d, Y', strtotime($hotel['created_at'])); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <h3 class="text-primary"><?php echo $rooms_count; ?></h3>
                            <p>Total Rooms</p>
                        </div>
                        <div class="col-6 text-center">
                            <h3 class="text-success"><?php echo $bookings_count; ?></h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>