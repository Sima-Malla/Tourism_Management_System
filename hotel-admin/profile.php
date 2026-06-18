<?php
$hotel_id = $_SESSION['hotel_id'];

// Get hotel information
$hotel_query = "SELECT * FROM hotels WHERE hotel_id = $hotel_id";
$hotel_result = mysqli_query($db, $hotel_query);
$hotel = mysqli_fetch_assoc($hotel_result);

// Handle form submission
if (isset($_POST['update_profile'])) {
    $hotel_name = $_POST['hotel_name'];
    $hotel_address = $_POST['hotel_address'];
    $hotel_phone = $_POST['hotel_phone'];
    $hotel_email = $_POST['hotel_email'];
    $hotel_description = $_POST['hotel_description'];
    $hotel_rating = $_POST['hotel_rating'];
    
    $update_query = "UPDATE hotels SET 
                     hotel_name = '$hotel_name',
                     hotel_address = '$hotel_address', 
                     hotel_phone = '$hotel_phone',
                     hotel_email = '$hotel_email',
                     hotel_description = '$hotel_description',
                     hotel_rating = '$hotel_rating'
                     WHERE hotel_id = $hotel_id";
    
    if (mysqli_query($db, $update_query)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = '?page=profile';</script>";
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($db) . "');</script>";
    }
}
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-building"></i> Hotel Profile
            </h2>
            <p class="mb-0" style="color: #000000;">Manage your hotel information and settings</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="color: #4B795D; font-weight: 600;"><i class="fas fa-edit"></i> Hotel Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_name">Hotel Name</label>
                                    <input type="text" class="form-control" id="hotel_name" name="hotel_name" 
                                           value="<?php echo htmlspecialchars($hotel['hotel_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_email">Email Address</label>
                                    <input type="email" class="form-control" id="hotel_email" name="hotel_email" 
                                           value="<?php echo htmlspecialchars($hotel['hotel_email']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_phone">Phone Number</label>
                                    <input type="text" class="form-control" id="hotel_phone" name="hotel_phone" 
                                           value="<?php echo htmlspecialchars($hotel['hotel_phone']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hotel_rating">Hotel Rating</label>
                                    <select class="form-control" id="hotel_rating" name="hotel_rating" required>
                                        <option value="1" <?php echo $hotel['hotel_rating'] == 1 ? 'selected' : ''; ?>>1 Star</option>
                                        <option value="2" <?php echo $hotel['hotel_rating'] == 2 ? 'selected' : ''; ?>>2 Stars</option>
                                        <option value="3" <?php echo $hotel['hotel_rating'] == 3 ? 'selected' : ''; ?>>3 Stars</option>
                                        <option value="4" <?php echo $hotel['hotel_rating'] == 4 ? 'selected' : ''; ?>>4 Stars</option>
                                        <option value="5" <?php echo $hotel['hotel_rating'] == 5 ? 'selected' : ''; ?>>5 Stars</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hotel_address">Hotel Address</label>
                            <textarea class="form-control" id="hotel_address" name="hotel_address" rows="3" required><?php echo htmlspecialchars($hotel['hotel_address']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="hotel_description">Hotel Description</label>
                            <textarea class="form-control" id="hotel_description" name="hotel_description" rows="4"><?php echo htmlspecialchars($hotel['hotel_description'] ?? ''); ?></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="color: #4B795D; font-weight: 600;"><i class="fas fa-chart-bar"></i> Hotel Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 style="color: #4B795D;"><?php 
                                    $rooms_count = mysqli_query($db, "SELECT COUNT(*) as count FROM rooms WHERE hotel_id = $hotel_id");
                                    echo $rooms_count ? mysqli_fetch_assoc($rooms_count)['count'] : 0;
                                ?></h4>
                                <p style="color: #000000;">Total Rooms</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 style="color: #4B795D;"><?php 
                                    $bookings_count = mysqli_query($db, "SELECT COUNT(*) as count FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id");
                                    echo $bookings_count ? mysqli_fetch_assoc($bookings_count)['count'] : 0;
                                ?></h4>
                                <p style="color: #000000;">Total Bookings</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 style="color: #4B795D;"><?php echo $hotel['hotel_rating']; ?> <i class="fas fa-star"></i></h4>
                                <p style="color: #000000;">Hotel Rating</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 style="color: #4B795D;"><?php echo ucfirst($hotel['status']); ?></h4>
                                <p style="color: #000000;">Hotel Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>