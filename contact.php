<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Food Fox</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main {
            color: white;
        }

        .contact-container {
            padding: 50px 30px;
            max-width: 900px;
            margin: 50px auto;
            text-align: center;
            background-color: #faf4ed;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: #5C4033;
        }

        .contact-heading {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #7F6C54;
            border-bottom: 2px solid #d3a029;
            display: inline-block;
            padding-bottom: 5px;
        }

        .contact-info h3 {
            font-size: 24px;
            margin-top: 25px;
            color: #7F6C54;
            font-weight: bold;
        }

        .contact-info p {
            font-size: 18px;
            line-height: 1.8;
            margin: 10px 0;
            color: #4a4a4a;
        }

        .contact-info a {
            color: #d3a029;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: #7F6C54;
        }

        .map-container {
            margin-top: 30px;
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .map-container iframe:hover {
            transform: scale(1.01);
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="social-media">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>

            <div class="main-links">
                <a href="mainpage.php" class="roundButton main">Home</a>
                <a href="events.php" class="roundButton main">Events</a>
                <a href="donations.php" class="roundButton main">Donation</a>
                <a href="contact.php" class="roundButton main">Contact</a>
            </div>

            <div class="nav-links">
                <?php
                loginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <div class="contact-container">
        <h1 class="contact-heading">Get In Touch</h1>
        <div class="contact-info">
            <h3>Headquarters</h3>
            <p>51, Jalan Binjai, KLCC, KL City Centre, Kuala Lumpur</p>
            <h3>Email</h3>
            <p><a href="mailto:info@foodfox.org.my">info@foodfox.org.my</a></p> <!--open a mail with auto display email?-->
            <h3>Phone</h3>
            <p>+603-0929 0501</p>
        </div>
        <div class="map-container">
            <h3>Find Us Here</h3>
            <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.470981641436!2d101.715123!3d3.155348!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc49562a6b123f%3A0xabcde1234567890!2s51%2C%20Jalan%20Binjai%2C%20KLCC%2C%20KL%20City%20Centre%2C%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1691234567890"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
            </iframe>
        </div>
    </div>
</main>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>About Us</h4>
            <p>Food Fox is a Malaysian-based non-profit organization focused on providing food to the underprivileged community.</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="mainpage.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Follow Us</h4>
            <div class="social-links">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>
        </div>
        <div class="footer-section">
            <h4>Contact Info</h4>
            <p>Email: info@foodfox.org.my</p>
            <p>Phone Number: +603-0929 0501</p>
            <p>Food Fox Headquarters: 51, Jalan Binjai, KLCC, KL City Centre, Kuala Lumpur</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Food Fox. All rights reserved. | Powered by <a href="https://foodfox.com" target="_blank">Food Fox</a></p>
    </div>
</footer>
</body>
</html>
