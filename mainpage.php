<?php
ob_start(); //better to not use this
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .banner{
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .banner img{
            width: 100%;
            height: 100%;
            display: block; /*remove extra spaces around the image*/
            object-fit: cover;
        }
        .overlay-content{
            position: absolute;
            display: flex;
            flex-direction: column;
            top: 0;
            left: 0;
        }
        .logo img{
            width: 60%;
            height: 50%;
        }
        .companyDescription{
            padding-left: 100px;
            color: white;
        }
        .companyDescription h1{
            margin-top: 0;
            font-size: 80px;
            font-weight: bold;
        }
        .companyDescription p{
            margin-bottom: 70px;
            font-size: 20px;
        }
        .join{
            background-color: white;
            color: #d3a029;
            padding: 20px 25px;
            border-radius: 10px;
        }
        .overlay-nav{
            position: absolute;
            display: flex;
            gap: 50px;
            top: 50px;
            left: 400px;
        }
        .overlay-nav a{
            text-decoration: none;
            color: white;
            font-size: larger;
        }
        .overlay-nav a:hover{
            border-radius: 25px;
            background-color: rgba(255,255,255,0.2);
        }
        .main-info {
            display: flex;
            flex-direction: column;
            gap: 50px;
            padding: 30px;
        }

        .main-info section {
            padding: 40px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            width: 90%;
            background-color: #fdfdfd;
        }

        .main-info section:nth-child(odd) {
            background-color: #f7f1e3;
        }

        .main-info section h2 {
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #d3a029;
            display: inline-block;
            padding-bottom: 10px;
        }

        .main-info section p {
            font-size: 1.1rem;
            color: #333;
            line-height: 1.6;
            text-align: justify;
        }

        .about-us-images{
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .about-us-images img{
            width: 50%;
            height: 50%;
            object-fit: cover;
        }

        .team-member {
            text-align: center;
        }

        .team-member div {
            display: inline-block;
            margin: 15px;
            text-align: center;
        }

        .team-member img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .team-member img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
        }

        .team-member p {
            margin-top: 10px;
            color: #5C4033;
            font-size: 1rem;
            font-weight: bold;
        }

        .getinvolved img,
        .approach img,
        .aboutus img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 10px 0;
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

            <div class="nav-links">
                <?php
                loginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
    <main>
        <div class="banner">
            <img src="banner.png" alt="Banner">
            <div class="overlay-content">
                <div class="logo">
                    <img src="logo.png" alt="Logo">
                </div>
                <div class="companyDescription">
                    <h1>Food Fox</h1>
                    <p>"Join Food Fox in the fight to make zero hunger a reality for everyone."</p>
                    <a href="signup.php" class="roundButton join">Join Us Now!</a> <!--if already login then jump main page-->
                </div>
                <div class="overlay-nav">
                    <a href="mainpage.php" class="roundButton main">Home</a>
                    <a href="events.php" class="roundButton main">Events</a>
                    <a href="donations.php" class="roundButton main">Donation</a>
                    <a href="contact.php" class="roundButton main">Contact</a>
                </div>
            </div>
        </div>
        <div class="main-info">
            <section class="aboutus">
                <h2>About Us</h2>
                <p>xxx</p>
                <div class="about-us-images">
                    <img src="aboutus1.jpeg" alt="About Us 1">
                    <img src="aboutus2.jpeg" alt="About Us 2">
                </div>
            </section>
            <section class="team-member">
                <h2>Our Team</h2>
                <div class="team-member1">
                    <img src="logo.png" alt="Team Member 1">
                    <p><b>Goh Shu Yi</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member2">
                    <img src="logo.png" alt="Team Member 2">
                    <p><b>Tiong Jia Yi</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member3">
                    <img src="logo.png" alt="Team Member 3">
                    <p><b>Yim Sook Xin</b></p>
                    <p>xxx</p>
                </div>
                <div class="team-member4">
                    <img src="logo.png" alt="Team Member 4">
                    <p><b>Phang Pei Mei</b></p>
                    <p>xxx</p>
                </div>
            </section>
            <section class="approach">
                <h2>Our Approach</h2>
                <p>Food Fox is a Malaysian based non-profit organisation focused on providing food to the underprivileged community who striuggle to put food on the table everyday.</p>
            </section>
            <section class="getinvolved">
                <h2>Get Involved</h2>
                <p>You do not need to be a chef to be involved in out work! <br> Everyone with a kind heart that wants to give back to their community is welcome to join us in our mission to achieve SDG 2: Zero Hunger.</p>
                <img src="getinvolved.jpeg" alt="Get Involved">
            </section>
        </div>
    </main>
</body>

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
</html>
