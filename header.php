<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="css/font-awesome.min.css">
<header class="abs" style="height: 100px; ">
    <div class="bottom-header" style="padding: 0px 0px; width: 100%;">
        <div class="container" style="padding-left:0px; padding-right:0px; width: 100%; max-width: 100%;">
            <div class="hd"
                style="height: 60px; align-items: center; width: 100%;background-color: #E8E1D8 !important;">
                <div class="logo">
                    <a href="index.php" title="">
                        <img src="images/log1.png" alt="" style="max-height: 30px; width: auto;" />
                    </a>
                </div>
                <div class="menu-search">
                    <nav>
                        <ul>
                            <li
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?> menu-item-has-children">
                                <a href="index.php" title="">Home</a>
                            </li>
                            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'hotels.php' ? 'active' : ''; ?>">
                                <a href="hotels.php" title="">Hotels</a>
                            </li>
                            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                                <a href="about.php" title="">About Us</a>
                            </li>
                            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'term.php' ? 'active' : ''; ?>">
                                <a href="term.php" title="">Terms & Condition</a>
                            </li>
                            <!-- <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                                <a href="contact.php" title="">Contact Us</a>
                            </li> -->
                            <?php if (isset($_SESSION['user_id']) || isset($_SESSION['user_email'])): ?>
                                <li class="menu-item-has-children">
                                    <a href="#" title="">

                                        <?php if (!empty($user_image) && file_exists("uploads/" . $user_image)): ?>

                                            <!-- Show uploaded image -->
                                            <img src="uploads/<?php echo $user_image; ?>"
                                                style="width:30px; height:30px; border-radius:50%; object-fit:cover; margin-right:5px;">

                                        <?php else: ?>

                                            <!-- Default icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                                class="bi bi-person-circle" viewBox="0 0 16 16" style="margin-right: 5px;">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                <path fill-rule="evenodd"
                                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                            </svg>

                                        <?php endif; ?>

                                    </a>
                                    <ul class="sub-menu">
                                        <li><a href="profile.php">My Profile</a></li>
                                        <li><a href="my-bookings.php">My Bookings</a></li>
                                        <li><a href="logout.php">Logout</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                                    <a href="login.php" title="">Login</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="menu-icon">
                    <span class="first-bar"></span>
                    <span class="second-bar"></span>
                    <span class="third-bar"></span>
                </div>
            </div>
        </div>
    </div>
</header>