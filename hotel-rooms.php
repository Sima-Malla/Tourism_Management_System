<?php include 'header.php'; ?>
<?php include("connection/connect.php"); ?>

<?php
$hotel_id = isset($_GET['hotel_id']) ? (int) $_GET['hotel_id'] : 0;

// Get hotel information
$hotel_query = "SELECT * FROM hotels WHERE hotel_id = $hotel_id AND status = 'active'";
$hotel_result = mysqli_query($db, $hotel_query);

if (mysqli_num_rows($hotel_result) == 0) {
    header('Location: hotels.php');
    exit();
}

$hotel = mysqli_fetch_assoc($hotel_result);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $hotel['hotel_name']; ?> - Rooms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <style>
    .hotel-header {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('admin/upload/ft-img.jpg');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        color: white;
    }

    .hero-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
    }

    .hero-text {
        text-align: center;
        color: white;
    }

    .hero-text h1 {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 15px;
        color: white;
    }

    .hero-text p {
        color: white;
    }

    .hotel-rating {
        color: #ffc107;
        margin-bottom: 15px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .hotel-rating .fa {
        font-size: 16px;
        color: #ffc107;
    }

    .search-container {
        width: 100%;
        max-width: 900px;
    }

    .search-room {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .search-room h3 {
        color: #333;
        font-size: 24px;
        margin-bottom: 25px;
        text-align: center;
    }

    .search-form .form-row {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-form .form-group {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-form select {
        width: 100%;
        height: 50px;
        padding: 0 40px 0 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: #666;
        background: white;
        cursor: pointer;
    }

    .search-form .form-group i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
    }

    .btn-search {
        width: 100%;
        height: 50px;
        background: #4B795D;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-search:hover {
        background: #3a5f47;
    }

    .btn-search i {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .search-form .form-row {
            flex-direction: column;
        }

        .search-form .form-group {
            width: 100%;
        }
    }

    .rooms-section {
        padding: 10px 0;
        background: #f8f9fa;
    }

    .room-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 0;
        transition: transform 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .room-card:hover {
        transform: translateY(-5px);
    }

    .room-card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .room-info {
        padding: 25px;
    }

    .room-info h3 {
        color: #333;
        margin-bottom: 15px;
        font-size: 24px;
    }

    .room-info p {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .room-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .room-price h5 {
        color: #4B795D;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .room-price span {
        color: #999;
        font-size: 14px;
    }

    .btn-book {
        background: #4B795D;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
        width: 100%;
        text-align: center;
    }

    .btn-book:hover {
        background: #3a5f47;
        color: white;
        text-decoration: none;
    }

    .manage-items {
        display: flex !important;
        flex-wrap: wrap !important;
    }

    .des-sort {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .recommended-room {
        border: 2px solid #4B795D;
        position: relative;
    }

    .recommendation-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #4B795D;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        z-index: 2;
    }

    .recommendation-badge i {
        margin-right: 3px;
    }

    .recommended-room .room-card {
        box-shadow: 0 8px 25px rgba(75, 121, 93, 0.2);
    }

    .recommended-room:hover {
        transform: translateY(-8px);
    }

    /* Star rating selector */
    .star-selector {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 4px;
    }

    .star-selector input {
        display: none;
    }

    .star-selector label {
        font-size: 28px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-selector input:checked~label,
    .star-selector label:hover,
    .star-selector label:hover~label {
        color: #ffc107;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="hotel-header">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1><?php echo $hotel['hotel_name']; ?></h1>
                        <p><i class="fa fa-map-marker"></i> <?php echo $hotel['hotel_address']; ?></p>
                        <p><?php echo $hotel['hotel_description']; ?></p>
                        <div class="hotel-rating">
                            <?php
                            $rating = $hotel['hotel_rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                            }
                            echo " ({$rating})";
                            ?>
                        </div>
                    </div>
                    <div class="search-container">
                        <div class="search-room">
                            <h3>Search Rooms in This Hotel</h3>
                            <form class="search-form" action='hotel-search.php' method='get'>
                                <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="date" name="check_in" class="form-control" id="check_in"
                                            placeholder="Check-in Date" required
                                            min="<?php echo date('Y-m-d'); ?>"
                                            style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; color: #666;">
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="check_out" class="form-control" id="check_out"
                                            placeholder="Check-out Date" required
                                            min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                            style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; color: #666;">
                                    </div>
                                    <div class="form-group">
                                        <select name='type' required>
                                            <option value="">Select Room Type</option>
                                            <option value='Single Room'>Single</option>
                                            <option value='Double Room'>Double</option>
                                            <option value='First Room'>First class</option>
                                        </select>
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <style>

                                    </style>
                                    <div class="form-group ">
                                        <select name='adult' required>
                                            <option value="">Adults</option>
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>

                                        </select>
                                        <i class=" fa fa-user"></i>
                                    </div>
                                    <div class="form-group">
                                        <select name='child' required>
                                            <option value="">Children</option>
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>

                                        </select>
                                        <i class="fa fa-child"></i>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name='search' class="btn-search">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="rooms-section">
            <div class="container">
                <div class="row">
                    <?php
                    // Debug: Check if user is logged in and has recommendations
                    // Uncomment the lines below to debug:
                    // echo "<div class='col-12'><p>User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not logged in') . "</p></div>";
                    
                    // Recommendation Algorithm
                    function getRecommendedRooms($db, $user_id = null)
                    {
                        $recommended_rooms = [];

                        if ($user_id) {
                            $history_query = "SELECT DISTINCT r.rtype, COUNT(*) as booking_count 
                                            FROM booked b 
                                            JOIN rooms r ON b.r_id = r.r_id 
                                            WHERE b.ID = ? 
                                            GROUP BY r.rtype 
                                            ORDER BY booking_count DESC";

                            $stmt = mysqli_prepare($db, $history_query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, 'i', $user_id);
                                mysqli_stmt_execute($stmt);
                                $history_result = mysqli_stmt_get_result($stmt);

                                while ($row = mysqli_fetch_assoc($history_result)) {
                                    $recommended_rooms[] = $row['rtype'];
                                }
                            }
                        }

                        return $recommended_rooms;
                    }

                    $current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    $recommended_types = getRecommendedRooms($db, $current_user_id);

                    // Debug: Show recommended types
                    // echo "<div class='col-12'><p>Recommended Types: " . implode(', ', $recommended_types) . "</p></div>";
                    
                    $today = date('Y-m-d');
                    $rooms_query = "SELECT * FROM rooms 
                                   WHERE hotel_id = $hotel_id 
                                   AND r_id NOT IN (
                                       SELECT DISTINCT r_id FROM booked 
                                       WHERE booking_status = 'confirmed'
                                       AND check_in <= '$today' AND check_out > '$today'
                                   )
                                   ORDER BY rprice ASC";
                    $rooms_result = mysqli_query($db, $rooms_query);

                    $displayed_rooms = [];
                    $room_counter = 0;

                    if (mysqli_num_rows($rooms_result) > 0) {
                        // First pass: Display recommended rooms
                        if (!empty($recommended_types)) {
                            echo '<div class="col-12"><h4 style="color: #4B795D; margin: 20px 0; text-align: center;"><i class="fa fa-star"></i> Recommended Rooms for You</h4></div>';

                            mysqli_data_seek($rooms_result, 0);
                            while ($room = mysqli_fetch_array($rooms_result)) {
                                if (in_array($room['rtype'], $recommended_types) && !in_array($room['r_id'], $displayed_rooms)) {
                                    $displayed_rooms[] = $room['r_id'];
                                    $room_counter++;
                                    ?>
                    <div class="col-md-4" style="display:flex; margin-bottom:30px;">
                        <div class="room-card recommended-room" style="width:100%;">
                            <div class="recommendation-badge">
                                <i class="fa fa-star"></i> Recommended
                            </div>
                            <img src="admin/upload/<?php echo $room['rimage']; ?>" alt="<?php echo $room['rtype']; ?>">
                            <div class="room-info" style="flex:1; display:flex; flex-direction:column;">
                                <h3><?php echo $room['rtype']; ?></h3>
                                <p class="des-sort" style="flex:1;"><?php echo $room['rtext']; ?></p>
                                <div class="room-price">
                                    <h5>R <?php echo $room['rprice']; ?>/-</h5>
                                    <span>Per Night</span>
                                </div>
                                <a href="booking.php?book=<?php echo $room['r_id']; ?>&hotel_id=<?php echo $hotel_id; ?>"
                                    class="btn-book">Book Now</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($room_counter % 3 == 0)
                                        echo '<div class="clearfix"></div>'; ?>
                    <?php }
                            }
                        }

                        // Second pass: Display other rooms
                        mysqli_data_seek($rooms_result, 0);
                        while ($room = mysqli_fetch_array($rooms_result)) {
                            if (!in_array($room['r_id'], $displayed_rooms)) {
                                $displayed_rooms[] = $room['r_id'];
                                $room_counter++;
                                ?>
                    <div class="col-md-4" style="display:flex; margin-bottom:30px;">
                        <div class="room-card" style="width:100%;">
                            <img src="admin/upload/<?php echo $room['rimage']; ?>" alt="<?php echo $room['rtype']; ?>">
                            <div class="room-info" style="flex:1; display:flex; flex-direction:column;">
                                <h3><?php echo $room['rtype']; ?></h3>
                                <p class="des-sort" style="flex:1;"><?php echo $room['rtext']; ?></p>
                                <div class="room-price">
                                    <h5>R <?php echo $room['rprice']; ?>/-</h5>
                                    <span>Per Night</span>
                                </div>
                                <a href="booking.php?book=<?php echo $room['r_id']; ?>&hotel_id=<?php echo $hotel_id; ?>"
                                    class="btn-book">Book Now</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($room_counter % 3 == 0)
                                    echo '<div class="clearfix"></div>'; ?>
                    <?php }
                        }
                    } else {
                        echo '<div class="col-12 text-center"><h3>No rooms available at this hotel currently.</h3></div>';
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- Rating Section -->
        <?php
        // Fetch all reviews for this hotel
        $reviews_query = "SELECT r.*, u.Name FROM hotel_reviews r JOIN users u ON r.user_id = u.ID WHERE r.hotel_id = $hotel_id ORDER BY r.created_at DESC";
        $reviews_result = mysqli_query($db, $reviews_query);
        $total_reviews = mysqli_num_rows($reviews_result);

        // Check if logged-in user already rated
        $user_existing_rating = null;
        $user_existing_review = '';
        $user_has_booking = false;
        if (isset($_SESSION['user_email'])) {
            $ue = $_SESSION['user_email'];
            $uid_q = mysqli_query($db, "SELECT ID FROM users WHERE Email='$ue'");
            $uid_row = mysqli_fetch_assoc($uid_q);
            if ($uid_row) {
                $uid = $uid_row['ID'];
                $bk = mysqli_query($db, "SELECT booking_id FROM booked WHERE ID=$uid AND hotel_id=$hotel_id AND booking_status='confirmed' LIMIT 1");
                $user_has_booking = mysqli_num_rows($bk) > 0;
                $er = mysqli_query($db, "SELECT * FROM hotel_reviews WHERE user_id=$uid AND hotel_id=$hotel_id LIMIT 1");
                if (mysqli_num_rows($er) > 0) {
                    $er_row = mysqli_fetch_assoc($er);
                    $user_existing_rating = $er_row['rating'];
                    $user_existing_review = $er_row['review'];
                }
            }
        }
        ?>
        <section style="padding: 50px 0; background: #f8f9fa;">
            <div class="container">
                <div class="row">
                    <!-- Submit Rating -->
                    <div class="col-md-5">
                        <div
                            style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                            <h4 style="color: #4B795D; margin-bottom: 20px;"><i class="fa fa-star"></i> Rate This Hotel
                            </h4>
                            <?php if (!isset($_SESSION['user_email'])): ?>
                            <p>Please <a href="login.php" style="color:#4B795D;">login</a> to rate this hotel.</p>
                            <?php elseif (!$user_has_booking): ?>
                            <p class="text-muted">Only guests who have stayed here can leave a rating.</p>
                            <?php else: ?>
                            <form id="ratingForm">
                                <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                                <div style="margin-bottom: 20px;">
                                    <label style="font-weight:600; display:block; margin-bottom:10px;">Your
                                        Rating</label>
                                    <div class="star-selector">
                                        <?php for ($s = 5; $s >= 1; $s--): ?>
                                        <input type="radio" id="star<?php echo $s; ?>" name="rating"
                                            value="<?php echo $s; ?>"
                                            <?php echo $user_existing_rating == $s ? 'checked' : ''; ?>>
                                        <label for="star<?php echo $s; ?>"><i class="fa fa-star"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label style="font-weight:600; display:block; margin-bottom:8px;">Your Review
                                        (optional)</label>
                                    <textarea name="review" rows="4"
                                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; resize:vertical;"
                                        placeholder="Share your experience..."><?php echo htmlspecialchars($user_existing_review); ?></textarea>
                                </div>
                                <button type="submit"
                                    style="background:#4B795D; color:white; border:none; padding:12px 30px; border-radius:5px; width:100%; font-size:15px; cursor:pointer;">
                                    <?php echo $user_existing_rating ? 'Update Rating' : 'Submit Rating'; ?>
                                </button>
                            </form>
                            <div id="ratingMsg" style="margin-top:15px; display:none;"></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="col-md-7">
                        <div
                            style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                            <h4 style="color: #4B795D; margin-bottom: 20px;">
                                <i class="fa fa-comments"></i> Guest Reviews
                                <span style="font-size:14px; color:#999; font-weight:normal;">
                                    (<?php echo $total_reviews; ?>
                                    review<?php echo $total_reviews != 1 ? 's' : ''; ?>)</span>
                            </h4>
                            <?php if ($total_reviews > 0): ?>
                            <div style="max-height: 400px; overflow-y: auto;">
                                <?php while ($rev = mysqli_fetch_assoc($reviews_result)): ?>
                                <div style="border-bottom: 1px solid #eee; padding: 15px 0;">
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                        <strong><?php echo htmlspecialchars($rev['Name']); ?></strong>
                                        <span
                                            style="color:#999; font-size:12px;"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                                    </div>
                                    <div style="color:#ffc107; margin-bottom:6px;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa fa-star<?php echo $i > $rev['rating'] ? '-o' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (!empty($rev['review'])): ?>
                                    <p style="color:#555; margin:0; font-size:14px;">
                                        <?php echo htmlspecialchars($rev['review']); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-muted">No reviews yet. Be the first to review!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
    $('#ratingForm').submit(function(e) {
        e.preventDefault();
        var rating = $('input[name="rating"]:checked').val();
        if (!rating) {
            $('#ratingMsg').show().html(
                '<div style="background:#fff3cd;padding:10px;border-radius:5px;color:#856404;">Please select a star rating.</div>'
            );
            return;
        }
        $.ajax({
            url: 'rate_hotel.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp) {
                var color = resp.success ? '#d4edda' : '#f8d7da';
                var textColor = resp.success ? '#155724' : '#721c24';
                $('#ratingMsg').show().html('<div style="background:' + color +
                    ';padding:10px;border-radius:5px;color:' + textColor + ';">' + resp
                    .message + '</div>');
                if (resp.success) {
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    });
    </script>

    <script>
    document.querySelector('.search-form').addEventListener('submit', function(e) {
        const fields = [
            { el: document.getElementById('check_in'),           label: 'Check-in Date' },
            { el: document.getElementById('check_out'),          label: 'Check-out Date' },
            { el: document.querySelector('select[name="type"]'),  label: 'Room Type' },
            { el: document.querySelector('select[name="adult"]'), label: 'Adults' },
            { el: document.querySelector('select[name="child"]'), label: 'Children' }
        ];

        for (var i = 0; i < fields.length; i++) {
            if (!fields[i].el.value) {
                e.preventDefault();
                alert(fields[i].label + ' is required.');
                fields[i].el.focus();
                return;
            }
        }

        const checkIn  = document.getElementById('check_in').value;
        const checkOut = document.getElementById('check_out').value;
        const today    = new Date().toISOString().split('T')[0];

        if (checkIn < today) {
            e.preventDefault();
            alert('Check-in date cannot be in the past.');
            document.getElementById('check_in').value = '';
            document.getElementById('check_in').focus();
            return;
        }
        if (checkOut <= checkIn) {
            e.preventDefault();
            alert('Check-out date must be after check-in date.');
            document.getElementById('check_out').value = '';
            document.getElementById('check_out').focus();
            return;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const today   = new Date().toISOString().split('T')[0];
        const checkIn  = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        checkIn.min  = today;
        checkOut.min = today;

        checkIn.addEventListener('change', function() {
            if (this.value < today) {
                alert('Check-in date cannot be in the past.');
                this.value = '';
                return;
            }
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOut.min = nextDay.toISOString().split('T')[0];
            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = '';
            }
        });

        checkOut.addEventListener('change', function() {
            if (!checkIn.value) {
                alert('Please select a check-in date first.');
                this.value = '';
                return;
            }
            if (this.value <= checkIn.value) {
                alert('Check-out date must be after check-in date.');
                this.value = '';
            }
        });
    });
    </script>

</body>

</html>