<?php include('../connection/connect.php'); ?>
<link rel="stylesheet" href="admin-style.css">

<style>
.contacts-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.contacts-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.contacts-card .card-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    padding: 20px;
}

.contacts-table thead th {
    background: #E8E1D8;
    color: #4B795D;
    border: none;
    padding: 15px;
    font-weight: 600;
}

.contacts-table tbody tr {
    transition: all 0.3s ease;
}

.contacts-table tbody tr:hover {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.contacts-table td {
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
                <i class="fas fa-envelope"></i> Contact Messages
            </h2>
            <p class="mb-0" style="color: #64748b;">Manage customer inquiries and messages</p>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="card admin-card">
            <div class="card-header">
                <h5 style="color: #4B795D; font-weight: 600;"><i class="fas fa-list"></i> Customer Inquiries</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table contacts-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag"></i> ID</th>
                                <th><i class="fa fa-user"></i> Name</th>
                                <th><i class="fa fa-envelope"></i> Email</th>
                                <th><i class="fa fa-tag"></i> Subject</th>
                                <th><i class="fa fa-comment"></i> Message</th>
                                <th><i class="fa fa-calendar"></i> Date</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM contact ORDER BY id DESC";
                            $result = mysqli_query($db, $query);
                            
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['subject']; ?></td>
                                        <td><?php echo substr($row['message'], 0, 50) . '...'; ?></td>
                                        <td><?php echo isset($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-info btn-action" onclick="viewMessage(<?php echo $row['id']; ?>)" 
                                                        data-toggle="modal" data-target="#messageModal"
                                                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                        data-subject="<?php echo htmlspecialchars($row['subject']); ?>"
                                                        data-message="<?php echo htmlspecialchars($row['message']); ?>" title="View Message">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary btn-action" data-toggle="modal" data-target="#editContactModal<?php echo $row['id']; ?>" title="Edit Message">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="index.php?page=contacts&delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this message?');" title="Delete Message">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Contact Modal -->
                                    <div class="modal fade" id="editContactModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Contact Message</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="POST">
                                                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Subject</label>
                                                            <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($row['subject']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Message</label>
                                                            <textarea name="message" class="form-control" rows="4" required><?php echo htmlspecialchars($row['message']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="edit_contact" class="btn btn-primary">Update Message</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No contact messages found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Message Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Name:</strong> <span id="modal-name"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong> <span id="modal-email"></span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <strong>Subject:</strong> <span id="modal-subject"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <strong>Message:</strong>
                        <div class="border p-3 mt-2" id="modal-message"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="replyMessage()">Reply</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#messageModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    $('#modal-name').text(button.data('name'));
    $('#modal-email').text(button.data('email'));
    $('#modal-subject').text(button.data('subject'));
    $('#modal-message').text(button.data('message'));
});

function viewMessage(id) {
    // Modal will be populated by the data attributes
}

<?php
// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM contact WHERE id = $delete_id";
    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Contact message deleted successfully!'); window.location.href = 'index.php?page=contacts';</script>";
    } else {
        echo "<script>alert('Error deleting message: " . mysqli_error($db) . "');</script>";
    }
}

// Handle edit request
if (isset($_POST['edit_contact'])) {
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $sql = "UPDATE contact SET name='$name', email='$email', subject='$subject', message='$message' WHERE id=$edit_id";
    
    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Contact message updated successfully!'); window.location.href = 'index.php?page=contacts';</script>";
    } else {
        echo "<script>alert('Error updating message: " . mysqli_error($db) . "');</script>";
    }
}
?>
</div>