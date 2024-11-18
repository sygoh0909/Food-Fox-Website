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
    <title>Edit/Delete Registration Page</title>

    <style>

    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $registrationID = isset($_GET['registrationID']) ? $_GET['registrationID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $registrationInfo = null;

    if ($registrationID){
        $sql = "SELECT * FROM registrations WHERE registrationID = $registrationID";
        $result = mysqli_query($conn, $sql);
        $registrationInfo = mysqli_fetch_assoc($result);

        if ($action == "edit"){
            $sql = "SELECT * FROM registrations WHERE registrationID = $registrationID";
            $result = mysqli_query($conn, $sql);
            $registrationInfo = mysqli_fetch_assoc($result);
        }
        elseif ($action == "delete"){
            $sql = "DELETE FROM registrations WHERE registrationID = $registrationID"; //press delete only delete lahhh
            if ($conn->query($sql) === TRUE) {
                echo "Registration deleted successfully";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $registerType = $_POST["registrations"];
            $dietaryRestrictions = $_POST["dietaryRestrictions"];

            $errors = [];

            if (empty($errors)){
                if ($action == "edit"){
                    //participant/volunteer info some not same, what if wan to change register type hmm
                }
            }

            }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">

    </form>
</main>
</body>
</html>

