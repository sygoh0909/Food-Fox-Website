<?php
include ('cookie.php');
$visitCount = cookie();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Event Info Page</title>
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
        .events{
            text-align: center;
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
                <a href="volunteers.php" class="roundButton main">Volunteers</a>
                <a href="donations.php" class="roundButton main">Donation</a>
            </div>

            <div class="nav-links">
                <?php
                if (isset($_SESSION['memberID'])) {
                    $memberID = $_SESSION['memberID'];
                    echo "<a href='profile.php?id=$memberID'>Member ID: $memberID</a>";
                    echo "<p>Welcome back! This is your visit number $visitCount.</p>"; //testing
                } else {
                    echo "<a href='login.php' class='roundButton login'>Login</a>";
                    echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
                    echo "<p>This is your visit number $visitCount.</p>";
                }
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "foodfoxdb";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
    $eventData = null;

    if ($eventID){
        $sql = "SELECT * FROM `events` WHERE `eventID` = $eventID";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eventData = $row;
                echo "<div class='events'>";
                echo "Event Name: $eventData[eventName]";
                echo "</div>";
                echo "<a href='eventRegistrations.php?eventID=" .$row['eventID']."'><button>Register Now!</button></a>";
                //check user is member or not, if not display a message and jump to sign up page
            }
        }
    }

    ?>
    <!--display all info related to the event-->
</main>
</body>
</html>