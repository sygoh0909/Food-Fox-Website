<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Event Info Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>

        .main{
            color: white;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 30px;
            background-color: #f2f2f2;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            width: 90%;
            margin: auto;
        }

        .activity-item {
            display: flex;
            flex-direction: column;
            padding: 20px;
            background-color: #dfe6e9;
            border: 1px solid #b2bec3;
            border-radius: 10px;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, background-color 0.3s ease;
            font-size: 1.1em;
        }

        .activity-item:hover {
            background-color: #ced6e0;
            transform: scale(1.02);
        }

        .activity-item span {
            margin: 8px 0;
            font-size: 1em;
            color: #333;
        }

        .activity-item .event-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #2d3436;
        }

        .activity-item .register-type {
            font-size: 0.9em;
            color: #636e72;
        }

        p.no-data {
            text-align: center;
            color: #666;
            font-size: 1.4em;
            margin-top: 40px;
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
    <?php
    $conn = connection();
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : '';

    $sql = "SELECT e.eventName, r.registrationDate, r.registerType 
        FROM events e, registrations r 
        WHERE r.memberID = $memberID AND e.eventID = r.eventID 
        ORDER BY r.registrationDate DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='activity-list'>"; // Container for activities
        while ($row = mysqli_fetch_assoc($result)) {
            $eventName = $row['eventName'];
            $registrationDate = $row['registrationDate'];
            $registerType = $row['registerType'];

            echo "<div class='activity-item'>";
            echo "<span class='event-name'>Registered Event: $eventName</span>";
            echo "<span>Registered Date: $registrationDate</span>";
            echo "<span class='register-type'>Register Type: $registerType</span>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='no-data'>No recent registrations found.</p>";
    }
    ?>
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
