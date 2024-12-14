<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action for Registration Page</title>
    <link rel="stylesheet" href="form.css">

    <style>
        .participant-field,
        .volunteer-field {
            display: block;
            width: 100%;
            text-align: left;
            margin-bottom: 20px;
        }

        .participant-field input[type="text"],
        .participant-field select,
        .volunteer-field input[type="text"],
        .volunteer-field select {
            display: block;
            width: 100%;
            padding: 12px;
            border: 1px solid #A89E92;
            border-radius: 8px;
            font-size: 16px;
            background-color: #FFFFFF;
            color: #444444;
            box-sizing: border-box;
        }

        .participant-field .form-grp,
        .volunteer-field .form-grp {
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $registrationID = $_GET['registrationID'] ?? null;
    $action = $_GET['action'] ?? null;
    $registrationInfo = null;

    if ($registrationID){
        $sql = "SELECT r.*, m.memberName, m.email, m.phoneNum, e.eventName, p.*, v.* FROM registrations r JOIN members m ON r.memberID = m.memberID JOIN events e ON r.eventID = e.eventID LEFT JOIN participants p ON r.registrationID = p.registrationID LEFT JOIN volunteers v ON r.registrationID = v.registrationID WHERE r.registrationID = $registrationID";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $registrationInfo = $result->fetch_assoc();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $dietaryRestrictions = $_POST['dietaryRestrictions'];
            $registerType = $_POST['registerType'];

        $errors = array();

            if ($action == "edit"){
                if (empty($errors)){
                    $sql = "UPDATE registrations SET dietaryRestrictions = '$dietaryRestrictions' WHERE registrationID = $registrationID";

                    if ($conn->query($sql) === TRUE){
                        if ($registrationInfo['registerType'] == "Participant"){
                            $sizes = $_POST['sizes'];
                            $specialAccommodation = $_POST['specialAccommodation'];

                            $sql = "UPDATE participants SET specialAccommodation = '$specialAccommodation', shirtSize = '$sizes' WHERE registrationID = $registrationID";
                        }
                        elseif ($registrationInfo['registerType'] == "Volunteer"){
                            $skills = $_POST['skills'];
                            $sql = "UPDATE volunteers SET relevantSkills = '$skills' WHERE registrationID = $registrationID";
                        }
                        else{
                            echo "Invalid registration type";
                        }
                        if ($conn->query($sql) === TRUE){
                            echo "<script>alert('Registration updated successfully!'); window.location.href='admin_registrations.php?eventID=".$registrationInfo['eventID']."';</script>";
                        }
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
    }
    if ($registrationID && $action == "edit"){
        echo "<h2>Update registration</h2>";
    }
    elseif ($registrationID && $action == "delete"){
        echo "<h2>Delete registration</h2>";
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-grp">
            <p>Event Name: </p>
            <?php echo $registrationInfo['eventName']?>
        </div>

        <div class="form-grp">
            <p>Member Name: </p>
            <?php echo $registrationInfo['memberName']?>
        </div>

        <div class="form-grp">
            <p>Email:</p>
            <?php echo $registrationInfo['email']; ?>
        </div>

        <div class="form-grp">
            <p>Phone Number:</p>
            <?php echo $registrationInfo['phoneNum']; ?>
        </div>

        <div class="form-grp">
            <p>Dietary Restrictions:</p>
            <label><input type="text" name="dietaryRestrictions" value="<?php echo $registrationInfo['dietaryRestrictions'] ?? ''; ?>"></label>
        </div>

        <div class="form-grp">
            <p>Register type: </p>
            <?php echo $registrationInfo['registerType']; ?>
            <input type="hidden" name="registrations" value="<?php echo $registrationInfo['registerType']; ?>">
        </div>

        <div class="participant-field" style="display: <?php echo $registrationInfo['registerType'] == "Participant" ? "block" : "none"; ?>;">
            <div class="form-grp">
                <p>Special Accommodation:</p>
                <label><input type="text" name="specialAccommodation" value="<?php echo $registrationInfo['specialAccommodation'] ?? '';?>" ></label>
            </div>

            <div class="form-grp">
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
        </div>

        <div class="volunteer-field" style="display: <?php echo $registrationInfo['registerType'] == "Volunteer" ? "block" : "none"; ?>;">
            <div class="form-grp">
                <p>Relevant skills:</p>
                <label><input type="text" name="skills" value="<?php echo $registrationInfo['relevantSkills'] ?? '';?>"></label>
            </div>
        </div>

        <div>
            <button type="button" onclick="displayActionPopup()"><?php echo $registrationID && $action=='edit'?'Update Registration info': 'Delete Registration Info';?></button>
            <?php echo "<a href='admin_registrations.php?eventID=" .$registrationInfo['eventID']."'><button type='button'>Cancel</button></a>"?>
        </div>

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

