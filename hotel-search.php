<?php include 'header.php'; ?>
<?php include("connection/connect.php"); ?>

<?php
$hotel_id = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$adult = isset($_GET['adult']) ? $_GET['adult'] : '';
$child = isset($_GET['child']) ? $_GET['child'] : '';

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
    <title>Search Results - <?php echo $hotel['hotel_name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
        .search-results {
            padding: 60px 0;
            background: #f8f9fa;
        }
        .room-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
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
        .btn-book {
            background: #4B795D;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        .btn-book:hover {
            background: #3a5f47;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="search-results">
            <div class="container">
                <div class="row">
                    <?php
                    $rooms_query = "SELECT * FROM rooms 
                                   WHERE hotel_id = $hotel_id 
                                   AND rtype = '$type'
                                   AND r_id NOT IN (
                                       SELECT r_id FROM booked 
                                       WHERE booking_status = 'confirmed'
                                       AND check_in < '$check_out' AND check_out > '$check_in'
                                   )
                                   ORDER BY rprice ASC";
                    
                    $rooms_result = mysqli_query($db, $rooms_query);

                    if ($rooms_result && mysqli_num_rows($rooms_result) > 0) {
                        while ($room = mysqli_fetch_array($rooms_result)) {
                            ?>
                            <div class="col-md-4">
                                <div class="room-card">
                                    <img src="admin/upload/<?php echo $room['rimage']; ?>" alt="<?php echo $room['rtype']; ?>">
                                    <div class="room-info">
                                        <h3><?php echo $room['rtype']; ?></h3>
                                        <p><?php echo $room['rtext']; ?></p>
                                        <div class="room-price">
                                            <h5>R <?php echo $room['rprice']; ?>/-</h5>
                                            <span>Per Night</span>
                                        </div>
                                        <a href="booking.php?book=<?php echo $room['r_id']; ?>&hotel_id=<?php echo $hotel_id; ?>&check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>" class="btn-book">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center"><h3>No rooms available for the selected dates and criteria.</h3><p>Please try different dates or room type.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
