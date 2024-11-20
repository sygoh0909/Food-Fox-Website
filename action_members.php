<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
    <title>Edit/Delete Member Page</title>

    <style>
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
            $memberName = $_POST["memberName"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $phoneNum = $_POST["phoneNum"];
            $bio = $_POST["bio"];
            $memberProfilePath = '';

            if (isset($_FILES['memberProfile']) && $_FILES['memberProfile']['error'] == 0) {
                $target_dir = "uploads/";
                $memberProfilePath = $target_dir . basename($_FILES["memberProfile"]["name"]);
                move_uploaded_file($_FILES["memberProfile"]["tmp_name"], $memberProfilePath);
            }

            //regular expressions
            $namePattern = '/^[a-zA-Z]+$/';
            $emailPattern = '/^[\w\-\.]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/';
            $passwordPattern = '/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/'; //password format maybe need change

            //validation
            $errors = [];

            //need to include if edit only need check these errors???
            if (empty($memberName)) {
                $errors[] = "Member Name is required";
            }
            elseif (!preg_match($namePattern, $memberName)) {
                $errors[] = "Name can contain only letters and spaces";
            }
            if (empty($email)) {
                $errors[] = "Email is required";
            }
            elseif (!preg_match($emailPattern, $email)) {
                $errors[] = "Enter a valid email address.";
            }

            if (empty($errors)){
                //update event
                if ($action == "edit"){
                    if ($password == ''){
                        $sql = "UPDATE members SET memberName = '$memberName', email = '$email', phoneNum = '$phoneNum', bio = '$bio', memberProfile = '$memberProfilePath' WHERE memberID = $memberID";
                    }
                    else{
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        if (!preg_match($passwordPattern, $password)) {
                            $errors[] = "Password must at least be 8 characters long, with at least one letter and one number.";
                        }
                        $sql = "UPDATE members SET memberName = '$memberName', email = '$email', password = '$hashedPassword', phoneNum = '$phoneNum', bio = '$bio', memberProfile = '$memberProfilePath' WHERE memberID = $memberID";
                    }

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Member Info Updated Successfully');
                        window.location.href='admin_members.php';</script>";
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
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            } //change design for this maybe - show below each field? or just show on top idk
            }

        if ($action == "edit"){
            echo "<h2>Edit Member Info</h2>";
        }

        elseif ($action == "delete"){
            echo "<h2>Delete Member Info</h2>";
        }
    }
    else{
        //error message
    }
    ?>

    <form method="POST" enctype="multipart/form-data">

        <p>Member ID:</p> <!--should display member id?-->
        <?php echo $memberData["memberID"]; ?>

        <p>Member Profile:</p>
        <input type="file" name="memberProfile" accept="image/*" onchange="previewMemberProfile(event)">
        <img id="memberProfilePreview" alt="Member Profile Preview" style="display: none;">

        <p>Member Name:</p>
        <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>"></label>

        <p>Email:</p>
        <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>"></label>

        <p>Password:</p>
        <label><input type="text" name="password" placeholder="Enter a new password if you want to change it..."></label>
        <p>Leave blank to keep the existing password.</p>

        <p>Phone Number:</p>
        <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>

        <p>Bio:</p>
        <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>"></label>

        <p>Join Date:</p>
        <?php echo $memberData['joinDate'];?> <br>

        <button type="submit"><?php echo $memberID && $action=='edit'?'Update member info': 'Delete Member Info'?></button>
        <a href="admin_members.php"><button>Cancel</button></a>

    </form>
</main>
</body>
<script>
    function previewMemberProfile(event) {
        const memberProfilePreview = document.getElementById('memberProfilePreview');
        const file = event.target.files[0];

        if (file) {
            memberProfilePreview.src = URL.createObjectURL(file);
            memberProfilePreview.style.display = 'block';
        } else {
            memberProfilePreview.style.display = 'none';
        }
    }

</script>
</html>
