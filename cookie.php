<?php
session_start();
function cookie(){
    //check if cookie is set here
    if (isset($_COOKIE['visitCount'])) {
        //if yes, then increase 1
        $visitCount = $_COOKIE['visitCount'] + 1;
    } else {
        //if not then set initial one
        $visitCount = 1;
    }
    //set cookie
    setcookie('visitCount', $visitCount, time() + (86400 * 30), "/"); //expire in 30 days
    return $visitCount; //return the variable so that can be used by other pages
}

function loginSection(){
    $visitCount = cookie();

    if (isset($_SESSION['memberID'])) {
        $memberID = $_SESSION['memberID'];
        echo "<a href='profile.php?id=$memberID'>Member ID: $memberID</a>";
        echo "<p>Welcome back! This is your visit number $visitCount.</p>"; //testing
    } else {
        echo "<a href='login.php' class='roundButton login'>Login</a>";
        echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
        echo "<p>This is your visit number $visitCount.</p>";
    }
}

function connection(){
    /*connect to database*/
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'foodfoxdb';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
