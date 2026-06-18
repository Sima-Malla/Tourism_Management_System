<?php
include 'header.php';
include 'connection/connect.php';

$message = '';
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $msg = $_POST['message'];

    $sql = "INSERT INTO contact(name,email,subject,message) VALUES('$name', '$email','$subject','$msg')";
    if (mysqli_query($db, $sql)) {
        $message = '<div class="alert alert-success">Thank you! Your message has been sent successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Sorry, there was an error sending your message.</div>';
    }
}
?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <title>Contact Us - TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Linking Bootstrap css file -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- Linking Main Css file -->
    <link rel="stylesheet" type="text/css" href="css/animate.css">
            <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">


    <style>
    body {
        margin: 0;
        padding: 0;
    }

    .contact-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/hotel-cover.jpg');
        background-size: cover;
        background-position: center;
        padding: 100px 0;
        color: white;
        text-align: center;
    }

    .contact-hero h1 {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .contact-hero p {
        font-size: 20px;
        margin-bottom: 0;
    }

    .contact-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .contact-form {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .contact-form textarea {
        height: 120px;
        resize: vertical;
    }

    .btn-contact {
        background: #4B795D;
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-contact:hover {
        background: #3a5f47;
    }

    .contact-info {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .contact-item i {
        background: #4B795D;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 20px;
    }

    .contact-item h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .contact-item p {
        margin: 0;
        color: #666;
    }

    .social-links {
        margin-top: 30px;
    }

    .social-links a {
        display: inline-block;
        padding: 10px 5px;
        width: 40px;
        height: 40px;
        background: #4B795D;
        color: white;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        transition: background 0.3s;
    }

    .social-links a:hover {
        background: #3a5f47;
        text-decoration: none;
        color: white;
    }

    @media (min-width:1200px) {
        .container {
            width: 1620px;
        }
    }
    </style>
</head>


<body>

    <div class="wrapper">



        <!--Header end-->

        <div class="contact-hero">
            <div class="container">
                <h1>Contact Us</h1>
                <p style="color:white;">Get in touch with us for any inquiries or reservations</p>
            </div>
        </div>



        <section class="contact-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="contact-form">
                            <h3 style="margin-bottom: 30px; color: #333;">Send us a Message</h3>
                            <?php echo $message; ?>

                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="name" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" placeholder="Your Email" required>
                                    </div>
                                </div>
                                <input type="text" name="subject" placeholder="Subject" required>
                                <textarea name="message" placeholder="Your Message" required></textarea>
                                <button type="submit" name="submit" class="btn-contact">Send Message</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-info">
                            <h3 style="margin-bottom: 30px; color: #333;">Get in Touch</h3>

                            <div class="contact-item">
                                <i class="fa fa-map-marker"></i>
                                <div>
                                    <h4>Address</h4>
                                    <p>Kalanki-14<br>Kathmandu</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fa fa-phone"></i>
                                <div>
                                    <h4>Phone</h4>
                                    <p>9865435234</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fa fa-envelope"></i>
                                <div>
                                    <h4>Email</h4>
                                    <p>sima.malla@gmail.com</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fa fa-clock-o"></i>
                                <div>
                                    <h4>Business Hours</h4>
                                    <p>Sun-Mon: 24/7<br>Always Available</p>
                                </div>
                            </div>

                            <div class="social-links">
                                <h4 style="margin-bottom: 15px; color: #333;">Follow Us</h4>
                                <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"><i
                                        class="fa fa-facebook"></i></a>
                                <a href="https://www.twitter.com/" target="_blank" rel="noopener noreferrer"><i
                                        class="fa fa-twitter"></i></a>
                                <a href="https://www.instagram.com/simamallaroyalthakuri/" target="_blank"
                                    rel="noopener noreferrer"><i class="fa fa-instagram"></i></a>
                                <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer"><i
                                        class="fa fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>

    </div>
    <!--wrapper end-->

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>


</body>


<!-- Mirrored from creativethemes.us/relax/reservation.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Sep 2017 15:23:47 GMT -->

</html>