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
        $sql = "SELECT r.*, m.memberName, m.email, m.phoneNum, e.eventName FROM registrations r, members m, events e WHERE r.eventID = e.eventID AND r.memberID = m.memberID AND registrationID = $registrationID";
        $result = mysqli_query($conn, $sql);
        $registrationInfo = mysqli_fetch_assoc($result);

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            //details like member email edit through another page not here
//            $eventName = $_POST['events'];
            $dietaryRestrictions = $_POST['dietaryRestrictions'];
            $sizes = $_POST['sizes'];
            $specialAccommodation = $_POST['specialAccommodation'];
            $skills = $_POST['skills'];

        $errors = [];

        if (empty($errors)){
            if ($action == "edit"){
                //what if user wan change from participant to volunteer/change other event? ask them resubmit form instead?
                $sql = "UPDATE registrations SET dietaryRestrictions = '$dietaryRestrictions' WHERE registrationID = $registrationID";

                if ($conn->query($sql) === TRUE){
                        if ($registrationInfo['registerType'] == "Participant"){
                            $sql = "UPDATE participants SET specialAccommodation = '$specialAccommodation', shirtSize = '$sizes' WHERE registrationID = $registrationID";
                        }
                        elseif ($registrationInfo['registerType'] == "Volunteer"){
                            $sql = "UPDATE volunteers SET relevantSkills = '$skills' WHERE registrationID = $registrationID";
                        }
                        else{
                            //error?
                        }
                        if ($conn->query($sql) === TRUE){
                            echo "<script>alert('Registration updated successfully!'); window.location.href='admin_registrations.php?eventID=".$registrationInfo['eventID']."';</script>"; //jump back but with blank?
                        }
                    }
            }
            elseif ($action == "delete"){
                $sql = "DELETE FROM registrations WHERE registrationID = $registrationID";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Registration Deleted!'); window.location.href='admin_registrations.php?eventID=".$registrationInfo['eventID']."';</script>";
                }
            }
        }
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="events">Event Name: </label>
        <?php echo $registrationInfo['eventName']?>

        <p>Member Name: </p>
        <?php echo $registrationInfo['memberName']?>

        <p>Email:</p>
        <?php echo $registrationInfo['email']; ?>

        <p>Phone Number:</p>
        <?php echo $registrationInfo['phoneNum']; ?>

        <p>Dietary Restrictions:</p>
        <label><input type="text" name="dietaryRestrictions" value="<?php echo isset ($registrationInfo['dietaryRestrictions']) ? $registrationInfo['dietaryRestrictions'] : ''; ?>"></label>

        <label for="registrations">Register type: </label>
        <?php echo $registrationInfo['registerType']; ?>
        <input type="hidden" name="registrations" value="<?php echo $registrationInfo['registerType']; ?>">

        <div class="participant-field" style="display: <?php echo $registrationInfo['registerType'] == "Participant" ? "block" : "none"; ?>;">
            <p>Special Accommodation:</p>
            <label><input type="text" name="specialAccommodation" value="<?php echo isset ($registrationInfo['specialAccommodation']) ? $registrationInfo['specialAccommodation'] :'';?>" ></label>

            <p>T-Shirt Size</p>
            <label for="sizes">Choose a T-Shirt Size:</label>
            <select name="sizes" id="sizes">
                <?php
                $sizes = ['XS', 'S', 'M', 'L', 'XL'];
                foreach ($sizes as $size){
                    $selectedSize = ($size == $registrationInfo['sizes']) ? "selected" : "";
                    echo "<option value='$size' $selectedSize>$size</option>";
                }
                ?>
            </select>
            <!--provide t-shirt size chart also-->
        </div>

        <div class="volunteer-field" style="display: <?php echo $registrationInfo['registerType'] == "Volunteer" ? "block" : "none"; ?>;">
            <p>Please note that you should be free the whole day as volunteer. </p>

            <p>Relevant skills:</p>
            <label><input type="text" name="skills" value="<?php echo isset ($registrationInfo['skills']) ? $registrationInfo['skills']:'';?>"></label>
        </div>

        <button type="submit"><?php echo $registrationID && $action=='edit'?'Update Registration info': 'Delete Registration Info';?></button>
        <a href="admin_registrations.php"><button type="button">Cancel</button></a>
    </form>
</main>
</body>
</html>

