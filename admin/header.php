<meta content="" name="descriptison">
<meta content="" name="keywords">



<!-- Google Fonts -->
<link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">


<!-- Vendor CSS Files -->
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="assets/DataTables/datatables.min.css" rel="stylesheet">


<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="assets/css/jquery-te-1.4.0.css">

<style>
body {
    padding-top: 100px;
}

.abs {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    background: #fff;
    box-shadow: 0px 2px 15px rgba(25, 119, 204, 0.1);
}

.hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
    padding: 0 15px;
}

.logo img {
    max-height: 30px;
    width: auto;
}

.menu-search nav ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.menu-search nav ul li {
    margin: 0 15px;
    position: relative;
}

.menu-search nav ul li a {
    color: #2c4964;
    text-decoration: none;
    font-weight: 500;
    padding: 5px 10px;
    border-bottom: 2px solid transparent;
    transition: 0.3s;
}

.menu-search nav ul li a:hover,
.menu-search nav ul li.active a {
    color: #1977cc;
    border-bottom-color: #1977cc;
}

.menu-item-has-children .sub-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
    padding: 10px 0;
    min-width: 150px;
    opacity: 0;
    visibility: hidden;
    transition: 0.3s;
}

.menu-item-has-children:hover .sub-menu {
    opacity: 1;
    visibility: visible;
}

.sub-menu li {
    margin: 0 !important;
}

.sub-menu li a {
    padding: 10px 20px;
    display: block;
    border: none !important;
}

.menu-icon {
    display: none;
}

@media (max-width: 768px) {
    .menu-search {
        display: none;
    }

    .menu-icon {
        display: block;
    }
}
</style>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/DataTables/datatables.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/venobox/venobox.min.js"></script>
<script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="assets/vendor/counterup/counterup.min.js"></script>
<script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Set admin session variables if not set
if (!isset($_SESSION['setting_hotel_name'])) {
  $_SESSION['setting_hotel_name'] = 'TourStay Admin Panel';
}
if (!isset($_SESSION['admin_name']) && isset($_SESSION['admin_id'])) {
  $_SESSION['admin_name'] = 'Administrator';
}
?>

<header class="abs"
    style="height: 100px; position: fixed; top: 0; width: 100%; z-index: 1000; background: #fff; box-shadow: 0px 2px 15px rgba(25, 119, 204, 0.1);">
    <div class="bottom-header" style="padding: 0px 0px;">
        <div class="container-fluid">
            <div class="hd" style="height: 60px; align-items: center;">
                <div class="logo">
                    <a href="index.php" title="">
                        <img src="../images/log1.png" alt="" style="max-height: 30px; width: auto;" />
                    </a>
                </div>
                <div class="menu-search">
                    <nav>
                        <ul>

                            <?php if (isset($_SESSION['admin_id'])): ?>
                            <li class="menu-item-has-children">
                                <a href="#" title="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-person-circle" viewBox="0 0 16 16" style="margin-right: 5px;">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                </a>
                                <ul class="sub-menu">
                                    <li><a href="../login.php">Logout</a></li>
                                </ul>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="menu-icon">
                    <span class="first-bar"></span>
                    <span class="second-bar"></span>
                    <span class="third-bar"></span>
                </div>
            </div>
        </div>
    </div>
</header>