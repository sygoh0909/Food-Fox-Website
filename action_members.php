<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="main.css">
    <title>Edit/Delete Member Page</title>

    <style>
        .note {
            font-size: 14px;
            color: #5C4033;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $memberData = null;

    if ($memberID){
        $sql = "SELECT * FROM members WHERE memberID = '$memberID'";
        $result = $conn->query($sql);
        $memberData = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //include address?
            $memberName = $_POST["memberName"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $phoneNum = $_POST["phoneNum"];
            $bio = $_POST["bio"];
            $memberProfilePath = $memberData["memberProfile"] ?? '';

            if (isset($_FILES['memberProfile']) && $_FILES['memberProfile']['error'] == 0) {
                $target_dir = "../../uploads/";
                $memberProfilePath = $target_dir . basename($_FILES["memberProfile"]["name"]);
                move_uploaded_file($_FILES["memberProfile"]["tmp_name"], $memberProfilePath);
            }

            //regular expressions
//            $namePattern = '/^[a-zA-Z]+$/';
//            $emailPattern = '/^[\w\-\.]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/';
            $passwordPattern = '/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/';
            $phonePattern = '/^\+?[0-9]{1,4}?\s?(\(?[0-9]{3}\)?[\s.-]?)?[0-9]{3}[\s.-]?[0-9]{4}$/';

            //validation
            $errors = array();
            $passwordError = array();

            //need to include if edit only need check these errors???
            if (empty($memberName)) {
                $errors['memberName'] = "Member Name is required";
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
            if (!empty($phoneNum) && !preg_match($phonePattern, $phoneNum)) {
                $errors['phoneNum'] = "Enter a valid phone number.";
            }

            if (empty($errors)){
                //update
                if ($action == "edit"){
                    if ($password == ''){
                        $sql = "UPDATE members SET memberProfile = '$memberProfilePath', memberName = '$memberName', email = '$email', phoneNum = '$phoneNum', bio = '$bio', memberProfile = '$memberProfilePath' WHERE memberID = $memberID";
                    }
                    else{
                        if (!preg_match($passwordPattern, $password)) {
                            $passwordError['password'] = "Password must at least be 8 characters long, with at least one letter and one number.";
                        }
                        if (empty($passwordError)){
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $sql = "UPDATE members SET memberProfile = '$memberProfilePath', memberName = '$memberName', email = '$email', password = '$hashedPassword', phoneNum = '$phoneNum', bio = '$bio', memberProfile = '$memberProfilePath' WHERE memberID = $memberID";
                        }
                    }

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Member Info Updated Successfully');
                        window.location.href='admin_members.php';</script>";
                        }
                    }
                }

            elseif ($action == "delete"){
                $sql = "DELETE FROM members WHERE memberID = $memberID";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Member Info Deleted Successfully');
                            window.location.href='admin_members.php';</script>";
                }
            }
        }

        if ($action == "edit"){
            echo "<h2>Edit Member Info</h2>";
        }

        elseif ($action == "delete"){
            echo "<h2>Delete Member Info</h2>";
        }
    }
    else{
        echo "Cannot find member ID.";
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-grp">
            <p>Member ID:</p> <!--should display member id?-->
            <?php echo str_repeat('*', strlen($memberID));?>
        </div>

        <div class="form-grp">
            <p>Member Profile:</p>
            <img src="<?php echo ($memberData['memberProfile']);?>" alt="Member Profile" id="memberProfile" class="roundImage">
            <input type="file" name="memberProfile" id="uploadPic" accept="image/*" onchange="previewMemberProfile()">
        </div>

        <div class="form-grp">
            <p>Member Name:</p>
            <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>"></label>
            <p class="error-message"><?= isset($errors['memberName']) ? $errors['memberName'] : '' ?></p>
        </div>

        <div class="form-grp">
            <p>Email:</p>
            <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>"></label>
            <p class="error-message"><?= isset($errors['email']) ? $errors['email'] : '' ?></p>
        </div>

        <div class="form-grp">
            <p>Password:</p>
            <label><input type="text" name="password" placeholder="Enter a new password if you want to change it..."></label>
            <p class="note">Leave blank to keep the existing password.</p>
            <p class="error-message"><?= isset($passwordError['password']) ? $passwordError['password'] : '';?></p>
        </div>

        <div class="form-grp">
            <p>Phone Number:</p>
            <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>
            <p class="error-message"><?= isset ($errors['phoneNum']) ? $errors['phoneNum'] :'';?></p>
        </div>

        <div class="form-grp">
            <p>Bio:</p>
            <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>"></label>
        </div>

        <div class="form-grp">
            <p>Join Date:</p>
            <?php echo date("d-m-Y", strtotime($memberData["joinDate"]));?>
        </div>

        <button type="button" onclick="displayActionPopup()"><?php echo $memberID && $action=='edit'?'Update member info': 'Delete Member Info'?></button>
        <a href="admin_members.php"><button type="button">Cancel</button></a>

        <div id="action-popup" class="action-popup" style="display:none;">
            <h2><?php echo $memberID && $action=='edit'?'Confirm to update member info?':'Confirm to delete member info?'?></h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </div>
    </form>

</main>
</body>
<script src="main.js"></script>
<script>
    function previewMemberProfile() {
        const fileInput = document.getElementById('uploadPic');
        const memberProfile = document.getElementById('memberProfile');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e){
                memberProfile.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

</script>
</html>
