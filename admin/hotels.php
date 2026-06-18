<?php include('../connection/connect.php'); ?>
<link rel="stylesheet" href="admin-style.css">

<style>
.hotels-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.hotels-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.hotels-card .card-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    padding: 20px;
}

.hotels-table {
    margin: 0;
}

.hotels-table thead th {
    background: #E8E1D8;
    color: #4B795D;
    border: none;
    padding: 15px;
    font-weight: 600;
}

.hotels-table tbody tr {
    transition: all 0.3s ease;
}

.hotels-table tbody tr:hover {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.hotels-table td {
    padding: 15px;
    vertical-align: middle;
    border: none;
    border-bottom: 1px solid #f0f0f0;
}

.btn-action {
    margin: 2px;
    border-radius: 20px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.badge-status {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
}

.star-rating .fa-star { color: #FFD700; }
.star-rating .fa-star-o { color: #ccc; }

.add-hotel-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 25px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.add-hotel-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}
</style>

<div class="container-fluid" style="background: linear-gradient(rgba(232, 225, 216, 0.3), rgba(232, 225, 216, 0.3)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover fixed; min-height: 100vh; padding: 20px;">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                    <i class="fas fa-building"></i> Hotels Management
                </h2>
                <p class="mb-0" style="color: #64748b;">Manage and monitor all registered hotels</p>
            </div>
            <button class="btn btn-primary" onclick="uni_modal('Add New Hotel','manage_hotel.php')">
                <i class="fas fa-plus"></i> Add Hotel
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Hotel Directory</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table hotels-table">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-hashtag"></i> ID</th>
                                    <th><i class="fa fa-building"></i> Hotel Name</th>
                                    <th><i class="fa fa-user"></i> Username</th>
                                    <th style="width: 120px;"><i class="fa fa-map-marker"></i> Address</th>
                                    <th><i class="fa fa-phone"></i> Phone</th>
                                    <th><i class="fa fa-envelope"></i> Email</th>
                                    <th><i class="fa fa-star"></i> Rating</th>
                                    <th><i class="fa fa-info-circle"></i> Status</th>
                                    <th style="width: 200px;"><i class="fa fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hotels_query = "SELECT * FROM hotels ORDER BY hotel_id DESC";
                                $hotels_result = mysqli_query($conn, $hotels_query);
                                
                                if ($hotels_result && mysqli_num_rows($hotels_result) > 0) {
                                    while ($hotel = mysqli_fetch_assoc($hotels_result)) {
                                        // Count rooms for this hotel
                                        $rooms_count_query = "SELECT COUNT(*) as count FROM rooms WHERE hotel_id = {$hotel['hotel_id']}";
                                        $rooms_count_result = mysqli_query($conn, $rooms_count_query);
                                        $rooms_count = $rooms_count_result ? mysqli_fetch_assoc($rooms_count_result)['count'] : 0;
                                ?>
                                <tr>
                                    <td><?php echo $hotel['hotel_id']; ?></td>
                                    <td><?php echo $hotel['hotel_name']; ?></td>
                                    <td><?php echo $hotel['h_username']; ?></td>
                                    <td><?php echo substr($hotel['hotel_address'], 0, 30) . '...'; ?></td>
                                    <td><?php echo $hotel['hotel_phone']; ?></td>
                                    <td><?php echo $hotel['hotel_email']; ?></td>
                                    <td>
                                        <div class="star-rating">
                                            <?php 
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $hotel['hotel_rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                            }
                                            echo " <span class='text-muted'>({$hotel['hotel_rating']})</span>";
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-status badge-<?php echo $hotel['status'] == 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($hotel['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="index.php?page=hotel_rooms&id=<?php echo $hotel['hotel_id']; ?>" class="btn btn-sm btn-success btn-action" title="View Available Rooms">
                                                <i class="fa fa-bed"></i>
                                            </a>
                                            <button class="btn btn-sm btn-info btn-action" onclick="uni_modal('Hotel Details','view_hotel.php?id=<?php echo $hotel['hotel_id']; ?>')" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary btn-action" onclick="uni_modal('Edit Hotel','manage_hotel.php?id=<?php echo $hotel['hotel_id']; ?>')" title="Edit Hotel">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action" onclick="delete_hotel(<?php echo $hotel['hotel_id']; ?>)" title="Delete Hotel">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="9" class="text-center">No hotels found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function delete_hotel(id) {
    if (confirm('Are you sure you want to delete this hotel? This will also delete all associated rooms and bookings.')) {
        $.ajax({
            url: 'delete_hotel.php',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                if (resp == 1) {
                    alert('Hotel deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete hotel');
                }
            }
        });
    }
}
</script>