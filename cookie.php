<link rel="stylesheet" href="main.css">
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
        $memberInfo = null;

        if ($memberID){
            $conn = connection();
            $sql = "SELECT * FROM members WHERE memberID = $memberID";
            $result = $conn->query($sql);
            $memberInfo = $result->fetch_assoc();
        }

        echo "
        <div class='profile-container'>
        <a href='#' onclick='togglePopup(event)' class='round-button member'>Member ID: $memberID</a>
        <div id='profile-popup' class='popup'>
        <p>{$memberInfo['memberProfile']}</p> <!--display profile as a circle-->
        <p>Member ID: {$memberInfo['memberID']}</p>
        <p>Member Name: {$memberInfo['memberName']}</p>
        <p>Join Date: {$memberInfo['joinDate']}</p>
        <a href='profile.php?memberID=". $memberInfo['memberID']."'><button>Check out more!</button></a>
        <p>Points: </p>
        <a href='rewards.php'><button>Rewards</button></a>
</div>
        </div>
        ";

//        echo "<p>Welcome back! This is your visit number $visitCount.</p>"; //testing
    } else {
        echo "<a href='login.php' class='roundButton login'>Login</a>";
        echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
//        echo "<p>This is your visit number $visitCount.</p>";
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
<script>
    function togglePopup(event) {
        event.preventDefault();
        const popup = document.getElementById('profile-popup');
        popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
    }
</script>
