<?php
include("cookie.php");
cookie();

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "foodfoxdb";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}
$eventID = isset($_GET['eventID']) ? $_GET['eventID'] : '';

?>
