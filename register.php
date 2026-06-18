<?php
include 'connection/connect.php';

$message = '';

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    // NAME
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $errors[] = 'Name can only contain letters and spaces';
    }

    // EMAIL
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    } else {
        $check = mysqli_query($db, "SELECT * FROM users WHERE Email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email already exists';
        }
    }

    // PHONE
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Phone must be 10 digits';
    }

    // PASSWORD
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // IMAGE VALIDATION
    if (!isset($_FILES['uimage']) || $_FILES['uimage']['error'] != 0) {
        $errors[] = "Image is required";
    } else {
        $image_name = $_FILES['uimage']['name'];
        $image_tmp = $_FILES['uimage']['tmp_name'];
        $image_size = $_FILES['uimage']['size'];

        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Only JPG, PNG, JPEG, GIF allowed";
        } elseif ($image_size > 2 * 1024 * 1024) {
            $errors[] = "Image must be less than 2MB";
        }
    }

    // FINAL PROCESS
    if (empty($errors)) {

        // Create uploads folder if not exists
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Rename image
        $new_image = time() . "_" . basename($image_name);

        // Move image
        move_uploaded_file($image_tmp, $upload_dir . $new_image);


        // INSERT
        $sql = "INSERT INTO users (Name, Email, Phone, uimage, Password)
                VALUES ('$name', '$email', '$phone', '$new_image', '$password')";

        if (mysqli_query($db, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Registration failed</div>';
        }

    } else {
        $message = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Register - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/hotel-cover.jpg');
            background-size: cover;
            position: center;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
        }

        .register-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0 15px;
        }

        .btn-register {
            background: #4B795D;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }

        .btn-register:hover {
            background: #3a5f47;
            color: white;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="register-container">
        <div class="register-form">
            <h2 class="text-center mb-4">Create Account</h2>
            <?php echo $message; ?>

            <form method="POST" id="registerForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required minlength="2" pattern="[a-zA-Z\s]+"
                        title="Name can only contain letters and spaces">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" class="form-control" required pattern="[0-9]{10}"
                        title="Phone number must be exactly 10 digits">
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="uimage" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$"
                        title="Password must contain at least one uppercase letter, one lowercase letter, and one number">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>

                <button type="submit" name="register" class="btn btn-register">Register</button>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Validation functions
            function validateName(name) {
                if (name.length < 2) return 'Name must be at least 2 characters';
                if (!/^[a-zA-Z\s]+$/.test(name)) return 'Name can only contain letters and spaces';
                return '';
            }

            function validateEmail(email) {
                if (email.length < 12) return 'Email must be at least 12 characters';
                if (email.length > 100) return 'Email must not exceed 100 characters';
                if (/[^a-z0-9@._\-+]/.test(email))
                    return 'Email contains invalid special characters or capital letters';
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) return 'Invalid email format';
                return '';
            }

            function validatePhone(phone) {
                if (!/^[0-9]{10}$/.test(phone)) return 'Phone number must be exactly 10 digits';
                return '';
            }

            function validatePassword(password) {
                if (password.length < 6) return 'Password must be at least 6 characters';
                if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])/.test(password)) {
                    return 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character';
                }
                return '';
            }

            function showError(field, message) {
                field.css('border-color', '#dc3545');
                field.next('.error-message').remove();
                if (message) field.after('<small class="text-danger error-message">' + message + '</small>');
            }

            function showSuccess(field) {
                field.css('border-color', '#28a745');
                field.next('.error-message').remove();
            }

            // Real-time validation
            $('input[name="name"]').on('blur keyup', function () {
                const error = validateName($(this).val().trim());
                error ? showError($(this), error) : showSuccess($(this));
            });

            $('input[name="email"]').on('blur', function () {
                const email = $(this).val().trim();
                const error = validateEmail(email);
                if (error) {
                    showError($(this), error);
                    return;
                }
                const $field = $(this);
                $.post('check_email.php', {
                    email: email
                }, function (resp) {
                    resp == '1' ? showError($field, 'Email already registered') : showSuccess(
                        $field);
                });
            });
            $('input[name="email"]').on('keyup', function () {
                const error = validateEmail($(this).val().trim());
                error ? showError($(this), error) : showSuccess($(this));
            });

            $('input[name="phone"]').on('blur keyup', function () {
                const error = validatePhone($(this).val().trim());
                error ? showError($(this), error) : showSuccess($(this));
            });

            $('input[name="password"]').on('blur keyup', function () {
                const error = validatePassword($(this).val());
                error ? showError($(this), error) : showSuccess($(this));
                if ($('#confirm_password').val()) {
                    $('#confirm_password').trigger('keyup');
                }
            });

            $('#confirm_password').on('blur keyup', function () {
                const password = $('#password').val();
                const confirmPassword = $(this).val();

                if (confirmPassword && password !== confirmPassword) {
                    showError($(this), 'Passwords do not match');
                } else if (confirmPassword) {
                    showSuccess($(this));
                }
            });

            // Form submission validation
            $('#registerForm').on('submit', function (e) {
                let isValid = true;
                const name = $('input[name="name"]').val().trim();
                const email = $('input[name="email"]').val().trim();
                const phone = $('input[name="phone"]').val().trim();
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();

                const nameError = validateName(name);
                const emailError = validateEmail(email);
                const phoneError = validatePhone(phone);
                const passwordError = validatePassword(password);

                if (nameError) {
                    showError($('input[name="name"]'), nameError);
                    isValid = false;
                }

                if (emailError) {
                    showError($('input[name="email"]'), emailError);
                    isValid = false;
                }

                if (phoneError) {
                    showError($('input[name="phone"]'), phoneError);
                    isValid = false;
                }

                if (passwordError) {
                    showError($('#password'), passwordError);
                    isValid = false;
                }

                if (password !== confirmPassword) {
                    showError($('#confirm_password'), 'Passwords do not match');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.error-message').first().offset().top - 100
                    }, 500);
                }
            });
        });
    </script>
</body>

</html>