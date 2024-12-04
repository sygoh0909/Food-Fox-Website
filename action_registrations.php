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
        $sql = "SELECT r.*, m.memberName, m.email, m.phoneNum, e.eventName, p.*, v.* FROM registrations r JOIN members m ON r.memberID = m.memberID JOIN events e ON r.eventID = e.eventID LEFT JOIN participants p ON r.registrationID = p.registrationID LEFT JOIN volunteers v ON r.registrationID = v.registrationID WHERE r.registrationID = $registrationID";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $registrationInfo = $result->fetch_assoc();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            //details like member email edit through another page not here
//            $eventName = $_POST['events'];
            $dietaryRestrictions = $_POST['dietaryRestrictions'];
            $registerType = $_POST['registerType'];

        $errors = [];

        if (empty($errors)){
            if ($action == "edit"){
                //what if user wan change from participant to volunteer/change other event? ask them resubmit form instead?
                $sql = "UPDATE registrations SET dietaryRestrictions = '$dietaryRestrictions' WHERE registrationID = $registrationID";

                if ($conn->query($sql) === TRUE){
                        if ($registrationInfo['registerType'] == "Participant"){
                            $sizes = $_POST['sizes'];
                            $specialAccommodation = $_POST['specialAccommodation'];

//                            if (!in_array($sizes, ['XS', 'S', 'M', 'L', 'XL'])) {
//                                $errors['sizes'] = "Invalid T-Shirt size selected";
//                            }

                            $sql = "UPDATE participants SET specialAccommodation = '$specialAccommodation', shirtSize = '$sizes' WHERE registrationID = $registrationID";
                        }
                        elseif ($registrationInfo['registerType'] == "Volunteer"){
                            $skills = $_POST['skills'];
                            $sql = "UPDATE volunteers SET relevantSkills = '$skills' WHERE registrationID = $registrationID";
                        }
                        else{
                            //error
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
//        foreach ($errors as $error) {
//            echo "<p style='color:red;'>$error</p>";
//        }
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
            <label><input type="text" name="specialAccommodation" value="<?php echo $registrationInfo['specialAccommodation'] ?? '';?>" ></label>

            <p>T-Shirt Size</p>
            <label for="sizes"></label>
            <select name="sizes" id="sizes">
                <?php
                $sizes = ['XS', 'S', 'M', 'L', 'XL'];
                $selectedSize = $registrationInfo['shirtSize'] ?? '';

                foreach ($sizes as $size) {
                    $selected = ($size == $selectedSize) ? "selected" : "";
                    echo "<option value='$size' $selected>$size</option>";
                }
                ?>
            </select>
        </div>

        <div class="volunteer-field" style="display: <?php echo $registrationInfo['registerType'] == "Volunteer" ? "block" : "none"; ?>;">
            <!--<p>Please note that you should be free the whole day as volunteer. </p>-->

            <p>Relevant skills:</p>
            <label><input type="text" name="skills" value="<?php echo $registrationInfo['relevantSkills'] ?? '';?>"></label>
        </div>

        <button type="button" onclick="displayActionPopup()"><?php echo $registrationID && $action=='edit'?'Update Registration info': 'Delete Registration Info';?></button>
        <?php echo "<a href='admin_registrations.php?eventID=" .$registrationInfo['eventID']."'><button type='button'>Cancel</button></a>"?>

        <div id="action-popup" class="action-popup" style="display:none;">
            <h2><?php echo $registrationID && $action=='edit'?'Confirm to update registration info?': 'Confirm to delete registration info?';?></h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </div>
    </form>

</main>
</body>
<script src="main.js"></script>
</html>

