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
            position: relative;
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

        .btn{
            background-color: #BDB7AA;
            color: #333333;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.8em;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #A89E92;
        }

        .btn.save, .cancel{
            display: inline-block;
            background-color: #7F6C54;
            color: #fff;
            padding: 10px 15px;
            font-size: 1em;
            position: relative;
        }

        .btn.save, .cancel:hover{
            background-color: #6B5A48;
        }

        .btn.logout {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: #7F6C54;
            color: #fff;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1em;
        }

        .btn.logout:hover {
            background-color: #6B5A48;
        }

        .profile-pic {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #666;
            margin-bottom: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        .profile-pic .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 0.5em;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
            text-transform: uppercase;
            font-weight: bold;
        }

        .profile-pic:hover img {
            opacity: 0.7;
        }

        .profile-pic:hover .hover-overlay {
            opacity: 1;
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

        .error-message{
            color: red;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : '';
    $memberData = null;

    $passwordFlag = isset($_POST['changePasswordFlag']) ? $_POST['changePasswordFlag'] : false;

    $sql = "SELECT * FROM members WHERE memberID = $memberID";
    $result = mysqli_query($conn, $sql);
    $memberData = mysqli_fetch_assoc($result);

    $changePassword = false;
    $passwordChangeAttempt = false;

    if ($memberID){
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])){
            $memberName = isset($_POST['memberName']) ? $_POST['memberName'] : $memberData['memberName']; // Default to existing data
            $email = isset($_POST['email']) ? $_POST['email'] : $memberData['email'];
            $phoneNum = isset($_POST['phoneNum']) ? $_POST['phoneNum'] : $memberData['phoneNum'];
            $bio = isset($_POST['bio']) ? $_POST['bio'] : $memberData['bio'];
//            $password = $_POST['password'];

            $memberProfilePath = '';

            if (isset($_FILES['memberProfile']) && $_FILES['memberProfile']['error'] == 0) {
                $target_dir = "uploads/";
                $memberProfilePath = $target_dir . basename($_FILES["memberProfile"]["name"]);
                move_uploaded_file($_FILES["memberProfile"]["tmp_name"], $memberProfilePath);
                //validation for images 
            }

            $errors = array();
            $passwordError = array();

            if (empty($memberName)) {
                $errors['memberName'] = "Name is required";
            }
            elseif (!preg_match('/^[a-zA-Z]+$/', $memberName)) {
                $errors['memberName'] = "Name can contain only letters and spaces";
            }
            if (empty($email)) {
                $errors['email'] = "Email is required";
            }
            elseif (!preg_match('/^[\w\-\.]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/', $email)) {
                $errors['email'] = "Enter a valid email address.";
            }
            if (!preg_match('/^\+?[0-9]{1,4}?\s?(\(?[0-9]{3}\)?[\s.-]?)?[0-9]{3}[\s.-]?[0-9]{4}$/', $phoneNum)) {
                $errors['phoneNum'] = "Enter a valid phone number.";
            }


            if ($passwordFlag === 'true'){ //if the change password button is pressed and the fields displayed

                $currentPassword = $_POST['currentPassword'];
                $newPassword = $_POST['newPassword'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($currentPassword == ''){
                    $passwordChangeAttempt = true;
                    $passwordError['currentPassword'] = "Current password is required";
                }
                elseif (!password_verify($currentPassword, $memberData['password'])){
                    $passwordChangeAttempt = true;
                    $passwordError['currentPassword'] = "Current password is different";
                }
                if ($newPassword == ''){
                    $passwordChangeAttempt = true;
                    $passwordError['newPassword'] = "New password is required";
                }
                elseif (!preg_match('/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)){
                    $passwordChangeAttempt = true;
                    $passwordError['newPassword'] = "Password must at least be 8 characters long, with at least one letter and one number.";
                }
                if ($newPassword != $confirmPassword){
                    $passwordChangeAttempt = true;
                    $passwordError['confirmPassword'] = "Passwords do not match";
                }
            }

            if (empty($errors) && empty($passwordError)){
                if (!$passwordFlag){
                    $hashedPassword = $memberData['password'];
                    }
                else{
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    }

                $sql = "UPDATE members SET memberProfile = '$memberProfilePath', memberName = '$memberName', email = '$email', phoneNum = '$phoneNum', bio = '$bio', password = '$hashedPassword' WHERE memberID = $memberID";
                if ($conn->query($sql) === TRUE) {
                    $passwordChangeAttempt = false;
                    echo "<script>alert('Profile Updated Successfully'); window.location.href = window.location.href</script>";
                }
                else{
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
    ?>

    <form method="post" enctype="multipart/form-data" id="profileForm">
        <div class="container">
            <!-- Left Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-pic">
                    <label for="uploadPic">
                        <img src="<?php echo ($memberData['memberProfile'])?>" id="profileImg">
                        <div class="hover-overlay">Edit Picture</div>
                    </label>
                    <input type="file" name='memberProfile' id="uploadPic" disabled style="display: none;" accept="image/*" onchange="previewProfilePic()">
                </div>

                <div class="profile-info">
                    <p>Member ID: <?php echo ($memberData['memberID'])?></p>
                    <p>Member since: <?php echo date("d F Y", strtotime($memberData['joinDate']));?></p>
                </div>
                <button type='button' class="btn" id='editProfileBtn'>Edit Profile</button> <!--if press this user only can edit their info-->
                <?php
                echo "<form action='' method='POST'>
            <button type='submit' class='btn logout' name='logout'>Log Out</button>
        </form>";

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])){
                    session_unset();
                    session_destroy();
                    echo "<script>alert('Logged out Successfully'); window.location.href='mainpage.php';</script>";
                    exit();
                }
                ?>
            </div>

            <!-- Right Profile Details -->
            <div class="profile-details">
                <div class="form-section">
                    <h3>Profile Information</h3>

                    <!--right side-->
                    <p>Member Name:</p>
                    <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>" disabled></label>
                    <p class="error-message"><?= isset($errors['memberName']) ? $errors['memberName'] : '' ?></p>

                    <p>Email:</p>
                    <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>" disabled></label>
                    <p class="error-message"><?= isset($errors['email']) ? $errors['email'] : '' ?></p>

                    <p>Phone Number:</p>
                    <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>" disabled></label>
                    <p class="error-message"><?= isset ($errors['phoneNum']) ? $errors['phoneNum'] :'';?></p>

                    <p>Bio:</p>
                    <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>" disabled></label>

                    <p>Password:</p>
                    <?php
                    //                $changePassword = false;
                    echo str_repeat('*', 8);?>

                    <input type="hidden" id="changePasswordFlag" name="changePasswordFlag" value="false">
                    <button type="button" id="changePasswordBtn" class="btn" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>;">Change Password</button>
                    <!--if change password button is pressed, show some fields for user to enter their password now and a new password?-->

                    <div class="change-password-field" id="change-password-field" style="display: <?= $passwordChangeAttempt ? 'block' : 'none'; ?>;">
                        <label><input type="text" name="currentPassword" placeholder="Enter your current password..."></label>
                        <p class="error-message"><?= isset($passwordError['currentPassword']) ? $passwordError['currentPassword'] : '';?></p>

                        <label><input type="text" name="newPassword" placeholder="Enter your new password..."></label>
                        <p class="error-message"><?= isset($passwordError['newPassword']) ? $passwordError['newPassword'] : '';?></p>

                        <label><input type="text" name="confirmPassword" placeholder="Confirm your new password..."></label>
                        <p class="error-message"><?= isset($passwordError['confirmPassword']) ? $passwordError['confirmPassword'] : '';?></p>
                    </div>

                    <div class="save">
                        <br>
                        <button type="submit" name="saveChanges" id="saveChangesBtn" class="btn save" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>;">Save Changes</button>
                        <button type="button" id="cancel-btn" class="btn cancel" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>">Cancel</button>
                    </div>
                </div>
    </form>

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
        <button type="button" class="btn">Check out more!</button>
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
        document.getElementById('cancel-btn').style.display = 'inline-block'

        this.style.display = 'none';
    });

    document.getElementById('cancel-btn').addEventListener('click', function (){
        document.querySelectorAll('#profileForm input').forEach(input => input.disabled = true);
        document.getElementById('saveChangesBtn').style.display = 'none';
        document.getElementById('changePasswordBtn').style.display = 'none';
        document.getElementById('editProfileBtn').style.display = 'inline-block';
        document.getElementById('change-password-field').style.display = 'none';

        this.style.display = 'none';
    })

    document.getElementById('changePasswordBtn').addEventListener('click', function (){
        const field = document.getElementById("change-password-field");
        field.style.display = field.style.display === "block" ? "none" : "block";
        document.getElementById("changePasswordFlag").value = "true";

        this.style.display = 'none';
    })
</script>

</html>
