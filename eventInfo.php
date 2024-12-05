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
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
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
            font-size: 28px;
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

        .event-highlight {
            background-color: #f8f5f2;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #d3a029;
        }

        .event-highlight p {
            margin: 0;
            font-size: 16px;
            color: #4a4a4a;
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

        .note {
            font-size: 14px;
            color: #5C4033;
            margin-top: 10px;
            text-align: center;
        }

        .note strong {
            color: #C5B4A5;
        }

        .photo-gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 20px 0;
        }

        .gallery-item {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
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
        $sql = "SELECT e.*, g.*, h.*, s.* FROM events e, eventguests g, eventhighlights h, eventschedules s WHERE e.eventID = $eventID AND  e.eventID = g.eventID AND e.eventID = h.eventID AND e.eventID = s.eventID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $eventData = $result->fetch_assoc();

            $startDateTime = new DateTime($eventData['start_dateTime']);
            $endDateTime = new DateTime($eventData['end_dateTime']);

            $startFormatted = $startDateTime->format('d F y, H:i');
            $endFormatted = $endDateTime->format('d F y, H:i');

            //events info
            echo "<div class='events'>";
            echo "<h2>Event Name: " . $eventData['eventName'] . "</h2>";
            echo "<img src='" . $eventData['eventPic'] . "' alt='" . $eventData['eventPic'] . "' width='300' height='200'>";
            echo "<div class='event-highlight'>";
            echo "<p><strong>Details:</strong> " . $eventData['details'] . "</p>";
            echo "<p><strong>Date & Time:</strong> $startFormatted - $endFormatted </p>";
//            echo "<p><strong>End Date & Time:</strong> " . $eventData['end_dateTime'] . "</p>";
            echo "<p><strong>Location:</strong> " . $eventData['location'] . "</p>";
            echo "</div>";

            $sqlHighlights = "SELECT highlights FROM eventhighlights WHERE eventID = $eventID";
            $resultHighlights = $conn->query($sqlHighlights);
            if ($resultHighlights->num_rows > 0) {
                echo "<div class='event-section'>";
                echo "<h3>Highlights</h3>";
                echo "<ul>";
                while ($highlight = $resultHighlights->fetch_assoc()) {
                    echo "<li>" . $highlight['highlights'] . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }

            $sqlSchedules = "SELECT scheduleDateTime, activityDescription FROM eventschedules WHERE eventID = $eventID";
            $resultSchedules = $conn->query($sqlSchedules);
            if ($resultSchedules->num_rows > 0) {
                echo "<div class='event-section'>";
                echo "<h3>Schedules</h3>";
                echo "<button class='dropdown-btn' id='schedule-btn' onclick='toggleSchedule()'>Show Schedules</button>";
                echo "<ul class='schedule-list' id='schedule-list'>";
                while ($schedule = $resultSchedules->fetch_assoc()) {
                    echo "<li>";
                    echo "<div class='schedule-row'>";
                    echo "<span class='schedule-time'>" . $schedule['scheduleDateTime'] . "</span>";
                    echo "<span class='schedule-description'>" . $schedule['activityDescription'] . "</span>";
                    echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }

            $sqlGuests = "SELECT guestName, guestProfilePic, guestBio FROM eventguests WHERE eventID = $eventID";
            $resultGuests = $conn->query($sqlGuests);
            if ($resultGuests->num_rows > 0) {
                echo "<div class='event-section'>";
                echo "<h3>Guests</h3>";
                while ($guest = $resultGuests->fetch_assoc()) {
                    echo "<p>" . $guest['guestProfilePic'] . $guest['guestName'] . "<br>" . $guest['guestBio'] . "</p>";
                }
                echo "</div>";
            }

            if ($action == "upcoming") {
                echo "<div class='event-highlight'>";
                echo "<p><strong>Participants Needed:</strong> " . $eventData['participantsNeeded'] . "</p>";
                echo "<p><strong>Volunteers Needed:</strong> " . $eventData['volunteersNeeded'] . "</p>";
                echo "</div>";
                echo "<p class='note'><strong>Note:</strong> Participants are those who will attend the event, while volunteers are individuals who help with event operations.</p>";
                echo "</div>";

                $participantsNeeded = $eventData['participantsNeeded'];
                $volunteersNeeded = $eventData['volunteersNeeded'];

                if ($result->num_rows > 0) {
                    $limit = $result->fetch_assoc();
                    $participantsNeeded = $limit['participantsNeeded'];
                    $volunteersNeeded = $limit['volunteersNeeded'];
                }

                if (!empty($participantsNeeded) || !empty($volunteersNeeded)) {
                    $sqlParticipant = "SELECT COUNT(*) AS total_participants FROM participants p, registrations r, events e WHERE p.registrationID = r.registrationID AND r.eventID = e.eventID AND e.eventID = $eventID";
                    $resultParticipant = $conn->query($sqlParticipant);
                    $registeredParticipant = $resultParticipant->fetch_assoc()['total_participants'];

                    $sqlVolunteer = "SELECT COUNT(*) AS total_volunteers FROM volunteers v, registrations r, events e WHERE v.registrationID = r.registrationID AND r.eventID = e.eventID AND e.eventID = $eventID";
                    $resultVolunteer = $conn->query($sqlVolunteer);
                    $registeredVolunteer = $resultVolunteer->fetch_assoc()['total_volunteers'];

                    if (($registeredParticipant >= $participantsNeeded) && ($registeredVolunteer >= $volunteersNeeded)) {
                        echo "<button>Full</button>";
                    }
                    else{
                        if (isset($_SESSION['memberID'])){
                            echo "<a href='eventRegistrations.php?eventID=" . $eventID . "'><button>Register Now!</button></a>";
                        }
                        else{
                            echo "<a href='login.php' onclick='return confirm(\"Please login or sign up to register for events\");'><button type='button'>Register Now!</button></a>";
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
                    if ($resultGallery->num_rows > 0){
                        echo "<div class='photo-gallery-container'>";
                        echo "<h3>Photo Gallery</h3>";
                        while ($row = $resultGallery->fetch_assoc()){
                            echo "<div class='gallery-item'</div>";
                            echo "<img src='" . $row['imagePath'] ."'alt='Photo Gallery'>";
                            echo "</div>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    //no photos
                }
            }else{
                echo "No upcoming or past events.";
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
</script>
</body>
</html>
