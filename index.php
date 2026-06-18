<?php include 'header.php'; ?>

<?php
if (isset($_GET['search'])) {
    header("location:search.php");
}

?>




<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <title>TourStay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
        <link rel="stylesheet" type="text/css" href="css/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

    <style>
        @media (min-width:1200px) {
            .container {
                width: 1600px;
            }
        }

        p {
            font-size: 18px;
        }

        .service-det:hover .srvc-icon-img,
        .fancy-nav .slick-current img,
        .company-infoo h4,
        .comment::after,
        .search-sd-bar button {
            border-color: red;
        }

        /* ========== */
        /* .home-banner {
    display: none;
} */

        .col-md-12 {
            display: flex;
            width: 100%;

        }

        .room-finding {
            float: left;
            width: 60%;
            position: relative;
        }

        .find-room {
            width: 100%;
        }

        .form-container {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }

        .form-container div {
            width: 30%;
        }

        #home>.block {
            padding-top: 390px;
        }

        .home-detial {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .other-options {
            float: left;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #e5e5e5;
            padding: 35px 30px 20px 30px;
        }

        .feature-container {
            width: 45%;
            display: flex;
            justify-content: space-between;
        }

        .feature-container img {
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Banner Section -->
        <section class="hero-banner"
            style="background-image: url('images/hotel-cover.jpg'); width:100%; background-position: center; height: 600px; position: relative;">
            <div class="banner-overlay"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4);">
            </div>
            <div class="container"
                style="position: relative; z-index: 2; height: 100%; display: flex; align-items: center;">
                <div class="banner-content" style="color: white; text-align: center; width: 100%;">
                    <h1 style="font-size: 48px; font-weight: bold; margin-bottom: 20px;">Welcome to TourStay</h1>
                    <p style="font-size: 20px; margin-bottom: 30px; color: #ffffff !important;">Experience luxury and
                        comfort in our premium hotel rooms</p>
                    <a href="hotels.php" class="btn btn-primary"
                        style="padding: 15px 30px; font-size: 18px; background: #4B795D; border: none; border-radius: 5px; text-decoration: none; color: white;">View
                        Our Hotels</a>
                </div>
            </div>
        </section>

        <section>
            <div class="block" style="padding-top:50px !important">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="about-us">
                                <div class="title">
                                    <h3>About TourStay</h3>
                                </div>
                                <h5><a href="about.html" title="">Discover what makes us a five star hostel</a></h5>
                                <p>We offer a wide variety of carefully selected accommodation options to ensure that
                                    unique travel requirements are met for business trips, romantic getaways, successful
                                    conferences, memorable family holidays, golf and beach vacations as well as
                                    luxurious train journeys</p>
                                <ul class="ab-links">
                                    <li><i class="fa fa-user-secret"></i>Dedicated Team</li>
                                    <li><i class="fa fa-user"></i>Best Advisors</li>
                                    <li><i class="fa fa-history"></i>24/7 Supports</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="fav-areas">
                                <div class="title f2">
                                    <h3>Our Favorite Rooms</h3>
                                </div>
                                <p>We at Hotela offer you and family the best accommodation and service you dream of, we
                                    have variety of rooms for you to choose from.
                                </p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fav-room">
                                            <img src="images/resources/fav-room1.jpg" alt="">
                                            <div class="fav-rm-data">
                                                <h3><a href="#" title=""> Single Room</a></h3>

                                            </div>
                                        </div>
                                        <!--fav-room end-->
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fav-room">
                                            <img src="images/resources/fav-room2.jpg" alt="">
                                            <div class="fav-rm-data">
                                                <h3><a href="#" title="">Double Room</a></h3>

                                            </div>
                                        </div>
                                        <!--fav-room end-->
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fav-room">
                                            <img src="images/resources/fav-room3.jpg" alt="">
                                            <div class="fav-rm-data">
                                                <h3><a href="#" title="">First Class Room</a></h3>

                                            </div>
                                        </div>
                                        <!--fav-room end-->
                                    </div>
                                </div>
                            </div>
                            <!--fav areas end-->
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="pd2 bg bg1 overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="ft-img thumb-carousel" data-slider-id="1">
                                <div>
                                    <figure>
                                        <img src="images/resources/ft-img3.jpg" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <figure>
                                        <img src="images/resources/ft-img4.jpg" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <figure>
                                        <img src="images/resources/ft-img.jpg" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <figure>
                                        <img src="images/resources/ft-img2.jpg" alt="">
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="our-services">
                                <div class="title f2">
                                    <h3>Our Awesome Services</h3>
                                </div>
                                <!--title end-->
                                <div class="service-thumbs owl-thumbs" data-slider-id="1">
                                    <div class="service owl-thumb-item">
                                        <img src="images/res.png" alt="">
                                        <h4>Restaurant</h4>
                                        <p>Our restaurants comes with specializes in producing the best quality food
                                            that comes from the best range of the world.</p>
                                    </div>
                                    <!--service end-->
                                    <div class="service owl-thumb-item">
                                        <img src="images/spar.png" alt="">
                                        <h4>Spa - Beauty & Health</h4>
                                        <p>
                                            Feeling the strain of the daily grind? Whether you need your massage in Cape
                                            Town, Sandton, Pretoria or Port Elizabeth</p>
                                    </div>
                                    <!--service end-->
                                    <div class="service owl-thumb-item">
                                        <img src="images/meeting.png" alt="">
                                        <h4>Conference Room</h4>
                                        <p>
                                            Hotel Conference Venue Guide has a comprehensive selection of conference
                                            venues, ranging from boardrooms to convention centres. Make an enquiry now
                                        </p>
                                    </div>
                                    <!--service end-->
                                    <div class="service owl-thumb-item">
                                        <img src="images/swim.png" alt="">
                                        <h4>Swimming Pool</h4>
                                        <p>
                                            at TourStay we own 11 swimming pools in all seven city regions to keep
                                            residents cool during the hot summer season</p>
                                    </div>
                                    <!--service end-->
                                </div>
                            </div>
                            <!--our-services end-->
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="block remove-btm-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="title f3">
                                <h3>Our Gallery</h3>
                            </div>
                            <div class="options">
                                <div class="option-isotop">
                                    <ul id="filter" class="option-set filters-nav" data-option-key="filter">
                                        <li><a class="selected" data-option-value="*">Rooms Highligts</a></li>

                                    </ul>
                                </div>
                            </div><!-- FILTER BUTTONS -->
                        </div>
                        <div class="col-md-10">
                            <div class="row gallery grid">
                                <div class="col-md-5 col-sm-5 col-xs-5 rooms swimming">
                                    <div class="grid-item2 width-auto">
                                        <figure>
                                            <img src="images/resources/01.jpg" alt="">
                                            <figcaption>
                                                <h5>Bed room</h5>
                                                <ul>
                                                    <li><a href="#" title=""><i class="fa fa-television"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-wifi"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-video-camera"></i></a></li>
                                                </ul>
                                            </figcaption>
                                            <div class="popup-icon">
                                                <a class="html5lightbox" data-thumbnail="images/resources/gallery1.jpg"
                                                    data-group="set1" href="images/resources/gallery1.jpg"
                                                    title="home 1"><i class="fa fa-compress"></i></a>
                                            </div>
                                        </figure>
                                    </div>
                                </div>


                                <div class=" col-md-2 col-sm-2 col-xs-2  kitchen">
                                    <div class="grid-item2 width-auto">
                                        <figure>
                                            <img src="images/resources/02.jpg" alt="">
                                            <figcaption>
                                                <h5>Bed room</h5>
                                                <ul>
                                                    <li><a href="#" title=""><i class="fa fa-television"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-wifi"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-video-camera"></i></a></li>
                                                </ul>
                                            </figcaption>
                                            <div class="popup-icon">
                                                <a class="html5lightbox" data-thumbnail="images/resources/gallery2.jpg"
                                                    data-group="set1" href="images/resources/gallery2.jpg"
                                                    title="home 2"><i class="fa fa-compress"></i></a>
                                            </div>
                                        </figure>
                                    </div>
                                </div>


                                <div class="col-md-5 col-sm-5 col-xs-5 dinning bath">
                                    <div class="grid-item2 width-auto">
                                        <figure>
                                            <img src="images/resources/03.jpg" alt="">
                                            <figcaption>
                                                <h5>Bed room</h5>
                                                <ul>
                                                    <li><a href="#" title=""><i class="fa fa-television"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-wifi"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-video-camera"></i></a></li>
                                                </ul>
                                            </figcaption>
                                            <div class="popup-icon">
                                                <a class="html5lightbox" data-thumbnail="images/resources/gallery3.jpg"
                                                    data-group="set1" href="images/resources/gallery3.jpg"
                                                    title="home 3"><i class="fa fa-compress"></i></a>
                                            </div>
                                        </figure>
                                    </div>
                                </div>



                                <div class="col-md-5  col-sm-5 col-xs-5  bath rooms">
                                    <div class="grid-item2 width-auto">
                                        <figure>
                                            <img src="images/resources/04.jpg" alt="">
                                            <figcaption>
                                                <h5>Bed room</h5>
                                                <ul>
                                                    <li><a href="#" title=""><i class="fa fa-television"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-wifi"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-video-camera"></i></a></li>
                                                </ul>
                                            </figcaption>
                                            <div class="popup-icon">
                                                <a class="html5lightbox" data-thumbnail="images/resources/gallery4.jpg"
                                                    data-group="set1" href="images/resources/gallery4.jpg"
                                                    title="home 4"><i class="fa fa-compress"></i></a>
                                            </div>
                                        </figure>
                                    </div>
                                </div>

                                <div class=" col-md-7 col-sm-7 col-xs-7  swimming kitchen">
                                    <div class="grid-item2 width-auto">
                                        <figure>
                                            <img src="images/resources/05.jpg" alt="">
                                            <figcaption>
                                                <h5>Bed room</h5>
                                                <ul>
                                                    <li><a href="#" title=""><i class="fa fa-television"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-wifi"></i></a></li>
                                                    <li><a href="#" title=""><i class="fa fa-video-camera"></i></a></li>
                                                </ul>
                                            </figcaption>
                                            <div class="popup-icon">
                                                <a class="html5lightbox" data-thumbnail="images/resources/gallery5.jpg"
                                                    data-group="set1" href="images/resources/gallery5.jpg"
                                                    title="home 5"><i class="fa fa-compress"></i></a>
                                            </div>
                                        </figure>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="pd-btm-less">
                <div class="container">
                    <div class="partners-logo">

                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="block no-padding">
                <div class="newsletter">
                    <div class="bg bg2">
                        <div class="container">
                            <div class="stay-tuned">
                                <h2>Stay tuned with us</h2>
                                <h5>Get our updated offers, discounts, events and much more!</h5>
                            </div>
                            <div class="email-form">
                                <form>
                                    <input name="" placeholder="Enter your email address" type="text">
                                    <button type="submit">Subscribe</button>
                                </form>
                            </div>
                            <!--email-form end-->
                        </div>
                    </div>
                </div>
                <!--newsletter end-->
            </div>
        </section>
        <?php include 'footer.php'; ?>
    </div>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/flatpickr.min.js"></script>
    <script type="text/javascript" src="js/isotope.js"></script>
    <script type="text/javascript" src="js/html5lightbox.js"></script>
    <script type="text/javascript" src="js/wow.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>