<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Event Info Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>

        .main {
            color: white;
        }

        .events {
            display: flex;
            flex-wrap: wrap;
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
        }

        .events-left {
            flex: 1;
            max-width: 40%;
            margin: 10px;
            padding: 20px;
            text-align: center;
            background: linear-gradient(135deg, #f9e4bd, #f8f5f2);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .events-left h2 {
            color: #4a4a4a;
            margin-bottom: 20px;
            font-size: 36px;
            font-weight: bold;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .events-left img {
            width: 90%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .events-left p {
            font-size: 16px;
            color: #4a4a4a;
            margin-bottom: 10px;
            line-height: 1.8;
        }

        .events-right {
            flex: 2;
            max-width: 60%;
            margin: 10px;
            padding: 20px;
        }

        .event-highlight, .event-section {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #d3a029;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .event-section h3 {
            margin-top: 0;
            font-size: 20px;
            color: #4a4a4a;
        }

        .register-btn {
            background-color: #d3a029;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .register-btn:hover {
            background-color: #7F6C54;
        }

        .dropdown-btn {
            background-color: #d3a029;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 10px;
            width: fit-content;
            transition: background-color 0.3s ease;
        }

        .dropdown-btn:hover {
            background-color: #7F6C54;
        }

        .schedule-list {
            display: none;
            padding-left: 0;
            list-style-type: none;
        }

        .schedule-list li {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f5f2;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .schedule-list li:hover {
            background-color: #ecdcc6;
            color: white;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .schedule-time {
            font-weight: bold;
            color: #4a4a4a;
            flex: 1;
        }

        .schedule-description {
            color: #333333;
            flex: 2;
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

        .guest-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .guest-image img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .guest-details {
            margin-left: 15px;
        }

        .photo-gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .gallery-item img {
            width: 150px;
            height: auto;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.1);
        }

        .full-view {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .full-image {
            display: block;
            margin: auto;
            max-width: 80%;
            max-height: 80%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
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

    if ($eventID) {
        $sql = "SELECT e.* FROM events e WHERE e.eventID = $eventID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $eventData = $result->fetch_assoc();

            $startDateTime = new DateTime($eventData['start_dateTime']);
            $endDateTime = new DateTime($eventData['end_dateTime']);

            $startFormatted = $startDateTime->format('d F y, H:i');
            $endFormatted = $endDateTime->format('d F y, H:i');

            //events info
            echo "<div class='events'>";
            echo "<div class='events-left'>";
            echo "<h2>" . $eventData['eventName'] . "</h2>";
            echo "<img src='" . $eventData['eventPic'] . "' alt='" . $eventData['eventPic'] . "' width='300' height='200'>";
            echo "<div class='event-highlight'>";
            echo "<p><strong>Details:</strong><br> " . $eventData['details'] . "</p>";
            echo "<p><strong>Date & Time:</strong><br> $startFormatted - $endFormatted </p>";
//            echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
            echo "<p><strong>Location:</strong><br> " . $eventData['location'] . "</p>";
            echo "</div>";
            echo "</div>";

            $sqlHighlights = "SELECT highlights FROM eventhighlights WHERE eventID = $eventID";
            $resultHighlights = $conn->query($sqlHighlights);
            echo "<div class='events-right'>";
            echo "<div class='event-section'>";
            echo "<h3>Highlights</h3>";
            if ($resultHighlights->num_rows > 0) {
                echo "<ul>";
                while ($highlight = $resultHighlights->fetch_assoc()) {
                    echo "<li>" . $highlight['highlights'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No highlights available for this event.</p>";
            }
            echo "</div>";

            $sqlSchedules = "SELECT scheduleDateTime, activityDescription FROM eventschedules WHERE eventID = $eventID";
            $resultSchedules = $conn->query($sqlSchedules);
            echo "<div class='event-section'>";
            echo "<h3>Schedules</h3>";
            if ($resultSchedules->num_rows > 0) {
                echo "<button class='dropdown-btn' id='schedule-btn' onclick='toggleSchedule()'>Show Schedules</button>";
                echo "<ul class='schedule-list' id='schedule-list'>";
                while ($schedule = $resultSchedules->fetch_assoc()) {
                    $dateTime = new DateTime($schedule['scheduleDateTime']);
                    $dateFormatted = $dateTime->format('d-m-Y, H:i');
                    echo "<li>";
                    echo "<div class='schedule-row'>";
                    echo "<span class='schedule-time'>" . $dateFormatted . "</span>";
                    echo "<span class='schedule-description'>" . $schedule['activityDescription'] . "</span>";
                    echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No schedules available for this event.</p>";
            }
            echo "</div>";

            $sqlGuests = "SELECT guestName, guestProfilePic, guestBio FROM eventguests WHERE eventID = $eventID";
            $resultGuests = $conn->query($sqlGuests);
            echo "<div class='event-section'>";
            echo "<h3>Guests</h3>";
            if ($resultGuests->num_rows > 0) {
                while ($guest = $resultGuests->fetch_assoc()) {
                    echo "<div class='guest-container'>";
                    echo "<div class='guest-image'>";
                    echo "<img src='" . $guest['guestProfilePic'] . "' alt='Guest Profile' />";
                    echo "</div>";
                    echo "<div class='guest-details'>";
                    echo "<p class='guest-name'>" . $guest['guestName'] . "</p>";
                    echo "<p class='guest-bio'>" . $guest['guestBio'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No guests available for this event.</p>";
            }
            echo "</div>";

            if ($action == "upcoming") {
                echo "<div class='event-highlight'>";
                echo "<p><strong>Participants Needed:</strong> " . $eventData['participantsNeeded'] . "</p>";
                echo "<p><strong>Volunteers Needed:</strong> " . $eventData['volunteersNeeded'] . "</p>";
                echo "</div>";
                echo "<p class='note'><strong>Note:</strong> Participants are those who will attend the event, while volunteers are individuals who help with event operations.</p>";
//                echo "</div>";

                $participantsNeeded = $eventData['participantsNeeded'];
                $volunteersNeeded = $eventData['volunteersNeeded'];

                if (!empty($participantsNeeded) || !empty($volunteersNeeded)) {
                    $sqlParticipant = "SELECT COUNT(*) AS total_participants FROM participants p, registrations r, events e WHERE p.registrationID = r.registrationID AND r.eventID = e.eventID AND e.eventID = $eventID";
                    $resultParticipant = $conn->query($sqlParticipant);
                    $registeredParticipant = 0;
                    if ($resultParticipant && $resultParticipant->num_rows > 0) {
                        $registeredParticipant = $resultParticipant->fetch_assoc()['total_participants'];
                    }

                    $sqlVolunteer = "SELECT COUNT(*) AS total_volunteers FROM volunteers v, registrations r, events e WHERE v.registrationID = r.registrationID AND r.eventID = e.eventID AND e.eventID = $eventID";
                    $resultVolunteer = $conn->query($sqlVolunteer);
                    $registeredVolunteer = 0;
                    if ($resultVolunteer && $resultVolunteer->num_rows > 0) {
                        $registeredVolunteer = $resultVolunteer->fetch_assoc()['total_volunteers'];
                    }

                    if (($registeredParticipant >= $participantsNeeded) && ($registeredVolunteer >= $volunteersNeeded)) {
                        echo "<button class='register-btn'>Full</button>";
                    }
                    else{
                        if (isset($_SESSION['memberID'])){
                            echo "<a href='eventRegistrations.php?eventID=" . $eventID . "'><button class='register-btn'>Register Now!</button></a>";
                        }
                        else{
                            echo "<a href='login.php' onclick='return confirm(\"Please login or sign up to register for events\");'><button type='button' class='register-btn'>Register Now!</button></a>";
                        }
                    }
                }
            }
            elseif ($action == "past") {
                $sql = "SELECT p.*, COUNT(r.attendance) AS attendees FROM pastevents p LEFT JOIN registrations r ON r.eventID = p.eventID WHERE p.eventID = $eventID GROUP BY p.eventID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $pastEventData = $result->fetch_assoc();

                    echo "<div class='event-highlight'>";
                    echo "<p><strong>Attendees:</strong> " . $pastEventData['attendees'] . "</p>";
                    echo "<p><strong>Impact and Outcomes:</strong> " . $pastEventData['impact'] . "</p>";
                    echo "</div>";
//                    echo "<p><strong>Photo Gallery:</strong></p><img src='" . $pastEventData['photoGallery'] . "' alt='" . $pastEventData['photoGallery'] . "' width='300' height='200'>";

                    $sqlGallery = "SELECT * FROM photogallery WHERE eventID = $eventID";
                    $resultGallery = $conn->query($sqlGallery);
                    echo "<div class='photo-gallery-container'>";
                    echo "<h3>Photo Gallery</h3>";
                    if ($resultGallery->num_rows > 0) {
                        while ($row = $resultGallery->fetch_assoc()) {
                            echo "<div class='gallery-item'>";
                            echo "<img src='" . $row['imagePath'] . "' alt='Photo Gallery' onclick='openView(this.src)'>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No photos available for this event.</p>";
                    }
                    echo "</div>";
                }
            }else{
                echo "No upcoming or past events.";
            }
            echo "</div>";
            echo "</div>";
        }
    }
    ?>
    <div id="imageFullView" class="full-view">
        <span class="close" onclick="closeView()">&times;</span>
        <img class="full-image" id="fullImage">
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
<script>
    function toggleSchedule(){
        var btnText =  document.getElementById('schedule-btn');
        var scheduleList = document.getElementById('schedule-list')

        if (scheduleList.style.display === 'none' || scheduleList.style.display === ''){
            scheduleList.style.display = 'block';
            btnText.innerHTML = "Hide Schedules";
        }
        else{
            scheduleList.style.display = 'none';
            btnText.innerHTML = "Show Schedules";
        }
    }

    function openView(src){
        const fullView = document.getElementById('imageFullView');
        const fullImage = document.getElementById('fullImage');
        fullImage.src = src;
        fullView.style.display = 'block';
    }

    function closeView(){
        const fullView = document.getElementById('imageFullView');
        fullView.style.display = 'none';
    }
</script>
</body>
</html>
