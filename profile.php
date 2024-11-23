<?php
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main{
            color: white;
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
    $memberID = $_SESSION['memberID'];
    $memberData = null;

    $sql = "SELECT m.* FROM members m WHERE memberID = $memberID";
    $result = mysqli_query($conn, $sql);
    $memberData = mysqli_fetch_assoc($result);

    if ($memberID){
        if ($_SERVER["REQUEST_METHOD"] == "POST"){

        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <!--left side-->

        <!--show profile pic as a round image and user can edit the profile pic-->

        <?php echo ($memberData['memberID'])?>
        <p>Date Joined: <?php echo $memberData['joinDate'];?></p>
        <button>Edit Profile</button>
        <button>Delete Account</button>

        <!--right side-->
        <p>Member Name:</p>
        <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>"</label>

        <p>Email:</p>
        <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>"></label>

        <p>Phone Number:</p>
        <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>

        <p>Bio:</p>
        <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>"></label>

        <p>Password:</p>
        <!--show a change ur password button beside this label -->

        <!--if edit button is pressed, show save changes button-->
    </form>
</main>
</body>
</html>

