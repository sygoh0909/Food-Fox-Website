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
    <title>Add/Edit/Delete Event Page</title>

    <style>
        input[type="time"] , [type="date"]{
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            width: 120px;
            transition: border-color 0.3s ease;
        }
        .image {
            width: 120px;
            height: 100px;
            object-fit: cover;
            border: 3px solid #C5B4A5;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .event-image {
            width: 140px;
            height: 120px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #666;
            margin-bottom: 20px;
            object-fit: cover;
        }
        .dynamic-inputs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .dynamic-inputs label {
            flex: 1;
            margin-right: 10px;
        }

        .dynamic-inputs button {
            margin-left: 5px;
        }

        #guest-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .guestPic img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }


    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    //check for event id presence
    $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $eventData = null;

    if ($eventID) {
        if ($action == "edit" || $action == "delete"){
            $sql = "SELECT * FROM events WHERE eventID = '$eventID'";
            $result = $conn->query($sql);
            $eventData = $result->fetch_assoc(); //retrieves the data as an associative array
        }
        elseif ($action == "editPast" || $action == "deletePast"){
            $sql = "SELECT e.*, p.* FROM events e, pastevents p WHERE e.eventID = p.eventID AND e.eventID = '$eventID'";
            $result = $conn->query($sql);
            $eventData = $result->fetch_assoc();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //basic info for events
        $eventName = $_POST['eventName'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $location = $_POST['location'];
        $details = $_POST['details'];
        $participantsNeeded = $_POST['participantsNeeded'];
        $volunteersNeeded  = $_POST['volunteersNeeded'];
        $eventStatus = $_POST['eventStatus'];
        $highlights = $_POST['highlights'];
        $schedules = $_POST['schedules'];
        $guestName = $_POST['guestName'];
        $guestBio = $_POST['guestBio'];

        $startDateTime = $startDate . " " . $startTime;
        $endDateTime = $endDate . " " . $endTime;

        $eventImagePath = $eventData['eventPic'] ?? '';
        $guestImagePath = $eventData['guestProfilePic'] ?? '';
        $photoGalleryPath = $eventData['photoGallery'] ?? '';

        $errors = [];

        //validation
        if (empty($eventName)) {
            $errors['eventName'] = "Event Name is required";
        }
//        elseif (!preg_match("/^[a-zA-Z\s]+$/", $eventName)){
//            $errors['eventName'] = "Event name should only contain alphabets and spaces.";
//        }
        if (empty($startDate)) {
            $errors['startDate'] = "Start Date is required";
        }
        if (empty($endDate)) {
            $errors['endDate'] = "End Date is required";
        }
        if (empty($startTime)) {
            $errors['startTime'] = "Start Time is required";
        }
        if (empty($endTime)) {
            $errors['endTime'] = "End Time is required";
        }
        if (empty($location)) {
            $errors['location'] = "Location is required";
        }
        elseif (!preg_match("/^[a-zA-Z0-9\s,.\-\/]+$/", $location)) {
            $errors['location'] = "Location should only contain letters, numbers, spaces, commas, periods, hyphens, and slashes.";
        }
        if (!empty ($participantsNeeded) && !preg_match('/^[1-9]\d*$/', $participantsNeeded)){
            $errors['participantsNeeded'] = "Participants needed must be a positive integer number.";
        }
        if (empty($volunteersNeeded) && !preg_match('/^[1-9]\d*$/', $volunteersNeeded)){
            $errors['volunteersNeeded'] = "Volunteers needed must be a positive integer number.";
        }
        if (empty($eventStatus)) {
            $errors['eventStatus'] = "Event Status is required";
        }
        elseif (!in_array($eventStatus, ['Upcoming', 'Past', 'Canceled'])) {
            $errors['eventStatus'] = "Event status must be either 'Upcoming' or 'Past' or 'Canceled'.";
        }

        //handle image upload
        if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] == 0) {
            $target_dir = "uploads/";
            $eventImagePath = $target_dir . basename($_FILES["eventImage"]["name"]);
            move_uploaded_file($_FILES["eventImage"]["tmp_name"], $eventImagePath);
        }

        if (isset($_FILES['guestImage']) && $_FILES['guestImage']['error'] == 0) {
            $target_dir = "uploads/";
            $guestImagePath = $target_dir . basename($_FILES["guestImage"]["name"]);
            move_uploaded_file($_FILES["guestImage"]["tmp_name"], $guestImagePath);
        }

        if (isset($_FILES['photoGallery']) && $_FILES['photoGallery']['error'] == 0) {
            $target_dir = "uploads/";
            $galleryPath = $target_dir . basename($_FILES["photoGallery"]["name"]);
            move_uploaded_file($_FILES["photoGallery"]["tmp_name"], $galleryPath);
        }

        //action
        if ($action=="editPast"){
//            $attendees = $_POST['attendees'];
            $impact = $_POST['impact'];

//            if (!preg_match("/^\d+$/", $attendees)) {
//                $errors[] = "Attendees must be a positive number.";
//            }
        }

        //update event
        if (empty($errors)){
            if ($action=="edit" || $action=="editPast"){

                $updateQuery = "UPDATE events SET eventName = '$eventName', start_dateTime = '$startDateTime', end_dateTime = '$endDateTime', location = '$location', details = '$details', participantsNeeded = '$participantsNeeded', volunteersNeeded = '$volunteersNeeded', eventStatus = '$eventStatus', eventPic = '$eventImagePath' WHERE eventID = '$eventID'";

                if ($conn->query($updateQuery) === TRUE) {
                    $deleteScheduleQuery = "DELETE FROM eventschedules WHERE eventID = '$eventID'";
                    $conn->query($deleteScheduleQuery);

                    foreach ($schedules as $schedule) {
                        $scheduleUpdate = "INSERT INTO eventschedules(eventID, scheduleDateTime, activityDescription)".
                            " VALUES ('$eventID', '$schedule', '$schedule')";
                        $conn->query($scheduleUpdate);
                    }

                    $deleteHighlightQuery = "DELETE FROM eventhighlights WHERE eventID = '$eventID'";
                    $conn->query($deleteHighlightQuery);

                    foreach ($highlights as $highlight) {
                        $highlightUpdate = "INSERT INTO eventhighlights(eventID, highlights)".
                            " VALUES ('$eventID', '$highlight')";
                        $conn->query($highlightUpdate);
                    }

                    $deleteGuestQuery = "DELETE FROM eventguests WHERE eventID = '$eventID'";
                    $conn->query($deleteGuestQuery);

                    foreach ($guestName as $name) {
                        foreach ($guestBio as $bio) {
                            $guestUpdate = "INSERT INTO eventguests(eventID, guestName, guestBio, guestProfilePic)".
                                " VALUES ('$eventID', '$name', '$bio', '$guestImagePath')";
                            $conn->query($guestUpdate);
                        }
                    }

                    if ($eventStatus == "Past"){
                        $checkQuery = "SELECT * FROM pastevents WHERE eventID = $eventID";
                        $checkResult = $conn->query($checkQuery);

                        if ($checkResult->num_rows==0){
                            $sql = "INSERT INTO pastevents(eventID) VALUES($eventID)";;
                            if ($conn->query($sql) === TRUE) {
//                                echo "Event successfully moved to past events. Please proceed to past events tables for detailed update.";
                            }
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
//                        echo "Event is already marked as past.";
                    }

                    if ($action=="editPast"){
                        $sql = "UPDATE pastevents SET eventID = '$eventID', impact = '$impact', photoGallery = '$photoGalleryPath' WHERE eventID = '$eventID'";
                    }
                    echo "<script>alert('Event Updated'); window.location.href='admin_events.php';</script>";
                }
            }
            elseif ($action=="delete" || $action=="deletePast"){
                $sql = "DELETE FROM events WHERE eventID = '$eventID'";
                if ($action=="deletePast"){
                    $sql = "DELETE FROM pastevents WHERE eventID = '$eventID'";
                }
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Event Deleted'); window.location.href='admin_events.php';</script>";
                }
            }
            //add new event
            elseif ($action == "add"){
//                if (empty($errors)){
                    $query = "INSERT INTO events (eventName, start_dateTime, end_dateTime, location, details, participantsNeeded, volunteersNeeded, eventStatus, eventPic) VALUES ('$eventName', '$startDateTime', '$endDateTime', '$location', '$details', '$participantsNeeded', '$volunteersNeeded', '$eventStatus', '$eventImagePath')";

                    if ($conn->query($query) === TRUE) {
                        $eventID = $conn->insert_id;

                        foreach ($schedules as $schedule) { //foreach used for arrays, means loop through the array
                            $scheduleQuery = "INSERT INTO eventschedules (eventID, scheduleDateTime, activityDescription)"
                                . "VALUES ('$eventID', '$schedule', '$schedule')";
                            $conn->query($scheduleQuery);
                        }

                        foreach ($highlights as $highlight) {
                            $highlightQuery = "INSERT INTO eventhighlights (eventID, highlights)"
                                . "VALUES ('$eventID', '$highlight')";
                            $conn->query($highlightQuery);
                        }

                        //event guest
                        foreach ($guestName as $name){
                            foreach ($guestBio as $bio){
                                $guestQuery = "INSERT INTO eventguests (eventID, guestName, guestBio, guestProfilePic)".
                                    "VALUES ('$eventID', '$name', '$bio', '$guestImagePath')";
                                $conn->query($guestQuery);
                            }
                        }
                    }
                    echo "<script>alert('New Event Added'); window.location.href='admin_events.php';</script>";
//                }
            }
        }
//        foreach ($errors as $error) {
//            echo "<p style='color:red;'>$error</p>";
//        }
            }

        if ($action == "edit" || $action == "editPast"){
            echo "<h2>Update Event</h2>";
        }
        elseif ($action == "delete" || $action == "deletePast"){
            echo "<h2>Delete Event</h2>";
        }
        elseif ($action == "add"){
            //add event
            echo "<h2>Add New Event</h2>";
        }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <p>Event Image:</p>
        <div class="event-image">
            <img src="<?php echo ($eventData['eventPic'])?>" alt="Event Image" id="eventImg" class="image">
        </div>
        <input type="file" name="eventImage" id="uploadPic" accept="image/*" onchange="previewEventImage()">

        <p>Event Name:</p>
        <label><input type="text" name="eventName" value="<?php echo isset($eventData['eventName']) ? $eventData['eventName'] : ''; ?>" placeholder="Enter event name..."></label>
        <p class="error-message"><?= isset($errors['eventName']) ? $errors['eventName'] : '' ?></p>

        <p>Event Date:</p>
        <label>Start Date: <input type="date" name="startDate" value="<?php echo isset($eventData['start_dateTime']) ? substr($eventData['start_dateTime'], 0,10) : '';?>"></label>
        <p class="error-message""><?= isset($errors['startDate']) ? $errors['startDate'] : '' ?></p>
        <label>End Date: <input type="date" name="endDate" value="<?php echo isset ($eventData['end_dateTime']) ? substr($eventData['end_dateTime'], 0, 10): '';?>"</label>
        <p class="error-message""><?= isset($errors['endDate']) ? $errors['endDate'] : '' ?></p>

        <p>Event Time:</p>
        <label>Start Time: <input type="time" name="startTime" value="<?php echo isset($eventData['start_dateTime']) ? substr($eventData['start_dateTime'], 11, 5): '';?>"</label>
        <p class="error-message"><?= isset($errors['startTime']) ? $errors['startTime'] : '' ?></p>
        <label>End Time: <input type="time" name="endTime" value="<?php echo isset($eventData['end_dateTime']) ? substr($eventData['end_dateTime'], 11, 5): '';?>"></label>
        <p class="error-message"><?= isset($errors['endTime']) ? $errors['endTime'] : '' ?></p>

        <p>Event Location:</p>
        <label><input type="text" name="location" value="<?php echo isset ($eventData['location']) ? $eventData['location']:'';?>" placeholder="Enter event location..."></label>
        <p class="error-message"><?= isset($errors['location']) ? $errors['location'] : '' ?></p>

        <p>Event Details:</p>
        <label><input type="text" name="details" value="<?php echo isset ($eventData['details']) ? $eventData['details']: '';?>" placeholder="Enter brief event details..."></label>

        <p>Event Highlights:</p>
        <div id="highlights-container">
            <?php
            if ($eventID){
                $highlightQuery = "SELECT * FROM eventhighlights WHERE eventId = '$eventID'";
                $result = $conn->query($highlightQuery);
                while ($highlight = $result->fetch_assoc()){
                    echo "<div class='dynamic-inputs'><label><input type='text' name='highlights[]' value='{$highlight['highlights']}' placeholder='Enter event highlights...'></label><button type='button' onclick='removeRow(this)'>-</button></div>";
                }
            }
            else{
                echo "<div class='dynamic-inputs'><label><input type='text' name='highlights[]' placeholder='Enter event highlights...'></label><button type='button' onclick='removeRow(this)'>-</button></div>";
            }
            ?>
            <button type="button" id="add-highlight-button" onclick="addHighlights()">+</button>
        </div>

        <p>Event Schedule:</p>
        <div id="schedule-container">
            <?php
            if ($eventID){
                $scheduleQuery = "SELECT * FROM eventschedules WHERE eventID = '$eventID'";
                $result = $conn->query($scheduleQuery);
                while ($schedule = $result->fetch_assoc()){
                    //display schedule time date and activity
                    echo "<div class='dynamic-inputs'><label><input type='text' name='schedules[]' value='{$schedule['scheduleDateTime']} - {$schedule['activityDescription']}'' placeholder='Enter event schedule...'></label><button type='button' onclick='removeRow(this)'>-</button></div>";
                }
            }
            else{
                echo "<div class='dynamic-inputs'><label><input type='text' name='schedules[]' placeholder='Enter event schedule...'></label><button type='button' onclick='removeRow(this)'>-</button></div>";
            }
            ?>
            <button type="button" id="add-schedule-button" onclick="addSchedule()">+</button>
        </div>

        <p>Featured Speaker/Event Guests:</p>
        <div id="guest-container">
            <?php
            if ($eventID) {
                $guestQuery = "SELECT * FROM eventguests WHERE eventID = '$eventID'";
                $result = $conn->query($guestQuery);

                while ($guestList = $result->fetch_assoc()) {
                    echo "<div class='dynamic-inputs'>";
                    echo "<div class='guestPic'>";
                    echo "<img src='{$guestList['guestProfilePic']}' alt='Guest Picture' class='roundImage'>";
                    echo "</div>";
                    echo "<label>";
                    echo "<input type='file' accept='image/*' onchange='previewGuestImage(this)'>";
                    echo "<input type='text' name='guestName[]' value='{$guestList['guestName']}' placeholder='Enter guest's name...'>";
                    echo "<input type='text' name='guestBio[]' value='{$guestList['guestBio']}' placeholder='Enter guest's bio...'>";
                    echo "</label>";
                    echo "<button type='button' onclick='removeRow(this)'>-</button>";
                    echo "</div>";
                }
            } else {
                echo "<div class='dynamic-inputs'>";
                echo "<div class='guestPic'><img src='' alt='Guest Picture' class='roundImage'></div>";
                echo "<label>";
                echo "<input type='file' accept='image/*' onchange='previewGuestImage(this)'>";
                echo "<input type='text' name='guestName[]' placeholder='Enter guest's name...'>";
                echo "<input type='text' name='guestBio[]' placeholder='Enter guest's bio...'>";
                echo "</label>";
                echo "<button type='button' onclick='removeRow(this)'>-</button>";
                echo "</div>";
            }
            ?>
            <button type="button" id="add-guest-button" onclick="addGuest()">+</button>
        </div>


        <p>Participants Needed:</p>
        <label><input type="text" name="participantsNeeded" value="<?php echo isset ($eventData['participantsNeeded']) ? $eventData['participantsNeeded']: ''; ?>" placeholder="Enter Participants needed..."></label>
        <p class="error-message"><?= isset($errors['participantsNeeded']) ? $errors['participantsNeeded'] : '' ?></p>

        <p>Volunteers Needed:</p>
        <label><input type="text" name="volunteersNeeded" value="<?php echo isset ($eventData['volunteersNeeded']) ? $eventData['volunteersNeeded']: ''; ?>" placeholder="Enter Volunteers needed..."></label>
        <p class="error-message"><?= isset($errors['volunteersNeeded']) ? $errors['volunteersNeeded'] : '' ?></p>

        <p>Event Status:</p>
        <label><input type="text" name="eventStatus" value="<?php echo isset ($eventData['eventStatus']) ? $eventData['eventStatus']: '';?>" placeholder="Enter Event Type..."></label>
        <p class="error-message"><?= isset($errors['eventStatus']) ? $errors['eventStatus'] : '' ?></p>

        <?php if ($action == "editPast" || $action == "deletePast"){?>
        <p>Attendees:</p>
            <?php
            $sql = "SELECT COUNT(r.attendance) AS attendees FROM registrations r JOIN events e ON r.eventID = e.eventID WHERE r.eventID = {$eventID} AND r.attendance = 1";
            $result = $conn->query($sql);
            $attendeesCount = 0;
            if ($result->num_rows > 0) {
                $row  = $result->fetch_assoc();
                $attendeesCount = $row['attendees'];
            }
            ?>
            <label><input type="text" name="attendees" value="<?= $attendeesCount ?>" disabled</label>

        <p>Impact and Outcomes:</p>
        <label><input type="text" name="impact" value="<?php echo isset ($eventData['impact']) ? $eventData['impact']:'';?>"</label>

        <p>Photo Gallery:</p>
        <label><input type="file" name="photoGallery" accept="image/*" onchange='previewEventImage()'> <!--show the image saved in database-->
            <img id="photoGallery" class="photo-gallery" alt="Photo Gallery" style="display: none">
        </label>

        <?php } ?>

        <div class="button">
            <?php
            $buttonText = '';

            if ($eventID && ($action == "edit" || $action == "editPast")){
                $buttonText = "Update Event";
            }
            elseif ($eventID && ($action == "delete" || $action == "deletePast")){
                $buttonText = "Delete Event";
            }
            else{ //actually should set action for add
                $buttonText = "Add Event";
            }
            echo "<button type='button' onclick='displayActionPopup()'>{$buttonText}</button>";
            ?>
            <a href="admin_events.php"><button type="button">Cancel</button></a>
        </div>


        <div id="action-popup" class="action-popup" style="display:none;">
            <h2><?php
                $buttonText = '';

                if ($eventID && ($action == "edit" || $action == "editPast")){
                    $buttonText = "Confirm to update event info?";
                }
                elseif ($eventID && ($action == "delete" || $action == "deletePast")){
                    $buttonText = "Confirm to delete event info?";
                }
                else{ //actually should set action for add
                    $buttonText = "Confirm to add event?";
                }
                echo "{$buttonText}";
                ?>
            </h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </div>
    </form>

</main>
<script src="main.js"></script>
<script>
    function previewEventImage(){
        const fileInput = document.getElementById('uploadPic');
        const eventImg = document.getElementById('eventImg');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                eventImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function previewGuestImage(){
        const fileInput = document.getElementById('uploadGuestPic');
        const guestPic = document.getElementById('guestPic');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e){
                guestPic.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    //preview for event photo gallery pics

    function addHighlights() {
        const container = document.getElementById("highlights-container");
        const newHighlight = document.createElement("div");
        newHighlight.className = "dynamic-inputs";
        newHighlight.innerHTML = `
        <label><input type="text" name="highlights[]" placeholder="Enter event highlights..."></label>
        <button type="button" onclick="removeRow(this)">-</button>
    `;
        const addButton = document.getElementById('add-highlight-button');
        container.insertBefore(newHighlight, addButton);
    }

    function addSchedule() {
        const container = document.getElementById("schedule-container");
        const newSchedule = document.createElement("div");
        newSchedule.className = "dynamic-inputs";
        newSchedule.innerHTML = `
        <label><input type="text" name="schedules[]" placeholder="Enter event schedule..."></label>
        <button type="button" onclick="removeRow(this)">-</button>
    `;
        const addButton = document.getElementById('add-schedule-button');
        container.insertBefore(newSchedule, addButton);
    }

    function addGuest() {
        const container = document.getElementById("guest-container");

        // Create a new input block for the guest
        const newGuest = document.createElement("div");
        newGuest.className = "dynamic-inputs";

        newGuest.innerHTML = `
        <div class="guestPic">
            <img src="" alt="Guest Picture" class="roundImage">
        </div>
        <label>
            <input type="file" accept="image/*" onchange="previewGuestImage(this)">
            <input type="text" name="guestName[]" placeholder="Enter guest's name...">
            <input type="text" name="guestBio[]" placeholder="Enter guest's bio...">
        </label>
        <button type="button" onclick="removeRow(this)">-</button>
    `;

        const addButton = document.getElementById("add-guest-button");
        container.insertBefore(newGuest, addButton);
    }

    function removeRow(button) {
        const row = button.parentElement;
        row.remove();
    }

</script>
</body>
</html>