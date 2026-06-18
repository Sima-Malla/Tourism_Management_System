<?php include('../connection/connect.php'); ?>
<link rel="stylesheet" href="admin-style.css">

<style>
.rooms-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.rooms-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.rooms-card .card-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    padding: 20px;
}

.rooms-table thead th {
    background: #E8E1D8;
    color: #4B795D;
    border: none;
    padding: 15px;
    font-weight: 600;
}

.rooms-table tbody tr {
    transition: all 0.3s ease;
}

.rooms-table tbody tr:hover {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.rooms-table td {
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

.add-room-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 25px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.add-room-btn:hover {
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
                    <i class="fas fa-bed"></i> Manage Rooms
                </h2>
                <p class="mb-0" style="color: #64748b;">Add, edit and manage room listings</p>
            </div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addRoomModal">
                <i class="fas fa-plus"></i> Add New Room
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card admin-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table rooms-table">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-hashtag"></i> ID</th>
                                    <th><i class="fa fa-image"></i> Image</th>
                                    <th><i class="fa fa-bed"></i> Room Type</th>
                                    <th><i class="fa fa-money"></i> Price</th>
                                    <th><i class="fa fa-info-circle"></i> Description</th>
                                    <th><i class="fa fa-circle"></i> Status</th>
                                    <th><i class="fa fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_query($db, "UPDATE rooms r SET r.status = 'occupied' WHERE EXISTS (SELECT 1 FROM booked b WHERE b.r_id = r.r_id AND b.booking_status IN ('confirmed','pending') AND CURDATE() BETWEEN b.check_in AND b.check_out)");
                                mysqli_query($db, "UPDATE rooms r SET r.status = 'available' WHERE r.status = 'occupied' AND NOT EXISTS (SELECT 1 FROM booked b WHERE b.r_id = r.r_id AND b.booking_status IN ('confirmed','pending') AND CURDATE() BETWEEN b.check_in AND b.check_out)");

                                $query = "SELECT * FROM rooms ORDER BY r_id DESC";
                                $result = mysqli_query($db, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                <tr>
                                    <td><?php echo $row['r_id']; ?></td>
                                    <td>
                                        <img src="upload/<?php echo $row['rimage']; ?>"
                                            style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                                    </td>
                                    <td><?php echo $row['rtype']; ?></td>
                                    <td>R <?php echo $row['rprice']; ?>/-</td>
                                    <td><?php echo substr($row['rtext'], 0, 50) . '...'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $row['status'] == 'available' ? 'success' : ($row['status'] == 'occupied' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-primary btn-action" data-toggle="modal"
                                                data-target="#editRoomModal<?php echo $row['r_id']; ?>" title="Edit Room">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="index.php?page=rooms&delete_id=<?php echo $row['r_id']; ?>"
                                                class="btn btn-sm btn-danger btn-action"
                                                onclick="return confirm('Are you sure you want to delete this room?');" title="Delete Room">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Room Modal -->
                                <div class="modal fade" id="editRoomModal<?php echo $row['r_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Room</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="edit_id" value="<?php echo $row['r_id']; ?>">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Room Type</label>
                                                        <input type="text" name="rtype" class="form-control"
                                                            value="<?php echo $row['rtype']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Price per Night</label>
                                                        <input type="number" name="rprice" class="form-control"
                                                            value="<?php echo $row['rprice']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea name="rtext" class="form-control" rows="3"
                                                            required><?php echo $row['rtext']; ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Room Image (leave empty to keep current)</label>
                                                        <input type="file" name="rimage" class="form-control"
                                                            accept="image/*">
                                                        <small>Current: <?php echo $row['rimage']; ?></small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="edit_room"
                                                        class="btn btn-primary">Update
                                                        Room</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Room</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Room Type</label>
                            <input type="text" name="rtype" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price per Night</label>
                            <input type="number" name="rprice" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="rtext" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Room Image</label>
                            <input type="file" name="rimage" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_room" class="btn btn-success">Add Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Handle delete request
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql = "DELETE FROM rooms WHERE r_id = $delete_id";
        if (mysqli_query($db, $sql)) {
            echo "<script>alert('Room deleted successfully!'); window.location.href = 'index.php?page=rooms';</script>";
        } else {
            echo "<script>alert('Error deleting room: " . mysqli_error($db) . "');</script>";
        }
    }

    // Handle edit request
    if (isset($_POST['edit_room'])) {
        $edit_id = $_POST['edit_id'];
        $rtype = $_POST['rtype'];
        $rprice = $_POST['rprice'];
        $rtext = $_POST['rtext'];

        if (!empty($_FILES["rimage"]["name"])) {
            $target_dir = "upload/";
            $target_file = $target_dir . basename($_FILES["rimage"]["name"]);
            if (move_uploaded_file($_FILES["rimage"]["tmp_name"], $target_file)) {
                $rimage = basename($_FILES["rimage"]["name"]);
                $sql = "UPDATE rooms SET rtype='$rtype', rprice='$rprice', rtext='$rtext', rimage='$rimage' WHERE r_id=$edit_id";
            }
        } else {
            $sql = "UPDATE rooms SET rtype='$rtype', rprice='$rprice', rtext='$rtext' WHERE r_id=$edit_id";
        }

        if (mysqli_query($db, $sql)) {
            echo "<script>alert('Room updated successfully!'); window.location.href = 'index.php?page=rooms';</script>";
        } else {
            echo "<script>alert('Error updating room: " . mysqli_error($db) . "');</script>";
        }
    }

    if (isset($_POST['add_room'])) {
        $rtype = $_POST['rtype'];
        $rprice = $_POST['rprice'];
        $rtext = $_POST['rtext'];

        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["rimage"]["name"]);

        if (move_uploaded_file($_FILES["rimage"]["tmp_name"], $target_file)) {
            $rimage = basename($_FILES["rimage"]["name"]);
            $sql = "INSERT INTO rooms (rtype, rprice, rtext, rimage) VALUES ('$rtype', '$rprice', '$rtext', '$rimage')";

            if (mysqli_query($db, $sql)) {
                echo "<script>alert('Room added successfully!'); window.location.href = 'index.php?page=rooms';</script>";
            }
        }
    }
    ?>
</div>