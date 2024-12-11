<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Activity Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main{
            color: white;
        }

        .header{
            text-align: center;
        }

        h2{
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 10px;
            margin-top: 30px;
            border-bottom: 2px solid #d3a029;
            display: inline-block;
            padding-bottom: 10px;
        }
        .activity-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }

        .activity-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }

        .activity-item {
            background: #FFFFFF;
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #6B5A48;
            box-shadow: 0px 6px 12px rgba(92, 64, 51, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .activity-item:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(92, 64, 51, 0.3);
        }

        .activity-header {
            background-color: #D3A029;
            color: #FFFFFF;
            text-align: center;
            font-size: 1.4em;
            font-weight: bold;
            padding: 15px;
            border-bottom: 1px solid #6B5A48;
        }

        .activity-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
            text-align: center;
        }

        .activity-body span {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
            color: #6B5A48;
        }

        .activity-body span i {
            font-size: 1.2em;
            color: #D3A029;
        }

        .activity-footer {
            padding: 15px;
            text-align: center;
            background-color: #5C4033;
            color: #FFFFFF;
            font-size: 0.9em;
            font-style: italic;
            border-top: 1px solid #6B5A48;
        }

        p.no-data {
            text-align: center;
            color: #6B5A48;
            font-size: 1.2em;
            font-style: italic;
            margin-top: 20px;
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

    $sql = "
        (SELECT CONCAT('Event Registration - ', r.registerType) AS activityType, e.eventName AS activityName, r.registrationDate AS activityDate 
         FROM events e 
         INNER JOIN registrations r ON e.eventID = r.eventID 
         WHERE r.memberID = $memberID)
        UNION
        (SELECT 'Donation' AS activityType, d.amount AS activityName, d.donationDate AS activityDate 
         FROM donations d 
         WHERE d.memberID = $memberID)
        ORDER BY activityDate DESC ";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='header'><h2>Recent Activities</h2></div>";
        echo "<div class='activity-list'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $activityType = $row['activityType'];
            $activityName = $row['activityName'];
            $activityDate = $row['activityDate'];
            $dateFormatted = date('d-m-Y', strtotime($activityDate));

            echo "<div class='activity-item'>";
            echo "<div class='activity-header'>$activityType</div>";
            echo "<div class='activity-body'>";
            echo "<span><i class='fa fa-calendar'></i>Date: $dateFormatted</span>";
            echo "<span><i class='fa fa-book'></i>Activity: $activityName</span>";
            echo "</div>";
            echo "<div class='activity-footer'>Stay involved and make an impact!</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='no-data'>No recent activities found.</p>";
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
