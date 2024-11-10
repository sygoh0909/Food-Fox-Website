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
            $sql = "SELECT * FROM members WHERE memberID = $memberID";
            $result = $conn->query($sql);
            $memberData = $result->fetch_assoc();

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $memberName = $_POST["memberName"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $phoneNum = $_POST["phoneNumber"];
                $bio = $_POST["bio"];

                //member profile

                //validation
                $errors = [];

                //update event
                if (empty($errors)){
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE members SET memberName = '$memberName', email = '$email', password = '$hashedPassword', phoneNum = '$phoneNum', bio = '$bio' WHERE memberID = $memberID";

                    if ($conn->query($sql) === TRUE) {
                        echo "Updated successfully";
                    }
                }

            }
        }
    }
    elseif ($action == "delete"){
        echo "<h2>Delete Member Info</h2>";
    }
    elseif ($action == "view"){
        echo "<h2>View Member Info</h2>";
    }
    ?>
</main>
</body>
</html>
