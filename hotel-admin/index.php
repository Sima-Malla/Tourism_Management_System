<?php
session_start();
if (!isset($_SESSION['hotel_id'])) {
    header('location:../login.php');
    exit();
}

include("../connection/connect.php");

$hotel_id = $_SESSION['hotel_id'];
$hotel_query = "SELECT * FROM hotels WHERE hotel_id = $hotel_id";
$hotel_result = mysqli_query($db, $hotel_query);
$hotel = mysqli_fetch_assoc($hotel_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo $hotel['hotel_name']; ?> - Admin Panel</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        min-height: 100vh;
        color: #000000;
    }

    /* Header Styles */
    .admin-header {
        background: linear-gradient(135deg, #4B795D 0%, #3a5f47 100%);
        height: 70px;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
    }

    .admin-logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-logo img {
        height: 40px;
        width: auto;
    }

    .admin-logo h2 {
        color: white;
        font-weight: 600;
        font-size: 1.4rem;
        margin: 0;
    }

    .admin-user {
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .user-dropdown {
        position: relative;
    }

    .user-toggle {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-toggle:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
    }

    .dropdown-menu {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        margin-top: 10px;
        min-width: 150px;
    }

    .dropdown-item {
        padding: 12px 20px;
        color: #2c3e50;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 4px;
    }

    .dropdown-item:hover {
        background: #4B795D;
        color: white;
    }

    /* Sidebar Styles */
    .admin-sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        width: 280px;
        height: calc(100vh - 70px);
        background: #E8E1D8;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        z-index: 999;
        overflow-y: auto;
    }

    .sidebar-nav {
        padding: 20px 0;
    }

    .nav-item {
        margin: 0 15px 8px 15px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        color: #64748b;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
        border-left: 3px solid transparent;
    }

    .nav-link:hover {
        background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
        color: #4B795D;
        text-decoration: none;
        transform: translateX(5px);
        border-left-color: #8B9A7A;
    }

    .nav-link.active {
        background: linear-gradient(135deg, #4B795D 0%, #3a5f47 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(75, 121, 93, 0.3);
        border-left-color: #8B9A7A;
    }

    .nav-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    /* Main Content */
    .admin-main {
        margin-left: 280px;
        margin-top: 70px;
        padding: 30px;
        min-height: calc(100vh - 70px);
    }

    /* Cards */
    .admin-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid #8B9A7A;
        transition: all 0.3s ease;
    }

    .admin-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border-top-color: #4B795D;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid rgba(232, 225, 216, 0.5);
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 25px;
    }

    .card-body {
        padding: 25px;
        background: #e8e1d8;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #4B795D 0%, #3a5f47 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #3a5f47 0%, #2d4a37 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(75, 121, 93, 0.3);
    }

    .btn-secondary {
        background: #8B9A7A;
        border: none;
        color: white;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #7a8a69;
        color: white;
    }

    /* Tables */
    .table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background: white;
        color: #4B795D;
        border: none;
        padding: 15px;
        font-weight: 600;
        position: relative;
    }

    .table thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #8B9A7A;
    }

    .table tbody td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #E8E1D8 0%, #ddd4c7 100%);
        border-bottom: 1px solid rgba(232, 225, 216, 0.5);
        border-radius: 16px 16px 0 0;
        padding: 20px 25px;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid rgba(232, 225, 216, 0.5);
        padding: 20px 25px;
    }

    /* Form Controls */
    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 5px 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4B795D;
        box-shadow: 0 0 0 3px rgba(75, 121, 93, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .admin-sidebar.show {
            transform: translateX(0);
        }

        .admin-main {
            margin-left: 0;
        }

        .admin-header {
            padding: 0 15px;
        }
    }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="admin-logo">
            <img src="../images/log1.png" alt="TourStay Logo">
            <h2><?php echo $hotel['hotel_name']; ?> - Admin</h2>
        </div>

        <div class="admin-user">
            <div class="dropdown user-dropdown">
                <a href="#" class="user-toggle" data-toggle="dropdown">
                    <i class="fas fa-user-circle"></i>
                    Hotel Admin
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="../login.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="index.php?page=home" class="nav-link nav-home">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="index.php?page=rooms" class="nav-link nav-rooms">
                    <i class="fas fa-bed"></i>
                    <span>My Rooms</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="index.php?page=bookings" class="nav-link nav-bookings">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="index.php?page=revenue" class="nav-link nav-revenue">
                    <i class="fas fa-chart-line"></i>
                    <span>Revenue</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="index.php?page=profile" class="nav-link nav-profile">
                    <i class="fas fa-building"></i>
                    <span>Hotel Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        $allowed_pages = ['home', 'rooms', 'bookings', 'revenue', 'profile'];

        if (in_array($page, $allowed_pages)) {
            $file = $page . '.php';
            if (file_exists($file)) {
                include $file;
            } else {
                include 'home.php';
            }
        } else {
            include 'home.php';
        }
        ?>
    </main>

    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='submit'
                        onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>').addClass('active');
    window.uni_modal = function($title = '', $url = '') {
        $.ajax({
            url: $url,
            success: function(resp) {
                if (resp) {
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    $('#uni_modal').modal('show')
                }
            }
        })
    }
    </script>
</body>

</html>