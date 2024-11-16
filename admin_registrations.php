<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit/Delete Event Page</title>

    <style>
        body {
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
        }
        main{
            background-color: #C5B4A5;
            padding: 20px 40px;
            border-radius: 20px;
        }
        h2{
            text-align: center;
        }
    </style>
</head>
<body>
<main>
    <?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "foodfoxdb";
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }
    $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
    $registrationID = null;

    if ($eventID){
        $sql = "SELECT registrationID FROM registrations WHERE eventID = '$eventID'";
        $result = mysqli_query($conn, $sql);
        $registrationID = mysqli_fetch_assoc($result);
        echo "Registration ID: " . $registrationID["registrationID"] . "<br>";
    }
    ?>
</main>
</body>
</html>

