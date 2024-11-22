<?php
include ('cookie.php');
?>
<html>
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
        .events {
            background-color: white;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .events h2 {
            color: #C5B4A5;
        }

        .events p {
            font-size: 18px;
            color: #5C4033;
        }

        .events button {
            background-color: #d3a029;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .events button:hover {
            background-color: #7F6C54;
        }

        .note {
            font-size: 16px;
            color: #7F6C54;
            margin-top: 10px;
        }

        .note strong {
            color: #C5B4A5;
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

    $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $eventData = null;
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : null; //must include this or is there way to get from login section

    if ($eventID) {
        if ($action == "upcoming") {
            $sql = "SELECT * FROM events WHERE eventID = $eventID AND eventStatus='Upcoming' ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $eventData = $row;
                    echo "<div class='events'>";
                    echo "<h2>Event Name: " . $eventData['eventName'] . "</h2>";
                    echo "<img src='" . $row['eventPic'] . "' alt='" . $row['eventName'] . "' width='300' height='200'>";
                    echo "<p><strong>Details:</strong> " . $eventData['details'] . "</p>";
                    echo "<p><strong>Start Date & Time:</strong> " . $eventData['start_dateTime'] . "</p>";
                    echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $eventData['location'] . "</p>";
                    echo "<p><strong>Registrations Needed:</strong> " . $eventData['participantsNeeded'] . "</p>";
                    echo "<p><strong>Volunteers Needed:</strong> " . $eventData['volunteersNeeded'] . "</p>";
                    echo "<p class='note'><strong>Note:</strong> Participants are those who will attend the event, while volunteers are individuals who help with event operations.</p>";
                    echo "</div>";
                    if ($memberID){
                        echo "<a href='eventRegistrations.php?eventID=" . $row['eventID'] . "'><button>Register Now!</button></a>";
                    }
                    else{
                        echo "Please login to register for events"; //or maybe should do popup alert and jump to login page
                    }
                }
            }
        } elseif ($action == "past") {
            $sql = "SELECT * FROM events e, pastevents p WHERE e.eventID = $eventID AND e.eventID = p.eventID AND e.eventStatus='Past' ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $eventData = $row;
                    echo "<div class='events'>";
                    echo "<h2>Event Name: " . $eventData['eventName'] . "</h2>";
                    echo "<img src='" . $row['eventPic'] . "' alt='" . $row['eventName'] . "' width='300' height='200'>";
                    echo "<p><strong>Details:</strong> " . $eventData['details'] . "</p>";
                    echo "<p><strong>Start Date & Time:</strong> " . $eventData['start_dateTime'] . "</p>";
                    echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $eventData['location'] . "</p>";
                    echo "<p><strong>Participants Needed:</strong> " . $eventData['participantsNeeded'] . "</p>";
                    echo "<p><strong>Volunteers Needed:</strong> " . $eventData['volunteersNeeded'] . "</p>";
                    echo "<p class='note'><strong>Note:</strong> Participants are those who will attend the event, while volunteers are individuals who help with event operations.</p>";
                    echo "</div>";
                    echo "<a href='eventRegistrations.php?eventID=" . $row['eventID'] . "'><button>Register Now!</button></a>";
                }
            }
        }
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
