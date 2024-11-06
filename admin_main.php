<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <style>
        body{
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar{
            padding: 15px 20px;
            background-color: #5C4033;
        }
        .main-links{
            display: flex;
            justify-content: center;
            gap: 40px;
        }
        .main-links a{
            text-decoration: none;
            color: white;
        }
        .admin-dashboard{

        }
        .stats{
            background-color: #C5B4A5;
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 100px;
        }
        .stats p{
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="main-links">
                <a href="admin_main.php">Home</a>
                <a href="admin_members.php">Members</a>
                <a href="admin_events.php">Events</a>
                <a href="admin_donations.php">Donation</a>
            </div>
        </div>
    </nav>
</header>
<main>
    <section class="admin-dashboard">
        <h2>Admin Dashboard Overiew</h2>
        <div class="stats">
            <p>Total Members <br>
                <?php
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT COUNT(*) AS total_members FROM members";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo $row["total_members"];
                }
                else{
                    echo "0";
                }
                ?>
            </p>
            <p>
                Total Events <br>
                <?php
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    echo "Connection failed: " . $conn->connect_error;
                }
                $sql = "SELECT COUNT(*) AS total_events FROM events";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo $row["total_events"];
                }
                else{
                    echo "0";
                }
                ?>
            </p>
            <p>
                Total Registrations/Participants/Volunteers?
            </p>
            <p>
                Total Donations
            </p>
        </div>
    </section>
</main>
</body>
</html>
