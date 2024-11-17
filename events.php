<?php
include ('cookie.php');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #5C4033;
        }
        .social-media{
            display: flex;
            gap: 10px;
        }
        .social-media a{
            color: white;
            font-size: 20px;
            text-decoration: none;
        }
        .nav-links{
            display: flex;
            gap: 30px;
        }
        .roundButton{
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        .main{
            color: white;
        }
        .login{
            background-color: white;
            color: #d3a029;
            font-size: smaller;
        }
        .signup{
            background-color: #d3a029;
            color: white;
            font-size: smaller;
        }
        .login:hover, .signup:hover{
            transform: translateY(-2px);
        }
        .events {
            padding: 20px;
            background-color: #F5EEDC;
        }

        .events h2 {
            font-size: 2rem;
            color: #5C4033;
            text-align: center;
            margin-bottom: 30px;
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
                    echo "<h3>" . $row['eventName'] . "</h3>";
                    echo "<a href='eventInfo.php?eventID=" .$row['eventID']." &action=upcoming'><button>View more info</button></a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No upcoming events</p>";
            }
            $conn->close();
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
                    echo "<a href='eventInfo.php?eventID=" .$row['eventID']."action=past'><button>View more info</button></a>"; //upcoming and past same page different info?
                    echo "</div>";
                }
            } else {
                echo "<p>No past events</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
</main>
</body>
</html>
