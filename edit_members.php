<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Member Page</title>

    <style>
        body {
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
        }
        main{
            background-color: #C5B4A5;
            padding: 20px 40px;
            border-radius: 20px;
        }
        h2{
            text-align: center;
        }
    </style>
</head>
<body>
<main>
    <?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "foodfoxdb";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $memberID = isset($_GET['memberID']) ? $_GET['memberID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $memberData = null;

    if ($memberID){
        if ($action == "edit"){
            echo "<h2>Edit Member Info</h2>";

            }

        elseif ($action == "delete"){
            echo "<h2>Delete Member Info</h2>";
        }


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

                //validation
                $errors = [];

                if ($action == "edit"){
                    //update event
                    if (empty($errors)){
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //this idk
                        $sql = "UPDATE members SET memberName = '$memberName', email = '$email', password = '$hashedPassword', phoneNum = '$phoneNum', bio = '$bio', memberProfile = '$memberProfilePath' WHERE memberID = $memberID";

                        if ($conn->query($sql) === TRUE) {
                            echo "Updated successfully";
                        }
                    }
                }
                elseif ($action == "delete"){
                    if (empty($errors)){
                        $sql = "DELETE FROM members WHERE memberID = $memberID";
                        if ($conn->query($sql) === TRUE) {
                            echo "Deleted successfully";
                        }
                    }
                }
            }
    }
    else{
        //error message
    }
    ?>

    <form method="POST" enctype="multipart/form-data">

        <p>Member ID:</p> <!--edit member id because maybe member id not provided/assigned???)-->
        <?php echo $memberData["memberID"]; ?>

        <p>Member Profile:</p>
        <label><input type="file" name="memberProfile" accept="image/*" onchange='previewMemberProfile()'></label>
        <img id="memberProfilePreview" class="member-profile-preview" alt="Member Profile Preview" style="display: none">
        </label>

        <p>Member Name:</p>
        <label><input type="text" name="memberName" value="<?php echo isset($memberData['memberName']) ? $memberData['memberName']:'';?>"></label>

        <p>Email:</p>
        <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']:'';?>"></label>

        <p>Password:</p>
        <label><input type="text" name="password" ></label>

        <p>Phone Number:</p>
        <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>

        <p>Bio:</p>
        <label><input type="text" name="bio" value="<?php echo isset($memberData['bio'])?$memberData['bio']:'';?>"></label>

        <p>Join Date:</p>

        <button type="submit"><?php echo $memberID & $action=='edit'?'Update member info':'Delete Member Info'?></button>
        <a href="admin_members.php"><button>Cancel</button></a>

    </form>
</main>
</body>
<script>
    function previewMemberProfile(){
        const memberProfilePreview = document.getElementById('memberProfilePreview')
        memberProfilePreview.src = URL.createObjectURL(members.target.files[0]);
        memberProfilePreview.style.display = 'block';
    }
</script>
</html>
