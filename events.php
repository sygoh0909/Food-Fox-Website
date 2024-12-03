<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main{
            color: white;
        }
        .events {
            padding: 20px;
            background-color: #F5EEDC;
            text-align: center;
        }
        .events h2 {
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 10px;
            margin-top: 30px;
            border-bottom: 2px solid #d3a029;
            display: inline-block;
            padding-bottom: 10px;
        }

        .upcoming-events, .past-events {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .event-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .event-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .event-card h3 {
            font-size: 1.5rem;
            color: #5C4033;
            margin: 15px 10px;
        }

        .event-card button {
            background-color: #d3a029;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 15px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .event-card button:hover {
            background-color: #a17e23;
            transform: scale(1.05);
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
    <section class="events">
        <h2>Upcoming Events</h2>
        <div class="upcoming-events">
            <?php
            $conn = connection();
            $sql = "SELECT * FROM events WHERE eventStatus='Upcoming'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='event-card'>";
                    echo "<img src='" . $row['eventPic'] . "' alt='" . $row['eventPic'] . "'>";
                    echo "<h3>" . $row['eventName'] . "</h3>"; //show last updated when also?
                    echo "<a href='eventInfo.php?eventID=" .$row['eventID']." &action=upcoming'><button type='button'>View more info</button></a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No upcoming events</p>";
            }
            ?>
        </div>
        <h2>Past Events</h2>
        <div class="past-events">
            <?php
            $conn = connection();
            $sql = "SELECT * FROM events WHERE eventStatus='Past'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='event-card'>";
                    echo "<img src='" . $row['eventPic'] . "' alt='" . $row['eventPic'] . "'>";
                    echo "<h3>" . $row['eventName'] . "</h3>";
                    echo "<a href='eventInfo.php?eventID=" .$row['eventID']." &action=past'><button type='button'>View more info</button></a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No past events</p>";
            }
            ?>
        </div>
    </section>
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
