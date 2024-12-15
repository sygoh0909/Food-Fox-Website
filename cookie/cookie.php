<?php
ob_start();
session_start();
function cookie(){

    if (isset($_COOKIE['visitCount'])) {
        //if yes, then increase 1
        $visitCount = $_COOKIE['visitCount'] + 1;
    } else {
        $visitCount = 1;
    }
    //set cookie
    setcookie('visitCount', $visitCount, time() + (86400 * 30), "/"); //set this
    return $visitCount;

}
function checkSessionTimeout($timeout_duration) {
    if (isset($_SESSION['last_activity'])) {
        $duration = time() - $_SESSION['last_activity'];
        if ($duration > $timeout_duration) {
            session_unset();
            session_destroy();
            header("Location: session_timeout.php");
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
}

function loginSection(){
    cookie();
    checkSessionTimeout(3600);

    echo '<script type="text/javascript">
            setTimeout(function(){
                location.reload();
            }, 3600000);
          </script>';

    if (isset($_SESSION['memberID'])) {
        $memberID = $_SESSION['memberID'];

        if ($memberID){
            $conn = connection();
            $sql = "SELECT * FROM members WHERE memberID = $memberID";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $memberInfo = $result->fetch_assoc();
                $dateFormatted = date('d-m-Y', strtotime($memberInfo['joinDate']));

            echo "
        <div class='profile-container'>
        <a href='#' onclick='togglePopup(event)' class='roundButton member'>Member ID: $memberID</a>
        <div id='profile-popup' class='popup'>
        <p><img src='{$memberInfo['memberProfile']}' class='roundImage'></p><!--display profile as a circle-->
        <p>Member ID: {$memberInfo['memberID']}</p>
        <p>Member Name: {$memberInfo['memberName']}</p>
        <p>Join Date: {$dateFormatted}</p>
        <div class='btn-container'>
        <a href='profile.php?memberID=". $memberInfo['memberID']."'><button>Profile</button></a>
        <p>Points: " . ($memberInfo['points'] ?? 0) . "</p>
        <a href='rewards.php'><button>Rewards</button></a>
        <!--log out and jump to main page with no member id-->
        <form action='' method='POST'>
            <button type='submit' name='logout'>Log Out</button>
        </form>
        </div>
        </div>
        </div>
        
        ";if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                    session_unset();
                    session_destroy();
                    header("Location: mainpage.php");
                    exit();
                }
            }
        }
    }else {
        echo "<a href='login.php' class='roundButton login'>Login</a>";
        echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
    }
}

function adminLoginSection(){
    cookie();
    checkSessionTimeout((3600*8)); //8 hours

    echo '<script type="text/javascript">
            setTimeout(function(){
                location.reload();
            }, 28800000); //in milliseconds
          </script>';

    if (isset($_SESSION['adminID'])) {
        $adminID = $_SESSION['adminID'];

        if ($adminID) {

            echo "<div class='profile-container'>
                    <p><button class='roundButton admin'>Admin ID: {$adminID}</button></p>
                    <form action='' method='post'>
                        <button type='submit' name='adminLogout' class='roundButton logout'>Log Out</button>
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
    //if admin not logged in, cannot access to admin page, redirect them to admin login
    if (!isset($_SESSION['adminID'])) {
        header('Location: login_admin.php');
        exit();
    }
}
?>
<script>
    function togglePopup(event) {
        event.preventDefault();
        const popup = document.getElementById('profile-popup');
        popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
    }
</script>