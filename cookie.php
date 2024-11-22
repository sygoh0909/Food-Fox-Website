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
//    $visitCount = cookie();
    cookie();

    if (isset($_SESSION['memberID'])) {
        $memberID = $_SESSION['memberID'];
        $memberInfo = null;

        if ($memberID){
            $conn = connection();
            $sql = "SELECT * FROM members WHERE memberID = $memberID";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $memberInfo = $result->fetch_assoc();

            echo "
        <div class='profile-container'>
        <a href='#' onclick='togglePopup(event)' class='roundButton member'>Member ID: $memberID</a>
        <div id='profile-popup' class='popup'>
        <p><img src='{$memberInfo['memberProfile']}' class='roundImage'></p><!--display profile as a circle-->
        <p>Member ID: {$memberInfo['memberID']}</p>
        <p>Member Name: {$memberInfo['memberName']}</p>
        <p>Join Date: {$memberInfo['joinDate']}</p>
        <a href='profile.php?memberID=". $memberInfo['memberID']."'><button>Check out more!</button></a>
        <p>Points: </p>
        <a href='rewards.php'><button>Rewards</button></a>
        <!--log out and jump to main page with no member id-->
        <form action='' method='POST'>
            <button type='submit' name='logout'>Log Out</button>
        </form>
</div>
        </div>
        
        ";if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                    session_unset();
                    session_destroy();
                    header("Location: mainpage.php");
                    exit();
                }
            }
//            echo "<p>Welcome back! This is your visit number $visitCount.</p>"; //testing
        }
        }else {
        echo "<a href='login.php' class='roundButton login'>Login</a>";
        echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
//        echo "<p>This is your visit number $visitCount.</p>";
    }
}

function adminLoginSection(){
    cookie();

    if (isset($_SESSION['adminID'])) {
        $adminID = $_SESSION['adminID'];

        if ($adminID) {

            echo "<div class='profile-container'>
                    <p><button class='roundButton admin'>Admin ID: {$adminID}</button></p>
                    <form action='' method='post'>
                        <button type='submit' name='adminLogout'>Log Out</button>
                    </form>
                  </div>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adminLogout'])) {
                session_unset();
                session_destroy();
                header("Location: login_admin.php");
                exit();
            }
        }
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