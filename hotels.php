<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
}
function getRoadDistance($db, $lat1, $lon1, $lat2, $lon2) {
    $lat1r = round($lat1, 7); $lon1r = round($lon1, 7);
    $lat2r = round($lat2, 7); $lon2r = round($lon2, 7);
    $cache_query = "SELECT distance_km FROM road_distance_cache 
                    WHERE from_lat = $lat1r AND from_lon = $lon1r 
                    AND to_lat = $lat2r AND to_lon = $lon2r";
    $cache_result = mysqli_query($db, $cache_query);
    if ($cache_result && mysqli_num_rows($cache_result) > 0) {
        return (float)mysqli_fetch_assoc($cache_result)['distance_km'];
    }
    $url = "http://router.project-osrm.org/route/v1/driving/{$lon1r},{$lat1r};{$lon2r},{$lat2r}?overview=false";
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $response = @file_get_contents($url, false, $context);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['routes'][0]['distance'])) {
            $distance_km = round($data['routes'][0]['distance'] / 1000, 4);
            // Store in cache
            mysqli_query($db, "INSERT IGNORE INTO road_distance_cache 
                               (from_lat, from_lon, to_lat, to_lon, distance_km) 
                               VALUES ($lat1r, $lon1r, $lat2r, $lon2r, $distance_km)");
            return $distance_km;
        }
    }
    return haversineDistance($lat1, $lon1, $lat2, $lon2);
}

function aStarRankHotels($db, $user_lat, $user_lon, $hotels) {
    $n = count($hotels);
    if ($n === 0) return [];

    // Build node list: node 0 = user, nodes 1..n = hotels
    $nodes = array_merge(
        [['lat' => $user_lat, 'lon' => $user_lon]],
        array_map(fn($h) => ['lat' => (float)$h['latitude'], 'lon' => (float)$h['longitude']], $hotels)
    );

    // Precompute real road edge weights between all nodes using OSRM
    $edges = [];
    for ($i = 0; $i <= $n; $i++) {
        for ($j = 0; $j <= $n; $j++) {
            if ($i !== $j) {
                $edges[$i][$j] = getRoadDistance(
                    $db,
                    $nodes[$i]['lat'], $nodes[$i]['lon'],
                    $nodes[$j]['lat'], $nodes[$j]['lon']
                );
            }
        }
    }

    $ranked = [];

    // Run A* from user (node 0) to each hotel goal (node 1..n)
    for ($goal = 1; $goal <= $n; $goal++) {

        // Step 1 & 2: Initialize open and closed lists, put start node on open list
        $open   = [0 => ['g' => 0.0, 'f' => 0.0]];
        $closed = [];

        // Step 3: while the open list is not empty
        while (!empty($open)) {

            // Step 3a: find node with least f on open list - call it q
            $q        = null;
            $lowest_f = INF;
            foreach ($open as $node_id => $data) {
                if ($data['f'] < $lowest_f) {
                    $lowest_f = $data['f'];
                    $q        = $node_id;
                }
            }

            // Step 3b: pop q off the open list
            $q_g = $open[$q]['g'];
            unset($open[$q]);

            // Step 3c: generate q's successors (all other nodes in complete graph)
            for ($s = 0; $s <= $n; $s++) {
                if ($s === $q) continue;

                // Step 3d-i: if successor is the goal, stop search
                if ($s === $goal) {
                    $ranked[$goal - 1] = $q_g + $edges[$q][$s];
                    break 2;
                }

                // Step 3d-ii: compute g, h, f for successor
                // g = real road distance (OSRM)
                // h = Haversine straight-line to goal (admissible heuristic)
                $successor_g = $q_g + $edges[$q][$s];
                $successor_h = haversineDistance(
                    $nodes[$s]['lat'],    $nodes[$s]['lon'],
                    $nodes[$goal]['lat'], $nodes[$goal]['lon']
                );
                $successor_f = $successor_g + $successor_h;

                // Step 3d-iii: if same node in OPEN with lower f, skip
                if (isset($open[$s]) && $open[$s]['f'] <= $successor_f) continue;

                // Step 3d-iv: if same node in CLOSED with lower f, skip
                //             otherwise add to open list
                if (isset($closed[$s]) && $closed[$s]['f'] <= $successor_f) continue;

                $open[$s] = ['g' => $successor_g, 'f' => $successor_f];
            }

            // Step 3e: push q on the closed list
            $closed[$q] = ['g' => $q_g, 'f' => $lowest_f];
        }
    }

    asort($ranked);
    return $ranked;
}

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    include("connection/connect.php");

    $user_lat = isset($_GET['lat']) && $_GET['lat'] !== '' ? (float)$_GET['lat'] : null;
    $user_lon = isset($_GET['lon']) && $_GET['lon'] !== '' ? (float)$_GET['lon'] : null;
    $is_logged_in = isset($_SESSION['user_id']);

    // Only use location-based A* if user is logged in
    if (!$is_logged_in) {
        $user_lat = null;
        $user_lon = null;
    }

    // Search functionality
    $search_condition = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = mysqli_real_escape_string($db, $_GET['search']);
        $search_condition = " AND hotel_address LIKE '%$search_term%'";
    }

    $hotels_query = "SELECT * FROM hotels WHERE status = 'active'$search_condition";
    $hotels_result = mysqli_query($db, $hotels_query);

    if (mysqli_num_rows($hotels_result) > 0) {
        $hotels_array = [];
        while ($hotel = mysqli_fetch_assoc($hotels_result)) {
            $hotels_array[] = $hotel;
        }

        if ($user_lat !== null) {
            // Filter hotels that have coordinates
            $geo_hotels  = array_filter($hotels_array, fn($h) => $h['latitude'] && $h['longitude']);
            $nogeo_hotels = array_filter($hotels_array, fn($h) => !$h['latitude'] || !$h['longitude']);
            $geo_hotels  = array_values($geo_hotels);

            // A* ranking with OSRM real road distances
            $ranked = aStarRankHotels($db, $user_lat, $user_lon, $geo_hotels);

            // Rebuild sorted array using A* order
            $sorted = [];
            foreach ($ranked as $idx => $fcost) {
                $geo_hotels[$idx]['astar_cost'] = $fcost;
                $sorted[] = $geo_hotels[$idx];
            }
            // Append hotels without coordinates at the end
            foreach ($nogeo_hotels as $h) {
                $h['astar_cost'] = null;
                $sorted[] = $h;
            }
            $hotels_array = $sorted;
        } else {
            usort($hotels_array, fn($a, $b) => $b['hotel_rating'] <=> $a['hotel_rating']);
        }

        foreach ($hotels_array as $hotel) {
            $rooms_count_query = "SELECT COUNT(*) as room_count FROM rooms WHERE hotel_id = {$hotel['hotel_id']}";
            $rooms_count_result = mysqli_query($db, $rooms_count_query);
            $room_count = mysqli_fetch_assoc($rooms_count_result)['room_count'];
            $hotel_img = !empty($hotel['hotel_image']) ? 'admin/upload/' . $hotel['hotel_image'] : 'admin/upload/ft-img.jpg';
            $distance_badge = isset($hotel['astar_cost']) && $hotel['astar_cost'] !== null
                ? '<div style="position:absolute; top:10px; left:10px; background:#4B795D; color:white; padding:5px 10px; border-radius:15px; font-size:12px; font-weight:bold; z-index:2;"><i class="fa fa-map-marker"></i> ' . number_format($hotel['astar_cost'], 1) . ' km away</div>'
                : '';
            ?>
            <div class="col-md-4">
                <div class="hotel-card" style="position:relative;">
                    <?php echo $distance_badge; ?>
                    <img src="<?php echo $hotel_img; ?>" class="hotel-image" alt="<?php echo $hotel['hotel_name']; ?>">
                    <div class="hotel-info">
                        <div class="hotel-rating">
                            <?php
                            $rating = $hotel['hotel_rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                            }
                            echo " ({$rating})";
                            ?>
                        </div>
                        <h3><?php echo $hotel['hotel_name']; ?></h3>
                        <div class="hotel-address">
                            <i class="fa fa-map-marker"></i>
                            <?php echo $hotel['hotel_address']; ?>
                        </div>
                        <div class="hotel-description">
                            <?php echo substr($hotel['hotel_description'], 0, 120) . '...'; ?>
                        </div>
                        <div class="room-count">
                            <i class="fa fa-bed"></i> <?php echo $room_count; ?> Rooms Available
                        </div>
                        <a href="hotel-rooms.php?hotel_id=<?php echo $hotel['hotel_id']; ?>" class="btn-view-rooms">
                            View Rooms
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        $search_message = isset($_GET['search']) && !empty($_GET['search']) ?
            "No hotels found in '" . htmlspecialchars($_GET['search']) . "'. Try a different location." :
            "No hotels available at the moment.";
        echo '<div class="col-12 no-hotels"><h3>' . $search_message . '</h3><p>Please check back later for new hotel listings.</p></div>';
    }
    exit();
}
?>

<?php include 'header.php'; ?>
<?php include("connection/connect.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hotels - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">


    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .hotels-hero {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)), url('images/hotel-cover.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 120px 0;
            color: white;
            text-align: center;
            position: relative;
        }

        .search-container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto 0;
        }

        .search-form {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            background: transparent;
            outline: none;
            color: #333;
        }

        .search-btn {
            background: #4B795D;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #3a5f47;
        }

        .hotels-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }

        .hotels-hero .container {
            position: relative;
            z-index: 2;
            text-align: center;
        }


        .hotels-hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hotels-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            color: white !important;
            text-align: center !important;
        }

        .hotels-section {
            padding: 10px 0;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }

        .hotel-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .hotel-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .hotel-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .hotel-card:hover .hotel-image {
            transform: scale(1.05);
        }

        .hotel-info {
            padding: 30px;
            position: relative;
        }

        .hotel-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 15px 0 10px 0;
            line-height: 1.3;
        }

        .hotel-rating {
            color: #ffc107;
            margin-bottom: 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hotel-rating .fa {
            font-size: 16px;
        }

        .hotel-address {
            color: #7f8c8d;
            margin-bottom: 15px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hotel-address .fa {
            color: #4B795D;
        }

        .hotel-description {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .room-count {
            background: linear-gradient(135deg, #4B795D, #3a5f47);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 25px;
        }

        .btn-view-rooms {
            background: linear-gradient(135deg, #4B795D, #3a5f47);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            width: 100%;
            text-align: center;
            display: block;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-view-rooms::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-view-rooms:hover::before {
            left: 100%;
        }

        .btn-view-rooms:hover {
            background: linear-gradient(135deg, #484848ff, #575757ff);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(75, 121, 93, 0.3);
        }

        .no-hotels {
            text-align: center;
            padding: 80px 20px;
            color: #7f8c8d;
        }

        .no-hotels h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #5a6c7d;
        }

        @media (max-width: 768px) {
            .hotels-hero h1 {
                font-size: 2.5rem;
            }

            .hotels-hero p {
                font-size: 1.1rem;
            }

            .hotels-section {
                padding: 60px 0;
            }

            .hotel-info {
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="hotels-hero">
            <div class="container">
                <h1>Our Hotels</h1>
                <p>Discover our premium hotels across Nepal</p>
                <div class="search-container">
                    <form class="search-form" action="hotels.php" method="GET">
                        <input type="text" name="search" placeholder="Search hotels by location..." class="search-input"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <section class="hotels-section">
            <div class="container">
                <div id="location-banner" style="text-align:center; padding:10px 0; color:#4B795D; display:none;">
                    <i class="fa fa-map-marker"></i> <span id="location-msg">Detecting your location to sort hotels by nearest...</span>
                </div>
                <div class="row">
                    <?php
                    $user_lat = isset($_GET['lat']) && $_GET['lat'] !== '' ? (float)$_GET['lat'] : null;
                    $user_lon = isset($_GET['lon']) && $_GET['lon'] !== '' ? (float)$_GET['lon'] : null;
                    $is_logged_in = isset($_SESSION['user_id']);

                    // Only use location-based A* if user is logged in
                    if (!$is_logged_in) {
                        $user_lat = null;
                        $user_lon = null;
                    }

                    // Search functionality
                    $search_condition = "";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search_term = mysqli_real_escape_string($db, $_GET['search']);
                        $search_condition = " AND hotel_address LIKE '%$search_term%'";
                    }

                    $hotels_query = "SELECT * FROM hotels WHERE status = 'active'$search_condition";
                    $hotels_result = mysqli_query($db, $hotels_query);

                    if (mysqli_num_rows($hotels_result) > 0) {
                        $hotels_array = [];
                        while ($hotel = mysqli_fetch_assoc($hotels_result)) {
                            $hotels_array[] = $hotel;
                        }

                        if ($user_lat !== null) {
                            $geo_hotels   = array_values(array_filter($hotels_array, fn($h) => $h['latitude'] && $h['longitude']));
                            $nogeo_hotels = array_values(array_filter($hotels_array, fn($h) => !$h['latitude'] || !$h['longitude']));
                            $ranked = aStarRankHotels($db, $user_lat, $user_lon, $geo_hotels);
                            $sorted = [];
                            foreach ($ranked as $idx => $fcost) {
                                $geo_hotels[$idx]['astar_cost'] = $fcost;
                                $sorted[] = $geo_hotels[$idx];
                            }
                            foreach ($nogeo_hotels as $h) { $h['astar_cost'] = null; $sorted[] = $h; }
                            $hotels_array = $sorted;
                        } else {
                            usort($hotels_array, fn($a, $b) => $b['hotel_rating'] <=> $a['hotel_rating']);
                        }

                        foreach ($hotels_array as $hotel) {
                            $rooms_count_query = "SELECT COUNT(*) as room_count FROM rooms WHERE hotel_id = {$hotel['hotel_id']}";
                            $rooms_count_result = mysqli_query($db, $rooms_count_query);
                            $room_count = mysqli_fetch_assoc($rooms_count_result)['room_count'];
                            $hotel_img = !empty($hotel['hotel_image']) ? 'admin/upload/' . $hotel['hotel_image'] : 'admin/upload/ft-img.jpg';
                            $distance_badge = isset($hotel['astar_cost']) && $hotel['astar_cost'] !== null
                                ? '<div style="position:absolute; top:10px; left:10px; background:#4B795D; color:white; padding:5px 10px; border-radius:15px; font-size:12px; font-weight:bold; z-index:2;"><i class="fa fa-map-marker"></i> ' . number_format($hotel['astar_cost'], 1) . ' km away</div>'
                                : '';
                            ?>
                            <div class="col-md-4">
                                <div class="hotel-card" style="position:relative;">
                                    <?php echo $distance_badge; ?>
                                    <img src="<?php echo $hotel_img; ?>" class="hotel-image"
                                        alt="<?php echo $hotel['hotel_name']; ?>">
                                    <div class="hotel-info">
                                        <div class="hotel-rating">
                                            <?php
                                            $rating = $hotel['hotel_rating'];
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                            }
                                            echo " ({$rating})";
                                            ?>
                                        </div>
                                        <h3><?php echo $hotel['hotel_name']; ?></h3>
                                        <div class="hotel-address">
                                            <i class="fa fa-map-marker"></i>
                                            <?php echo $hotel['hotel_address']; ?>
                                        </div>
                                        <div class="hotel-description">
                                            <?php echo substr($hotel['hotel_description'], 0, 120) . '...'; ?>
                                        </div>
                                        <div class="room-count">
                                            <i class="fa fa-bed"></i> <?php echo $room_count; ?> Rooms Available
                                        </div>
                                        <a href="hotel-rooms.php?hotel_id=<?php echo $hotel['hotel_id']; ?>"
                                            class="btn-view-rooms">
                                            View Rooms
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        $search_message = isset($_GET['search']) && !empty($_GET['search']) ?
                            "No hotels found in '" . htmlspecialchars($_GET['search']) . "'. Try a different location." :
                            "No hotels available at the moment.";
                        echo '<div class="col-12 no-hotels"><h3>' . $search_message . '</h3><p>Please check back later for new hotel listings.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php';
    ?>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/flatpickr.min.js"></script>
    <script type="text/javascript" src="js/isotope.js"></script>
    <script type="text/javascript" src="js/html5lightbox.js"></script>
    <script type="text/javascript" src="js/wow.js"></script>
    <script type="text/javascript" src="js/script.js"></script>

    <script>
        $(document).ready(function () {
            var userLat = null, userLon = null;
            var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

            function loadHotels(searchTerm) {
                var data = { ajax: 1, search: searchTerm };
                if (userLat !== null) { data.lat = userLat; data.lon = userLon; }
                $.ajax({
                    url: 'hotels.php',
                    type: 'GET',
                    data: data,
                    success: function (response) {
                        $('.hotels-section .row').html(response);
                    }
                });
            }

            if (isLoggedIn && navigator.geolocation) {
                $('#location-banner').show();
                navigator.geolocation.getCurrentPosition(function (pos) {
                    userLat = pos.coords.latitude;
                    userLon = pos.coords.longitude;
                    $('#location-msg').text('Showing hotels sorted by nearest to your location.');
                    loadHotels($('.search-input').val());
                }, function () {
                    $('#location-msg').text('Location access denied. Showing hotels by rating.');
                });
            }

            $('.search-input').on('input', function () {
                loadHotels($(this).val());
            });
        });
    </script>
</body>

</html>