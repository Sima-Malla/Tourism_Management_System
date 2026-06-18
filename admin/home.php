<?php
include('../connection/connect.php');

// Get statistics with error checking
$hotels_query = mysqli_query($db, "SELECT * FROM hotels");
$total_hotels = $hotels_query ? mysqli_num_rows($hotels_query) : 0;

$users_query = mysqli_query($db, "SELECT * FROM users");
$total_users = $users_query ? mysqli_num_rows($users_query) : 0;

$contacts_query = mysqli_query($db, "SELECT * FROM contact");
$total_contacts = $contacts_query ? mysqli_num_rows($contacts_query) : 0;

$bookings_query = mysqli_query($db, "SELECT * FROM booked");
$total_bookings = $bookings_query ? mysqli_num_rows($bookings_query) : 0;
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-chart-line"></i> Dashboard Overview
            </h2>
            <p class="mb-0" style="color: #64748b;">Welcome back, <?php echo $_SESSION['login_name']; ?>! Here's what's happening with your hotel management system.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $total_hotels; ?>
                            </div>
                            <div style="color: #64748b; font-weight: 500;">Total Hotels</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #4B795D, #3a5f47); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $total_users; ?>
                            </div>
                            <div style="color: #64748b; font-weight: 500;">Registered Users</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffffff, #f5f5f5); color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $total_bookings; ?>
                            </div>
                            <div style="color: #64748b; font-weight: 500;">Total Bookings</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #4B795D, #3a5f47); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #4B795D; margin-bottom: 8px;">
                                <?php echo $total_contacts; ?>
                            </div>
                            <div style="color: #64748b; font-weight: 500;">Contact Messages</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffffff, #f5f5f5); color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4B795D; font-weight: 600;">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=hotels" class="btn btn-primary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-building mb-2" style="font-size: 1.5rem; display: block;"></i>
                                Manage Hotels
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=users" class="btn btn-secondary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-users mb-2" style="font-size: 1.5rem; display: block;"></i>
                                Manage Users
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=booked" class="btn btn-primary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-calendar-check mb-2" style="font-size: 1.5rem; display: block;"></i>
                                View Bookings
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="?page=contacts" class="btn btn-secondary w-100 py-3" style="border-radius: 12px;">
                                <i class="fas fa-envelope mb-2" style="font-size: 1.5rem; display: block;"></i>
                                View Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4B795D; font-weight: 600;">
                        <i class="fas fa-info-circle"></i> System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong style="color: #4B795D;"><i class="fas fa-building"></i> Hotel Name:</strong>
                                <span style="color: #64748b;">TourStay Hotel Management</span>
                            </div>
                            <div class="mb-3">
                                <strong style="color: #4B795D;"><i class="fas fa-code"></i> System Version:</strong>
                                <span style="color: #64748b;">2.0.0</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong style="color: #4B795D;"><i class="fas fa-clock"></i> Current Time:</strong>
                                <span style="color: #64748b;"><?php echo date('Y-m-d H:i:s'); ?></span>
                            </div>
                            <div class="mb-3">
                                <strong style="color: #4B795D;"><i class="fas fa-user-shield"></i> Admin User:</strong>
                                <span style="color: #64748b;"><?php echo $_SESSION['login_name']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4B795D; font-weight: 600;">
                        <i class="fas fa-chart-pie"></i> Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span style="color: #64748b;">Active Hotels</span>
                        <span style="color: #4B795D; font-weight: 600;"><?php echo $total_hotels; ?></span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span style="color: #64748b;">Total Users</span>
                        <span style="color: #4B795D; font-weight: 600;"><?php echo $total_users; ?></span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span style="color: #64748b;">Bookings</span>
                        <span style="color: #4B795D; font-weight: 600;"><?php echo $total_bookings; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="color: #64748b;">Messages</span>
                        <span style="color: #4B795D; font-weight: 600;"><?php echo $total_contacts; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>