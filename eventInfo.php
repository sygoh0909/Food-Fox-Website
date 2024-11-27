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
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .events img {
            width: 100%;
            height: auto;
            border-radius: 15px 15px 0 0;
        }

        .events h2 {
            color: #4a4a4a;
            margin-bottom: 10px;
            font-size: 24px;
            text-align: center;
        }

        .events p {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
            line-height: 1.5;
        }

        .events p strong {
            color: #7F6C54;
        }

        button {
            background-color: #d3a029;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 20px auto 0;
            width: fit-content;
        }

        button:hover {
            background-color: #7F6C54;
        }

        .note {
            font-size: 14px;
            color: #5C4033;
            margin-top: 10px;
            text-align: center;
        }

        .note strong {
            color: #C5B4A5;
        }

        .event-highlight {
            background-color: #f8f5f2;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .event-highlight p {
            margin: 0;
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
    $memberID = $_SESSION['memberID'];
    //must include this or is there way to get from login section

    if ($eventID) {
        if ($action == "upcoming") {
            $sql = "SELECT * FROM events WHERE eventID = $eventID AND eventStatus='Upcoming' ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $eventData = $row;
                    echo "<div class='events'>";
                    echo "<h2>Event Name: " . $eventData['eventName'] . "</h2>";
                    echo "<img src='" . $row['eventPic'] . "' alt='" . $row['eventPic'] . "' width='300' height='200'>";
                    echo "<div class='event-highlight'>";
                    echo "<p><strong>Details:</strong> " . $eventData['details'] . "</p>";
                    echo "<p><strong>Start Date & Time:</strong> " . $eventData['start_dateTime'] . "</p>";
                    echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $eventData['location'] . "</p>";
                    echo "</div>";
                    echo "<div class='event-highlight'>";
                    echo "<p><strong>Registrations Needed:</strong> " . $eventData['participantsNeeded'] . "</p>";
                    echo "<p><strong>Volunteers Needed:</strong> " . $eventData['volunteersNeeded'] . "</p>";
                    echo "</div>";
                    echo "<p class='note'><strong>Note:</strong> Participants are those who will attend the event, while volunteers are individuals who help with event operations.</p>";
                    echo "</div>";

                    if ($memberID){
                        echo "<a href='eventRegistrations.php?eventID=" . $row['eventID'] . "'><button>Register Now!</button></a>";
                    }
                    else{
                        echo "<script>alert('Please login or sign up to register for events'); window.location.href='login.php';</script>";
                    }
                }
            }
        } elseif ($action == "past") { //past event table/info is not set yet
            $sql = "SELECT e.*, p.* FROM events e, pastevents p WHERE e.eventID = $eventID AND e.eventID = p.eventID AND e.eventStatus='Past' ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $eventData = $row;
                    echo "<div class='events'>";
                    echo "<h2>Event Name: " . $eventData['eventName'] . "</h2>";
                    echo "<img src='" . $eventData['eventPic'] . "' alt='" . $eventData['eventPic'] . "' width='300' height='200'>";
                    echo "<div class='event-highlight'>";
                    echo "<p><strong>Details:</strong> " . $eventData['details'] . "</p>";
                    echo "<p><strong>Start Date & Time:</strong> " . $eventData['start_dateTime'] . "</p>";
                    echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $eventData['location'] . "</p>";
                    echo "</div>";
                    echo "<div class='event-highlight'>";
                    echo "<p><strong>Attendees:</strong>" . $eventData['attendees'] . "</p>";
                    echo "<p><strong>Impact and Outcomes:</strong>" . $eventData['impact'] . "</p>";
                    echo "</div>";
                    echo "<p><strong>Photo Gallery:</strong></p><img src='" . $eventData['photoGallery'] . "' alt='" . $eventData['eventName'] . "' width='300' height='200'>";
                    echo "</div>";

//                    if ($memberID){
//                        echo "<a href='eventRegistrations.php?eventID=" . $row['eventID'] . "'><button>Register Now!</button></a>";
//                    }
//                    else{
//                        echo "<script>alert('Please login or sign up to register for events'); window.location.href='login.php')</script>";
//                    }
                }
            }
        }else{
            echo "No upcoming or past events.";
        }
    }
    ?>
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
