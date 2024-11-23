<?php
include ('cookie.php')
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

        .container {
            display: flex;
            gap: 20px;
            height: 100vh;
            background-color: #f9f3e9;
            padding: 20px;
            box-sizing: border-box;
        }

        .profile-sidebar {
            flex: 0 0 30%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #666;
            margin-bottom: 20px;
        }

        .profile-info h2 {
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .profile-details {
            flex: 1;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .form-section h3 {
            margin-bottom: 10px;
        }

        .form-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-section input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .recent-activity {
            margin-top: 20px;
        }

        .recent-activity h3 {
            margin-bottom: 10px;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #e7e0d9;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : '';
    $memberData = null;

    $sql = "SELECT * FROM members WHERE memberID = $memberID";
    $result = mysqli_query($conn, $sql);
    $memberData = mysqli_fetch_assoc($result);

    if ($memberID){
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $memberName = $_POST['memberName'];
            $email = $_POST['email'];
            $phoneNum = $_POST['phoneNum'];
            $bio = $_POST['bio'];

            $memberProfilePath = '';

            if (isset($_FILES['memberProfile']) && $_FILES['memberProfile']['error'] == 0) {
                $target_dir = "uploads/";
                $memberProfilePath = $target_dir . basename($_FILES["memberProfile"]["name"]);
                move_uploaded_file($_FILES["memberProfile"]["tmp_name"], $memberProfilePath);
            }

            $errors = [];

            if (empty($memberName)) {
                $errors[] = "Name is required";
            }
            elseif (!preg_match('/^[a-zA-Z]+$/', $memberName)) {
                $errors[] = "Name can contain only letters and spaces";
            }
            if (empty($email)) {
                $errors[] = "Email is required";
            }
            elseif (!preg_match('/^[\w\-\.]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/', $email)) {
                $errors[] = "Enter a valid email address.";
            }
            if (empty($password)) {
                $errors[] = "Password is required";
            }

            if (empty($errors)){
                //update password?
                $sql = "UPDATE members SET memberProfile = $memberProfilePath, memberName = $memberName, email = $email, phoneNum = $phoneNum, bio = $bio WHERE memberID = $memberID";
                $result = mysqli_query($conn, $sql);
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Profile Updated Successfully');</script>";
                }
                else{
                    foreach ($errors as $error){
                        echo $error;
                    }
                }
            }
        }
    }
    ?>
<div class="container">
    <!-- Left Sidebar -->
    <div class="profile-sidebar">
        <div class="profile-pic">
            <img src="<?php echo ($memberData['memberProfile'])?>" alt="Profile Picture" id="profileImg" class="roundImage">
        </div>
        <button type="button" id='profile-btn' class="btn" onclick="document.getElementById('uploadPic').click()" style="display: none">Edit Profile Picture</button>
        <input type="file" id="uploadPic" style="display: none;" accept="image/*" onchange="previewProfilePic()">

        <div class="profile-info">
            <p>Member ID: <?php echo ($memberData['memberID'])?></p>
            <p>Member since: <?php echo $memberData['joinDate'];?></p>
        </div>
        <button type='button' class="btn" id='editProfileBtn'>Edit Profile</button> <!--if press this user only can edit their info-->
        <button class="btn">Log Out</button>
    </div>

    <!-- Right Profile Details -->
    <div class="profile-details">
        <div class="form-section">
            <h3>Profile Information</h3>
            <form method="post" enctype="multipart/form-data" id="profileForm">

                <!--right side-->
                <p>Member Name:</p>
                <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>" disabled></label>

                <p>Email:</p>
                <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>" disabled></label>

                <p>Phone Number:</p>
                <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>" disabled></label>

                <p>Bio:</p>
                <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>" disabled></label>

                <p>Password:</p>
                <?php echo str_repeat('*', strlen($memberData['password']));?>
                <button type="button" id="changePasswordBtn" class="btn" style="display: none">Change Password</button>
                <!--if change password button is pressed, show some fields for user to enter their password now and a new password?-->

                <div class="save">
                    <button type="submit" id="saveChangesBtn" class="btn" style="display: none;">Save Changes</button>
                </div>

            </form>
        </div>
        <div class="recent-activity">
            <h3>Recent Activity</h3>
            <!--show recent activity that member joined-->
            <?php
            $sql = "SELECT e.eventName, r.registrationDate FROM events e, registrations r WHERE r.memberID = $memberID AND e.eventID = r.eventID ORDER BY r.registrationDate DESC LIMIT 3";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventName = $row['eventName'];
                    $registrationDate = $row['registrationDate'];
                }
            }
            else {
                echo "<p>No recent registrations found.</p>";
            }

            echo "<div class='activity-item'>";
            echo "<span>$eventName</span>";
            echo "<span>$registrationDate</span>";
            echo "</div>";

            ?>

            <!--a button to check out all events they participated/registered-->
        </div>
    </div>
</div>
</main>
</body>
<script>
    function previewProfilePic() {
        const fileInput = document.getElementById('uploadPic');
        const profileImg = document.getElementById('profileImg');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('editProfileBtn').addEventListener('click', function () {

        document.querySelectorAll('#profileForm input').forEach(input => input.disabled = false);

        document.getElementById('saveChangesBtn').style.display = 'inline-block';

        document.getElementById('changePasswordBtn').style.display = 'inline-block';

        document.getElementById('profile-btn').style.display = 'inline-block';

        this.style.display = 'none';
    });
</script>

</html>
