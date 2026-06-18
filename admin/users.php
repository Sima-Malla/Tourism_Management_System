<?php include('../connection/connect.php'); ?>
<link rel="stylesheet" href="admin-style.css">

<style>
.users-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.users-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.users-card .card-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    padding: 20px;
}

.users-table thead th {
    background: #E8E1D8;
    color: #4B795D;
    border: none;
    padding: 15px;
    font-weight: 600;
}

.users-table tbody tr {
    transition: all 0.3s ease;
}

.users-table tbody tr:hover {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.users-table td {
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
</style>

<div class="container-fluid" style="background: linear-gradient(rgba(232, 225, 216, 0.3), rgba(232, 225, 216, 0.3)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover fixed; min-height: 100vh; padding: 20px;">
    <!-- Welcome Header -->
    <div class="admin-card mb-4">
        <div class="card-header text-center">
            <h2 class="mb-2" style="color: #4B795D; font-weight: 600;">
                <i class="fas fa-users"></i> Manage Users
            </h2>
            <p class="mb-0" style="color: #64748b;">View and manage registered users</p>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="card admin-card">
            <div class="card-header">
                <h5 style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Registered Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table users-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag"></i> ID</th>
                                <th><i class="fa fa-user"></i> Name</th>
                                <th><i class="fa fa-envelope"></i> Email</th>
                                <th><i class="fa fa-phone"></i> Phone</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Try different possible table and column names
                            $query = "SELECT * FROM users ORDER BY u_id DESC";
                            $result = mysqli_query($db, $query);

                            // If first query fails, try with 'id' column
                            if (!$result) {
                                $query = "SELECT * FROM users ORDER BY id DESC";
                                $result = mysqli_query($db, $query);
                            }

                            // If still fails, try 'user' table
                            if (!$result) {
                                $query = "SELECT * FROM user ORDER BY u_id DESC";
                                $result = mysqli_query($db, $query);
                            }

                            // If still fails, try 'user' table with 'id'
                            if (!$result) {
                                $query = "SELECT * FROM user ORDER BY id DESC";
                                $result = mysqli_query($db, $query);
                            }

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Determine which ID column exists
                                    $user_id = isset($row['ID']) ? $row['ID'] : (isset($row['id']) ? $row['id'] : 'N/A');
                                    ?>
                                    <tr>
                                        <td><?php echo $user_id; ?></td>
                                        <td><?php echo isset($row['Name']) ? $row['Name'] : (isset($row['name']) ? $row['name'] : 'N/A'); ?>
                                        </td>
                                        <td><?php echo isset($row['Email']) ? $row['Email'] : (isset($row['email']) ? $row['email'] : 'N/A'); ?>
                                        </td>
                                        <td><?php echo isset($row['Phone']) ? $row['Phone'] : (isset($row['phone']) ? $row['phone'] : 'N/A'); ?>
                                        </td>
                                        <!-- <td><?php echo isset($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : 'N/A'; ?>
                                        </td> -->
                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-info btn-action" onclick="showUser('<?php echo addslashes(isset($row['Name']) ? $row['Name'] : $row['name']); ?>','<?php echo addslashes(isset($row['Email']) ? $row['Email'] : $row['email']); ?>','<?php echo addslashes(isset($row['Phone']) ? $row['Phone'] : $row['phone']); ?>')" title="View User">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <a href="index.php?page=users&delete_id=<?php echo $user_id; ?>"
                                                    class="btn btn-sm btn-danger btn-action"
                                                    onclick="return confirm('Are you sure you want to delete this user?');" title="Delete User">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>No users found or database error: " . mysqli_error($db) . "</td></tr>";
                            } ?>
<?php
// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Try different possible ID column names
    $sql = "DELETE FROM users WHERE ID = $delete_id";
    if (!mysqli_query($db, $sql)) {
        $sql = "DELETE FROM users WHERE u_id = $delete_id";
        if (!mysqli_query($db, $sql)) {
            $sql = "DELETE FROM users WHERE id = $delete_id";
        }
    }
    
    if (mysqli_query($db, $sql)) {
        echo "<script>alert('User deleted successfully!'); window.location.href = 'index.php?page=users';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($db) . "');</script>";
    }
}

// Handle edit request
if (isset($_POST['edit_user'])) {
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $sql = "UPDATE users SET Name='$name', Email='$email', Phone='$phone' WHERE ID=$edit_id";
    if (!mysqli_query($db, $sql)) {
        $sql = "UPDATE users SET Name='$name', Email='$email', Phone='$phone' WHERE u_id=$edit_id";
        if (!mysqli_query($db, $sql)) {
            $sql = "UPDATE users SET Name='$name', Email='$email', Phone='$phone' WHERE id=$edit_id";
        }
    }
    
    if (mysqli_query($db, $sql)) {
        echo "<script>alert('User updated successfully!'); window.location.href = 'index.php?page=users';</script>";
    } else {
        echo "<script>alert('Error updating user: " . mysqli_error($db) . "');</script>";
    }
}
?>
</div>

<!-- Custom User View Overlay -->
<div id="userOverlay" onclick="closeUser()" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;"></div>
<div id="userPanel" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:16px; padding:30px; width:400px; max-width:90%; z-index:10000; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h5 style="color:#4B795D; margin:0; font-weight:600;"><i class="fa fa-user"></i> User Details</h5>
        <button onclick="closeUser()" style="background:none; border:none; font-size:20px; cursor:pointer; color:#999;">&times;</button>
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" id="uName" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" id="uEmail" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" id="uPhone" class="form-control" readonly>
    </div>
    <div style="text-align:right; margin-top:10px;">
        <button onclick="closeUser()" class="btn btn-secondary">Close</button>
    </div>
</div>

<script>
function showUser(name, email, phone) {
    document.getElementById('uName').value = name;
    document.getElementById('uEmail').value = email;
    document.getElementById('uPhone').value = phone;
    document.getElementById('userOverlay').style.display = 'block';
    document.getElementById('userPanel').style.display = 'block';
}
function closeUser() {
    document.getElementById('userOverlay').style.display = 'none';
    document.getElementById('userPanel').style.display = 'none';
}
</script>