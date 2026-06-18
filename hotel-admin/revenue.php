<?php
$hotel_id = $_SESSION['hotel_id'];

// Get revenue statistics
$total_revenue = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total_amount) as total FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'confirmed'"))['total'] ?? 0;

$monthly_revenue = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total_amount) as total FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'confirmed' AND MONTH(b.booked_at) = MONTH(CURRENT_DATE()) AND YEAR(b.booked_at) = YEAR(CURRENT_DATE())"))['total'] ?? 0;

$pending_revenue = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total_amount) as total FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'pending'"))['total'] ?? 0;

$confirmed_bookings = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'confirmed'"))['count'] ?? 0;
?>

<div class="container-fluid">
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-chart-line"></i> Revenue Management
            </h2>
            <p class="mb-0" style="color: #000000;">Track and analyze your hotel's revenue performance</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2rem; font-weight: 700; color: #4B795D;">
                                R <?php echo number_format($total_revenue); ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Total Revenue</div>
                        </div>
                        <div style="background: #4B795D; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2rem; font-weight: 700; color: #4B795D;">
                                R <?php echo number_format($monthly_revenue); ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">This Month</div>
                        </div>
                        <div style="background: #E8E1D8; color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2rem; font-weight: 700; color: #4B795D;">
                                R <?php echo number_format($pending_revenue); ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Pending</div>
                        </div>
                        <div style="background: #4B795D; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-size: 2rem; font-weight: 700; color: #4B795D;">
                                <?php echo $confirmed_bookings; ?>
                            </div>
                            <div style="color: #000000; font-weight: 500;">Confirmed Bookings</div>
                        </div>
                        <div style="background: #E8E1D8; color: #4B795D; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;"><i class="fas fa-chart-line"></i> Revenue Trend</h4>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Revenue Details</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> Booking ID</th>
                                    <th><i class="fas fa-user"></i> Guest</th>
                                    <th><i class="fas fa-bed"></i> Room Type</th>
                                    <th><i class="fas fa-calendar"></i> Check-in</th>
                                    <th><i class="fas fa-calendar"></i> Check-out</th>
                                    <th><i class="fas fa-info-circle"></i> Status</th>
                                    <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                    <th><i class="fas fa-clock"></i> Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $revenue_query = "SELECT b.*, r.rtype, u.Name as guest_name 
                                            FROM booked b 
                                            JOIN rooms r ON b.r_id = r.r_id 
                                            JOIN users u ON b.ID = u.ID 
                                            WHERE r.hotel_id = $hotel_id 
                                            ORDER BY b.booked_at DESC";
                            $revenue_result = mysqli_query($db, $revenue_query);
                            
                            if ($revenue_result && mysqli_num_rows($revenue_result) > 0) {
                                while ($row = mysqli_fetch_assoc($revenue_result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><?php echo $row['guest_name']; ?></td>
                                <td><?php echo $row['rtype']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['check_in'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['check_out'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $row['booking_status'] == 'confirmed' ? 'success' : 
                                            ($row['booking_status'] == 'pending' ? 'warning' : 'danger'); 
                                    ?>" style="padding: 8px 15px; border-radius: 20px; font-weight: 500;">
                                        <?php echo ucfirst($row['booking_status']); ?>
                                    </span>
                                </td>
                                <td style="font-weight: 600; color: #4B795D;">R <?php echo number_format($row['total_amount']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['booked_at'])); ?></td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">No revenue records found</td></tr>';
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php
// Get monthly revenue data for the last 12 months
$monthly_data = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $revenue = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total_amount) as total FROM booked b JOIN rooms r ON b.r_id = r.r_id WHERE r.hotel_id = $hotel_id AND booking_status = 'confirmed' AND DATE_FORMAT(b.booked_at, '%Y-%m') = '$month'"))['total'] ?? 0;
    $monthly_data[] = ['month' => date('M Y', strtotime($month)), 'revenue' => $revenue];
}
?>

const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthly_data, 'month')); ?>,
        datasets: [{
            label: 'Revenue (R)',
            data: <?php echo json_encode(array_column($monthly_data, 'revenue')); ?>,
            borderColor: '#4B795D',
            backgroundColor: 'rgba(75, 121, 93, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#4B795D',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: R ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
