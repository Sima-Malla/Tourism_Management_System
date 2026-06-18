<?php include 'header.php'; ?>

<?php
include("connection/connect.php");
$type = isset($_GET['type']) ? $_GET['type'] : '';
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
$adult = isset($_GET['adult']) ? $_GET['adult'] : '';
$child = isset($_GET['child']) ? $_GET['child'] : '';

?>

<head>
    <meta charset="UTF-8">
    <title>Search Results - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
        <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
</head>

<body>
    <div class="wrapper">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Search Results</h2>
                        <?php if ($check_in && $check_out): ?>
                            <p>Available rooms from <?php echo date('M d, Y', strtotime($check_in)); ?> to <?php echo date('M d, Y', strtotime($check_out)); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="room-listing-style3">
                        <?php
                        // Build the query based on search criteria
                        $base_query = "SELECT * FROM rooms";
                        $where_conditions = [];
                        $params = [];
                        
                        // If dates are provided, exclude rooms that are booked during those dates
                        if (!empty($check_in) && !empty($check_out)) {
                            $where_conditions[] = "r_id NOT IN (
                                SELECT DISTINCT r_id FROM booked 
                                WHERE booking_status = 'confirmed' 
                                AND (
                                    (check_in <= ? AND check_out > ?) OR
                                    (check_in < ? AND check_out >= ?) OR
                                    (check_in >= ? AND check_out <= ?)
                                )
                            )";
                            $params = array_merge($params, [$check_in, $check_in, $check_out, $check_out, $check_in, $check_out]);
                        }
                        
                        // Filter by room type if specified
                        if (!empty($type)) {
                            $where_conditions[] = "rtype = ?";
                            $params[] = $type;
                        }
                        
                        // Add WHERE clause if there are conditions
                        if (!empty($where_conditions)) {
                            $base_query .= " WHERE " . implode(" AND ", $where_conditions);
                        }
                        
                        // Execute the query
                        if (!empty($params)) {
                            $stmt = mysqli_prepare($db, $base_query);
                            if ($stmt) {
                                $types = str_repeat('s', count($params));
                                mysqli_stmt_bind_param($stmt, $types, ...$params);
                                mysqli_stmt_execute($stmt);
                                $res = mysqli_stmt_get_result($stmt);
                            } else {
                                $res = mysqli_query($db, $base_query);
                            }
                        } else {
                            $res = mysqli_query($db, $base_query);
                        }
                        
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_array($res)) {
                                $r_id = $row['r_id'];
                                $rimage = $row['rimage'];
                                $rtype = $row['rtype'];
                                $rprice = $row['rprice'];
                                $rtext = $row['rtext'];
                                ?>
                                <div class="col-md-4">
                                    <div class="room-list-view">
                                        <figure>
                                            <img src='admin/upload/<?php echo $rimage; ?>' alt="<?php echo $rtype; ?>">
                                        </figure>
                                        <div class="room-info style2 style3">
                                            <h3><?php echo $rtype; ?></h3>
                                            <p><?php echo $rtext; ?></p>
                                            <div class="room-price">
                                                <h5>R <?php echo $rprice; ?>/-</h5>
                                                <span>Per Night</span>
                                            </div>
                                            <a href="booking.php?book=<?php echo $r_id; ?>" class="booking3">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-12 text-center"><h3>No rooms available for the selected criteria.</h3></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--wrapper end-->

    <!-- Including Jquery Js File -->
    <script type="text/javascript" src="http://creativethemes.us/relax/js/jquery.min.js"></script>
    <!-- Including Bootstrap js file -->
    <script type="text/javascript" src="http://creativethemes.us/relax/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://creativethemes.us/relax/js/html5lightbox.js"></script>
    <!-- Custom Js file -->
    <script type="text/javascript" src="http://creativethemes.us/relax/js/script.js"></script>

    <!-- Including Jquery Js File -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- Including Bootstrap js file -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/flatpickr.min.js"></script>
    <script type="text/javascript" src="js/isotope.js"></script>
    <script type="text/javascript" src="js/html5lightbox.js"></script>
    <script type="text/javascript" src="js/wow.js"></script>

    <!-- Custom Js file -->
    <script type="text/javascript" src="js/script.js"></script>
</body>