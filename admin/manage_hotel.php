<?php
include 'db_connect.php';

$hotel_id = isset($_GET['id']) ? $_GET['id'] : '';
$hotel = [];

if ($hotel_id) {
    $query = "SELECT * FROM hotels WHERE hotel_id = $hotel_id";
    $result = mysqli_query($conn, $query);
    $hotel = mysqli_fetch_assoc($result);
}
?>

<form id="hotel-form">
    <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">

    <div class="form-group">
        <label>Hotel Name</label>
        <input type="text" name="hotel_name" class="form-control"
            value="<?php echo isset($hotel['hotel_name']) ? $hotel['hotel_name'] : ''; ?>" required>
    </div>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="h_username" class="form-control"
            value="<?php echo isset($hotel['h_username']) ? $hotel['h_username'] : ''; ?>" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="h_password" class="form-control" <?php echo !$hotel_id ? 'required' : ''; ?>>
        <?php if ($hotel_id): ?>
            <small class="text-muted">Leave blank to keep current password</small>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="hotel_description" class="form-control"
            rows="3"><?php echo isset($hotel['hotel_description']) ? $hotel['hotel_description'] : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label>Address</label>
        <textarea name="hotel_address" class="form-control"
            rows="2"><?php echo isset($hotel['hotel_address']) ? $hotel['hotel_address'] : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label>Location Coordinates <small class="text-muted">(used for nearest hotel sorting)</small></label>
        <div class="row">
            <div class="col-md-5">
                <input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude"
                    value="<?php echo isset($hotel['latitude']) ? $hotel['latitude'] : ''; ?>">
            </div>
            <div class="col-md-5">
                <input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude"
                    value="<?php echo isset($hotel['longitude']) ? $hotel['longitude'] : ''; ?>">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary btn-block" id="detect-location" title="Auto-detect location">
                    <i class="fa fa-map-marker"></i>
                </button>
            </div>
        </div>
        <small class="text-muted">Click the pin button to auto-detect your current location, or enter coordinates manually.</small>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="hotel_phone" class="form-control"
            value="<?php echo isset($hotel['hotel_phone']) ? $hotel['hotel_phone'] : ''; ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="hotel_email" class="form-control"
            value="<?php echo isset($hotel['hotel_email']) ? $hotel['hotel_email'] : ''; ?>">
    </div>

    <div class="form-group">
        <label>Rating</label>
        <select name="hotel_rating" class="form-control">
            <option value="1" <?php echo (isset($hotel['hotel_rating']) && $hotel['hotel_rating'] == 1) ? 'selected' : ''; ?>>1 Star
            </option>
            <option value="2" <?php echo (isset($hotel['hotel_rating']) && $hotel['hotel_rating'] == 2) ? 'selected' : ''; ?>>2 Stars
            </option>
        </select>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="active" <?php echo (isset($hotel['status']) && $hotel['status'] == 'active') ? 'selected' : ''; ?>>Active
            </option>
            <option value="inactive" <?php echo (isset($hotel['status']) && $hotel['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive
            </option>
        </select>
    </div>

    <div class="form-group">
        <label>Hotel Photo</label>
        <?php if ($hotel_id && !empty($hotel['hotel_image'])): ?>
            <div class="mb-2">
                <img src="upload/<?php echo $hotel['hotel_image']; ?>"
                    style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px;"
                    alt="Current Hotel Photo">
                <p class="text-muted small mt-1">Current photo</p>
            </div>
        <?php endif; ?>
        <input type="file" name="hotel_image" id="hotel_image" class="form-control" accept="image/*">
        <small class="text-muted">Upload a new photo to replace the current one (JPG, PNG, GIF)</small>
    </div>
</form>

<script>
    $('#hotel-form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'save_hotel.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (resp) {
                if (resp == 1) {
                    alert('Hotel saved successfully');
                    $('#uni_modal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to save hotel: ' + resp);
                }
            }
        });
    });

    $('#detect-location').on('click', function () {
        if (navigator.geolocation) {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            var btn = $(this);
            navigator.geolocation.getCurrentPosition(function (pos) {
                $('input[name="latitude"]').val(pos.coords.latitude.toFixed(7));
                $('input[name="longitude"]').val(pos.coords.longitude.toFixed(7));
                btn.html('<i class="fa fa-check"></i>');
            }, function () {
                alert('Could not detect location. Please enter coordinates manually.');
                btn.html('<i class="fa fa-map-marker"></i>');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });
</script>