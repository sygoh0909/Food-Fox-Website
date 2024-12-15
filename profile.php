<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <style>
        .container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-sidebar {
            position: relative;
            flex: 0 0 25%;
            background-color: #F7F7F7;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .profile-sidebar .profile-pic {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            background-color: #E0E0E0;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-sidebar .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-sidebar .profile-pic:hover {
            transform: scale(1.05);
        }

        .profile-sidebar .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            font-size: 0.8em;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-sidebar .profile-pic:hover .hover-overlay {
            opacity: 1;
        }

        .profile-info {
            text-align: center;
            font-size: 0.9em;
        }

        .profile-info p {
            margin: 5px 0;
        }

        .profile-sidebar .btn-container {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .profile-sidebar button {
            margin-top: 10px;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn.save, .btn.logout {
            background-color: #7F6C54;
            color: #FFFFFF;
        }

        .btn.delete {
            background-color: #E57373;
            color: #FFFFFF;
            font-size: 0.9em;
        }

        .btn.delete:hover {
            background-color: #D32F2F;
        }

        .change-password-field input{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #A89E92;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .profile-details {
            flex: 1;
            padding: 20px;
        }

        .profile-details .form-section h3 {
            margin-bottom: 10px;
            color: #5C4033;
        }

        .profile-details .form-section input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #A89E92;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .recent-activity {
            margin-top: 20px;
            background-color: #FFFFFF;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recent-activity h3 {
            color: #5C4033;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #E0E0E0;
            padding-bottom: 10px;
        }

        .recent-activity .activity-item {
            background-color: #F7F2E9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .recent-activity .activity-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .recent-activity .activity-item span {
            font-size: 0.9em;
            color: #5C4033;
        }

        .recent-activity .activity-item span:first-child {
            font-weight: bold;
            margin-right: 10px;
        }

        .recent-activity .activity-item span:last-child {
            font-style: italic;
            color: #8B7765;
        }

        .recent-activity .btn {
            display: inline-block;
            background-color: #7F6C54;
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .recent-activity .btn:hover {
            background-color: #6B5A48;
            transform: translateY(-3px);
        }

        .profile-sidebar .btn.back {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: transparent;
            border: none;
            color: #A89E92;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .profile-sidebar .btn.back:hover {
            color: #7F6C54;
            transform: scale(1.1);
        }

        p.error-message {
            color: red;
            font-size: 14px;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                gap: 10px;
            }

            .profile-sidebar {
                flex: none;
                width: 100%;
                text-align: center;
            }

            .profile-details {
                flex: none;
                width: 100%;
            }

            .recent-activity .activity-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .recent-activity .btn {
                margin-top: 10px;
                width: 100%;
            }
        }

        @media screen and (max-width: 1024px) {
            .container {
                max-width: 90%;
                gap: 15px;
            }

            .profile-sidebar {
                flex: 0 0 30%;
            }

            .profile-details {
                flex: 1;
            }
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $memberID = $_GET['memberID'] ?? '';
    $memberData = null;

    $changePassword = false;
    $passwordChangeAttempt = false;
    $passwordFlag = $_POST['changePasswordFlag'] ?? false;

    if ($memberID){
        $sql = "SELECT * FROM members WHERE memberID = $memberID";
        $result = mysqli_query($conn, $sql);
        $memberData = mysqli_fetch_assoc($result);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])){
            $memberName = $_POST['memberName'] ?? $memberData['memberName']; // Default to existing data
            $email = $_POST['email'] ?? $memberData['email'];
            $phoneNum = $_POST['phoneNum'] ?? $memberData['phoneNum'];
            $bio = $_POST['bio'] ?? $memberData['bio'];

            $memberProfilePath = $memberData['memberProfile'] ?? '';

            if (isset($_FILES['memberProfile']) && $_FILES['memberProfile']['error'] == 0) {
                $target_dir = "uploads/";
                $memberProfilePath = $target_dir . basename($_FILES["memberProfile"]["name"]);
                move_uploaded_file($_FILES["memberProfile"]["tmp_name"], $memberProfilePath);
            }

            $errors = array();
            $passwordError = array();

            if (empty($memberName)) {
                $errors['memberName'] = "Name is required";
            }
            else {
                $nameParts = explode(" ", $memberName);
                if (count($nameParts) < 2) {
                    $errors['memberName'] = "Please enter your full name (first and last name)";
                }
            }
            if (empty($email)) {
                $errors['email'] = "Email is required";
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Enter a valid email address.";
            }
            if (!empty($phoneNum) && !preg_match('/^\+?[0-9]{1,4}?[\s.-]?\(?[0-9]{2,5}\)?[\s.-]?[0-9]{1,5}[\s.-]?[0-9]{3,4}$/', $phoneNum)) {
                $errors['phoneNum'] = "Enter a valid phone number.";
            }


            if ($passwordFlag === 'true'){ //if the change password button is pressed and the fields displayed
                $currentPassword = $_POST['currentPassword'] ?? '';
                $newPassword = $_POST['newPassword'] ?? '';
                $confirmPassword = $_POST['confirmPassword'] ?? '';

                if (empty($currentPassword)){
                    $passwordChangeAttempt = true;
                    $passwordError['currentPassword'] = "Current password is required";
                }
                elseif (!password_verify($currentPassword, $memberData['password'])){
                    $passwordChangeAttempt = true;
                    $passwordError['currentPassword'] = "Current password is different";
                }
                if (empty($newPassword)){
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
                $hashedPassword = $passwordFlag === 'true' ? password_hash($newPassword, PASSWORD_DEFAULT) : $memberData['password'];

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
            <div class="profile-sidebar">
                <div class="profile-pic">
                    <label for="uploadPic">
                        <img src="<?php echo ($memberData['memberProfile'])?>" id="profileImg" alt="Profile Picture">
                        <div class="hover-overlay">Edit Picture</div>
                    </label>
                    <input type="file" name='memberProfile' id="uploadPic" disabled style="display: none;" accept="image/*" onchange="previewProfilePic()">
                </div>

                <div class="profile-info">
                    <p>Member ID: <?php echo ($memberData['memberID'])?></p>
                    <p>Member since: <?php echo date("d F Y", strtotime($memberData['joinDate']));?></p>
                </div>
                <button type='button' class="btn" id='editProfileBtn'>Edit Profile</button> <!--if press this user only can edit their info-->

                <p>Password:</p>
                <?php
                echo str_repeat('*', 8);?>

                <input type="hidden" id="changePasswordFlag" name="changePasswordFlag" value="false">
                <button type="button" id="changePasswordBtn" class="btn" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>;">Change Password</button>
                <!--if change password button is pressed, show some fields for user to enter their password now and a new password?-->

                <div class="change-password-field" id="change-password-field" style="display: <?= $passwordChangeAttempt ? 'block' : 'none'; ?>;">
                    <label><input type="password" name="currentPassword" placeholder="Enter your current password..."></label>
                    <a href='forgotpassword.php'><p>Forgot password?</p></a>
                    <p class="error-message"><?= $passwordError['currentPassword'] ?? '';?></p>

                    <label><input type="password" name="newPassword" placeholder="Enter your new password..."></label>
                    <p class="error-message"><?= $passwordError['newPassword'] ?? '';?></p>

                    <label><input type="password" name="confirmPassword" placeholder="Confirm your new password..."></label>
                    <p class="error-message"><?= $passwordError['confirmPassword'] ?? '';?></p>
                </div>

                <div class="btn-container">
                    <?php
                    echo "<a href='mainpage.php' class='btn back' title='Go to Home'><i class='fas fa-home'></i></a></a>";
                    echo "<form action='' method='POST'><button type='submit' class='btn logout' name='logout'>Log Out</button></form>";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])){
                        session_unset();
                        session_destroy();
                        echo "<script>alert('Logged out Successfully'); window.location.href='mainpage.php';</script>";
                        exit();
                    }
                    ?>

                    <button type="button" name="deleteAcc" class="btn delete" onclick="displayActionPopup()">Delete Account</button>
                </div>

            </div>

            <div class="profile-details">
                <div class="form-section">
                    <h3>Profile Information</h3>

                    <p>Member Name:</p>
                    <label><input type="text" name="memberName" value="<?php echo $memberData['memberName'] ?? '';?>" disabled></label>
                    <p class="error-message"><?= $errors['memberName'] ?? '' ?></p>

                    <p>Email:</p>
                    <label><input type="text" name="email" value="<?php echo $memberData['email'] ?? '';?>" disabled></label>
                    <p class="error-message"><?= $errors['email'] ?? '' ?></p>

                    <p>Phone Number:</p>
                    <label><input type="text" name="phoneNum" value="<?php echo $memberData['phoneNum'] ?? '';?>" disabled></label>
                    <p class="error-message"><?= $errors['phoneNum'] ?? '';?></p>

                    <p>Bio:</p>
                    <label><input type="text" name="bio" value="<?php echo $memberData['bio'] ?? '';?>" disabled></label>

                    <div class="save">
                        <br>
                        <button type="submit" name="saveChanges" id="saveChangesBtn" class="btn save" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>;">Save Changes</button>
                        <button type="button" id="cancel-btn" class="btn cancel" style="display: <?= $passwordChangeAttempt ? 'inline-block' : 'none'; ?>">Cancel</button>
                    </div>
                </div>

                <div class="recent-activity">
                    <h3>Recent Activity</h3>
                    <?php
                    $sql = "
        (SELECT 'Event Registration' AS activityType, CONCAT(e.eventName, ' - ',  r.registerType) AS activityName, r.registrationDate AS activityDate 
         FROM events e 
         INNER JOIN registrations r ON e.eventID = r.eventID 
         WHERE r.memberID = $memberID)
        UNION
        (SELECT 'Donation' AS activityType, d.amount AS activityName, d.donationDate AS activityDate 
         FROM donations d 
         WHERE d.memberID = $memberID)
        ORDER BY activityDate DESC 
        LIMIT 3";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $activityType = $row['activityType'];
                            $activityName = $row['activityName'];
                            $activityDate = $row['activityDate'];
                            $dateFormatted = date('d-m-Y', strtotime($activityDate));

                            echo "<div class='activity-item'>";
                            echo "<span>Type: $activityType</span>";
                            echo "<span>Activity: $activityName</span>";
                            echo "<span>Date: $dateFormatted</span>";
                            echo "</div>";
                        }
                        echo "<a href='recentActivity.php?memberID=" . $memberData['memberID'] . "'><button type='button' class='btn'>Check out more!</button></a>";
                    } else {
                        echo "<p>No recent activities found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>

    <div id="action-popup" class="action-popup" style="display:none;">
        <form id="action-form" method="post" action="">
            <h2>Are you sure you want to delete your account?</h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmAction'])) {
        $sql = "DELETE FROM members WHERE memberID = $memberID";
        if ($conn->query($sql) === TRUE) {
            session_unset();
            session_destroy();
            echo "<script>alert('Account deleted successfully! Logging out...'); window.location.href='mainpage.php'</script>')";
        }
        else{
            echo "Error deleting account: " . $conn->error;
        }
    }
    ?>

</main>
</body>
<script src="main.js"></script>
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
