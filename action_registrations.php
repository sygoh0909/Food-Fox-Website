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
        $sql = "SELECT r.*, m.email, m.phoneNum FROM registrations r, members m WHERE r.memberID = m.memberID AND registrationID = $registrationID";
        $result = mysqli_query($conn, $sql);
        $registrationInfo = mysqli_fetch_assoc($result);

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            //details like member email edit through another page not here
            $eventName = $_POST['eventName'];
            $registerType = $_POST['registerType'];
            $dietaryRestrictions = $_POST['dietaryRestrictions'];
            $sizes = $_POST['sizes'];
            $specialAccommodation = $_POST['specialAccommodation'];
            $skills = $_POST['skills'];
        }

        $errors = [];

        if (empty($errors)){
            if ($action == "edit"){
                $sql = "UPDATE registrations SET eventName = '$eventName', registerType = '$registerType', dietaryRestrictions = '$dietaryRestrictions', sizes = '$sizes', specialAccommodation = '$specialAccommodation', skills = '$skills' WHERE registrationID = $registrationID'";
                $result = mysqli_query($conn, $sql);
                if ($conn->query($sql) === TRUE){
                    echo "<script>alert('Registration updated successfully!'); window.location.href='admin_registrations.php';</script>";
                }
            }
            elseif ($action == "delete"){
                $sql = "DELETE FROM registrations WHERE registrationID = $registrationID";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Registration Deleted!'); window.location.href='admin_registrations.php';</script>";
                }
            }
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="events">Choose an event: </label>
        <select name="events" id="events">
            <?php
            $sql = "SELECT eventName FROM events";
            $result = mysqli_query($conn, $sql);
            while ($event = mysqli_fetch_assoc($result)) {
                $selectedEvent = ($event['eventName'] == $registrationInfo["eventName"]) ? "selected" : "";
                echo "<option value='$event[eventName]' $selectedEvent>$event[eventName]</option>";
            }
            ?>
        </select>

        <p>Email:</p>
        <?php echo $registrationInfo['email']; ?>

        <p>Phone Number:</p>
        <?php echo $registrationInfo['phoneNum']; ?>

        <p>Dietary Restrictions:</p>
        <label><input type="text" name="dietaryRestrictions" value="<?php echo isset ($registrationInfo['dietaryRestrictions']) ? $registrationInfo['dietaryRestrictions'] : ''; ?>"></label>

        <label for="registrations">Choose a register type: </label>
        <select name="registrations" id="registrations" onchange="showFields()">
            <option>Participant <?php echo $registrationInfo['registerType'] == 'Participant'?></option>
            <option>Volunteer <?php echo $registrationInfo['registerType'] == 'Volunteer'?></option>
        </select>

        <div class="participant-field">
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

        <div class="volunteer-field">
            <p>Please note that you should be free the whole day as volunteer. </p>

            <p>Relevant skills:</p>
            <label><input type="text" name="skills" value="<?php echo isset ($registrationInfo['skills']) ? $registrationInfo['skills']:'';?>"></label>
        </div>

        <button type="submit"><?php echo $registrationID && $action=='edit'?'Update member info': 'Delete Member Info'?></button>
        <a href="admin_registrations.php"><button>Cancel</button></a>
    </form>
</main>
</body>
<script>
    function showFields(){
        const registrationType = document.getElementById("registrations").value;
        const participantField = document.querySelector(".participant-field");
        const volunteerField = document.querySelector(".volunteer-field");

        if (registrationType == "Participant"){
            participantField.style.display = "block";
            volunteerField.style.display = "none";
        }
        else if (registrationType == "Volunteer"){
            participantField.style.display = "none";
            volunteerField.style.display = "block";
        }
        else{
            participantField.style.display = "none";
            volunteerField.style.display = "none";
        }

    }
</script>
</html>

