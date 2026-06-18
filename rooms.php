<?php
include("connection/connect.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rooms - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
        <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .rooms-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/hotel-cover.jpg');
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
        }

        .hero-text h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 15px;
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
            margin-bottom: 30px;
            transition: transform 0.3s;
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
            line-clamp: 2 !important;
        }

        /* Recommendation Styles */
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
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="wrapper">
        <div class="rooms-hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>Our Rooms</h1>
                        <p style="color:white;">Choose from our selection of comfortable and luxurious rooms</p>
                    </div>
                    <div class="search-container">
                        <div class="search-room">
                            <h3>Search A Room</h3>
                            <form class="search-form" action='search.php' method='get'>
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="date" name="check_in" class="form-control"
                                            placeholder="Check-in Date"
                                            style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; color: #666;">
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="check_out" class="form-control"
                                            placeholder="Check-out Date"
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
                                    <div class="form-group">
                                        <select name='adult'>
                                            <option value="">Adults</option>
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>
                                            <option value='3'>3</option>
                                        </select>
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="form-group">
                                        <select name='child'>
                                            <option value="">Children</option>
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>
                                            <option value='3'>3</option>
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

        <?php // Remove the search.php include to avoid duplicate room display ?>

        <section class="rooms-section">
            <div class="container">
                <div class="row manage-items">
                    <?php
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

                                if (mysqli_num_rows($history_result) > 0) {
                                    $preferred_type = mysqli_fetch_assoc($history_result)['rtype'];
                                    $recommended_rooms[] = $preferred_type;
                                }
                            }
                        }

                        return $recommended_rooms;
                    }

                    // Get user ID if logged in
                    $current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    $recommended_types = getRecommendedRooms($db, $current_user_id);

                    // Search functionality
                    $search_condition = "";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search_term = mysqli_real_escape_string($db, $_GET['search']);
                        $search_condition = " AND h.hotel_address LIKE '%$search_term%'";
                    }

                    // Get all available rooms with hotel address search
                    $all_rooms_query = "SELECT r.*, h.hotel_address FROM rooms r 
                                       LEFT JOIN hotels h ON r.hotel_id = h.hotel_id 
                                       WHERE r.r_id NOT IN (SELECT DISTINCT r_id FROM booked WHERE booking_status = 'confirmed') 
                                       $search_condition 
                                       ORDER BY r.rprice ASC";
                    $all_rooms_result = mysqli_query($db, $all_rooms_query);

                    $displayed_rooms = [];

                    if (mysqli_num_rows($all_rooms_result) > 0) {
                        // First pass: Display recommended rooms
                        if (!empty($recommended_types)) {
                            echo '<div class="col-12"><h4 style="color: #4B795D; margin: 20px 0;"><i class="fa fa-star"></i> Recommended for You</h4></div>';

                            mysqli_data_seek($all_rooms_result, 0);
                            while ($row = mysqli_fetch_array($all_rooms_result)) {
                                if (in_array($row['rtype'], $recommended_types) && !in_array($row['r_id'], $displayed_rooms)) {
                                    $r_id = $row['r_id'];
                                    $rimage = $row['rimage'];
                                    $rtype = $row['rtype'];
                                    $rprice = $row['rprice'];
                                    $rtext = $row['rtext'];
                                    $displayed_rooms[] = $r_id;
                                    ?>
                                    <div class="col-md-4">
                                        <div class="room-card recommended-room">
                                            <div class="recommendation-badge">
                                                <i class="fa fa-star"></i> Recommended
                                            </div>
                                            <img src="admin/upload/<?php echo $rimage; ?>" alt="<?php echo $rtype; ?>">
                                            <div class="room-info">
                                                <h3><?php echo $rtype; ?></h3>
                                                <p class="des-sort"><?php echo $rtext; ?></p>
                                                <div class="room-price">
                                                    <h5>R <?php echo $rprice; ?>/-</h5>
                                                    <span>Per Night</span>
                                                </div>
                                                <a href="booking.php?book=<?php echo $r_id; ?>" class="btn-book">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }

                        // Second pass: Display other rooms
                        $has_other_rooms = false;
                        mysqli_data_seek($all_rooms_result, 0);
                        while ($row = mysqli_fetch_array($all_rooms_result)) {
                            if (!in_array($row['r_id'], $displayed_rooms)) {
                                if (!$has_other_rooms) {
                                    $has_other_rooms = true;
                                }

                                $r_id = $row['r_id'];
                                $rimage = $row['rimage'];
                                $rtype = $row['rtype'];
                                $rprice = $row['rprice'];
                                $rtext = $row['rtext'];
                                $displayed_rooms[] = $r_id;
                                ?>
                                <div class="col-md-4">
                                    <div class="room-card">
                                        <img src="admin/upload/<?php echo $rimage; ?>" alt="<?php echo $rtype; ?>">
                                        <div class="room-info">
                                            <h3><?php echo $rtype; ?></h3>
                                            <p class="des-sort"><?php echo $rtext; ?></p>
                                            <div class="room-price">
                                                <h5>R <?php echo $rprice; ?>/-</h5>
                                                <span>Per Night</span>
                                            </div>
                                            <a href="booking.php?book=<?php echo $r_id; ?>" class="btn-book">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                    } else {
                        echo '<div class="col-12 text-center"><h3>No available rooms at the moment.</h3></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>