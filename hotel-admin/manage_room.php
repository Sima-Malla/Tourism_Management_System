<?php
session_start();
include("../connection/connect.php");

$room_id = isset($_GET['id']) ? $_GET['id'] : '';
$hotel_id = $_SESSION['hotel_id'];
$room = [];

if ($room_id) {
    $query = "SELECT * FROM rooms WHERE r_id = $room_id AND hotel_id = $hotel_id";
    $result = mysqli_query($db, $query);
    $room = mysqli_fetch_assoc($result);
}
?>

<form id="room-form" enctype="multipart/form-data">
    <input type="hidden" name="r_id" value="<?php echo $room_id; ?>">
    <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
    
    <div class="form-group">
        <label>Room Type</label>
        <select name="rtype" class="form-control" required>
            <option value="">Select Room Type</option>
            <option value="Single Room" <?php echo (isset($room['rtype']) && $room['rtype'] == 'Single Room') ? 'selected' : ''; ?>>Single Room</option>
            <option value="Double Room" <?php echo (isset($room['rtype']) && $room['rtype'] == 'Double Room') ? 'selected' : ''; ?>>Double Room</option>
            <option value="First Room" <?php echo (isset($room['rtype']) && $room['rtype'] == 'First Room') ? 'selected' : ''; ?>>First Class Room</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Price (per night)</label>
        <input type="number" name="rprice" class="form-control" value="<?php echo isset($room['rprice']) ? $room['rprice'] : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="rtext" class="form-control" rows="4" required><?php echo isset($room['rtext']) ? $room['rtext'] : ''; ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Room Image</label>
        <input type="file" name="rimage" class="form-control" accept="image/*" <?php echo !$room_id ? 'required' : ''; ?>>
        <?php if ($room_id && isset($room['rimage'])): ?>
        <small class="text-muted">Current image: <?php echo $room['rimage']; ?></small>
        <?php endif; ?>
    </div>
</form>

<script>
$('#room-form').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: 'save_room.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp == 1) {
                alert('Room saved successfully');
                $('#uni_modal').modal('hide');
                location.reload();
            } else {
                alert('Failed to save room');
            }
        }
    });
});
</script>