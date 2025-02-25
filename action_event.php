<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions for Event Page</title>
    <link rel="stylesheet" href="form.css">

    <style>
        .form-grp {
            max-width: 1000px;
        }

        input[type="datetime-local"]:focus {
            outline: none;
            border-color: #7F6C54;
            box-shadow: 0 0 5px #7F6C54;
        }

        input[type="datetime-local"]{
            padding: 12px;
            border: 1px solid #A89E92;
            border-radius: 8px;
            font-size: 16px;
            background-color: #FFFFFF;
            color: #444444;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .image, .event-image img {
            width: 160px;
            height: 130px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #C5B4A5;
            margin-bottom: 5px;
        }

        .event-image {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #666;
            margin-bottom: 20px;
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

        .dynamic-inputs input[name^="schedules[datetime]"] {
            flex: none;
            width: 200px;
            margin-right: 5px;
        }

        .dynamic-inputs input[name^="schedules[description]"] {
            flex: none;
            width: 705px;
        }

        .add-remove-btn {
            width: 50px;
            height: 35px;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: #C5B4A5;
            border: none;
            border-radius: 8px;
        }

        .add-remove-btn:hover {
            background-color: #7F6C54;
            transform: translateY(-2px);
        }

        .guestPic img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .datetime {
            display: inline-flex;
            gap: 20px;
            align-items: baseline;
        }

        .datetime label {
            font-size: 0.9em;
            white-space: nowrap;
        }

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
    //check for event id presence
    $eventID = $_GET['eventID'] ?? null;
    $action = $_GET['action'] ?? null;
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
        $startDateTime = $_POST['startDateTime'];
        $endDateTime = $_POST['endDateTime'];
        $location = $_POST['location'];
        $details = $_POST['details'];
        $participantsNeeded = $_POST['participantsNeeded'];
        $volunteersNeeded = $_POST['volunteersNeeded'];
        $eventStatus = $_POST['eventStatus'];
        $highlights = $_POST['highlights'] ?? []; // Default to an empty array if not set
        $schedules = $_POST['schedules'] ?? ['datetime' => [], 'description' => []];
        $guestName = $_POST['guestName'] ?? [];
        $guestBio = $_POST['guestBio'] ?? [];

        $eventImagePath = $eventData['eventPic'] ?? '';
        $guestImagePath = $eventData['guestProfilePic'] ?? '';
        $photoGalleryPath = $eventData['photoGallery'] ?? '';

        $errors = [];

        //validation
        if (empty($eventName)) {
            $errors['eventName'] = "Event Name is required";
        }
        if (empty($startDateTime)) {
            $errors['startDateTime'] = "Start Date and Time is required";
        }
        if (empty($endDateTime)) {
            $errors['endDateTime'] = "End Date and Time is required";
        }
        if (empty($location)) {
            $errors['location'] = "Location is required";
        }
        elseif (!preg_match("/^[a-zA-Z0-9\s,.\-\/]+$/", $location)) {
            $errors['location'] = "Location should only contain letters, numbers, spaces, commas, periods, hyphens, and slashes.";
        }
        if ($participantsNeeded === '' || !preg_match('/^(0|[1-9]\d*)$/', $participantsNeeded)) {
            $errors['participantsNeeded'] = "Participants needed must be a non-negative integer.";
        }
        if ($volunteersNeeded === '' || !preg_match('/^(0|[1-9]\d*)$/', $volunteersNeeded)) {
            $errors['participantsNeeded'] = "Participants needed must be a non-negative integer.";
        }
        if (empty($eventStatus)) {
            $errors['eventStatus'] = "Event Status is required";
        }
        elseif (!in_array($eventStatus, ['Upcoming', 'Past', 'Canceled'])) {
            $errors['eventStatus'] = "Event status must be either 'Upcoming' or 'Past' or 'Canceled'.";
        }
        elseif($action == "add" && $eventStatus != "Upcoming"){
            $errors['eventStatus'] = "Event status for new event can be only 'Upcoming'.";
        }

        //handle image upload
        if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] == 0) {
            $target_dir = "uploads/";
            $eventImagePath = $target_dir . basename($_FILES["eventImage"]["name"]);
            move_uploaded_file($_FILES["eventImage"]["tmp_name"], $eventImagePath);
        }

        $uploadedImages = [];
        $target_dir = "uploads/";
        if (isset($_FILES['guestPic']) && is_array($_FILES['guestPic']['name'])) {
            for ($i = 0; $i < count($_FILES["guestPic"]["name"]); $i++) {
                if (!empty($_FILES["guestPic"]["name"][$i]) && $_FILES["guestPic"]["error"][$i] === 0) {
                    $fileTmpName = $_FILES["guestPic"]["tmp_name"][$i];
                    $fileName = basename($_FILES["guestPic"]["name"][$i]);
                    $guestImagePath = $target_dir . $fileName;

                    if (move_uploaded_file($fileTmpName, $guestImagePath)) {
                        $uploadedImages[] = $guestImagePath;
                    } else {
                        $uploadedImages[] = '';
                    }
                } else {
                    $uploadedImages[] = $_POST['existingGuestPics'][$i] ?? '';
                }
            }
        } else {
            if (isset($_POST['existingGuestPics']) && is_array($_POST['existingGuestPics'])) {
                $uploadedImages = $_POST['existingGuestPics'];
            }
        }

        if (isset($_FILES['photoGallery'])) {
            $target_dir = "uploads/";
            $uploadedFiles = [];

            for ($i = 0; $i < count($_FILES["photoGallery"]["name"]); $i++) {
                if ($_FILES["photoGallery"]["error"][$i] == 0) {
                    $fileTmpName = $_FILES["photoGallery"]["tmp_name"][$i];
                    $fileName = basename($_FILES["photoGallery"]["name"][$i]);
                    $photoGalleryPath = $target_dir . $fileName;

                    if (move_uploaded_file($fileTmpName, $photoGalleryPath)) {
                        $uploadedFiles[] = $photoGalleryPath;
                    }
                }
            }
        }

        //update event
        if (empty($errors)){
            if ($action=="edit" || $action=="editPast"){

                $updateQuery = "UPDATE events SET eventName = '$eventName', start_dateTime = '$startDateTime', end_dateTime = '$endDateTime', location = '$location', details = '$details', participantsNeeded = '$participantsNeeded', volunteersNeeded = '$volunteersNeeded', eventStatus = '$eventStatus', eventPic = '$eventImagePath' WHERE eventID = '$eventID'";

                if ($conn->query($updateQuery) === TRUE) {
                    $deleteScheduleQuery = "DELETE FROM eventschedules WHERE eventID = '$eventID'";
                    $conn->query($deleteScheduleQuery);

                    foreach ($schedules['datetime'] as $index => $datetime){
                        $description = $schedules['description'][$index];

                        $datetime = $conn->real_escape_string($datetime);
                        $description = $conn->real_escape_string($description);

                        $scheduleUpdate = "INSERT INTO eventschedules(eventID, scheduleDateTime, activityDescription)".
                            " VALUES ('$eventID', '$datetime', '$description')";
                        $conn->query($scheduleUpdate);
                    }

                    $deleteHighlightQuery = "DELETE FROM eventhighlights WHERE eventID = '$eventID'";
                    $conn->query($deleteHighlightQuery);

                    foreach ($highlights as $highlight) {
                        $highlightUpdate = "INSERT INTO eventhighlights(eventID, highlights)".
                            " VALUES ('$eventID', '$highlight')";
                        $conn->query($highlightUpdate);
                    }

                    $deleteGuestsQuery = "DELETE FROM eventguests WHERE eventID = '$eventID'";
                    $conn->query($deleteGuestsQuery);

                    for ($i = 0; $i < count($guestName); $i++) {
                        $name = $conn->real_escape_string($guestName[$i]);
                        $bio = $conn->real_escape_string($guestBio[$i]);
                        $imagePath = $conn->real_escape_string($uploadedImages[$i]);

                        if (!empty($name)) {
                            $guestInsert = "
            INSERT INTO eventguests (eventID, guestName, guestBio, guestProfilePic) 
            VALUES ('$eventID', '$name', '$bio', '$imagePath')
        ";
                            if (!$conn->query($guestInsert)) {
                                echo "Error adding guest $name: " . $conn->error . "<br>";
                            }
                        }
                    }

                    if ($eventStatus == "Past"){
                        $checkQuery = "SELECT * FROM pastevents WHERE eventID = $eventID";
                        $checkResult = $conn->query($checkQuery);

                        if ($checkResult->num_rows==0){
                            $sql = "INSERT INTO pastevents(eventID) VALUES($eventID)";
                            $conn->query($sql);
                        }
                    }

                    if ($eventStatus == "Upcoming"){
                        $deleteSql = "DELETE FROM pastevents WHERE eventID = $eventID";
                        $conn->query($deleteSql);
                    }

                    if ($action=="editPast"){
                        $impact = $_POST['impact'];

                        $sql = "UPDATE pastevents SET impact = '$impact' WHERE eventID = '$eventID'";
                        if ($conn->query($sql) === TRUE) {
                            if (!empty($uploadedFiles)) {
                                $deleteQuery = "DELETE FROM photogallery WHERE eventID = '$eventID'";
                                if (!$conn->query($deleteQuery)) {
                                    echo "Error deleting old images: " . $conn->error;
                                }

                                foreach ($uploadedFiles as $filePath) {
                                    $sql = "INSERT INTO photogallery (eventID, imagePath) VALUES ('$eventID', '$filePath')";
                                    if (!$conn->query($sql)) {
                                        echo "Error inserting image: " . $conn->error;
                                    }
                                }
                            }
                        }
                    }
                    echo "<script>alert('Event Updated'); window.location.href='admin_events.php';</script>";
                }
            }
            //delete
            elseif ($action=="delete" || $action=="deletePast"){
                $sql = "DELETE FROM events WHERE eventID = '$eventID'";
                if ($action=="deletePast"){
                    $sqlPast = "DELETE FROM pastevents WHERE eventID = '$eventID'";
                    $conn->query($sqlPast);

                    $sqlGallery = "DELETE FROM photoGallery WHERE eventID = '$eventID'";
                    $conn->query($sqlGallery);
                }
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Event Deleted'); window.location.href='admin_events.php';</script>";
                }
            }
            //add new event
            elseif ($action == "add"){
                $query = "INSERT INTO events (eventName, start_dateTime, end_dateTime, location, details, participantsNeeded, volunteersNeeded, eventStatus, eventPic) VALUES ('$eventName', '$startDateTime', '$endDateTime', '$location', '$details', '$participantsNeeded', '$volunteersNeeded', '$eventStatus', '$eventImagePath')";

                if ($conn->query($query) === TRUE) {
                    $eventID = $conn->insert_id;

                    foreach ($schedules['datetime'] as $index => $datetime){
                        $description = $schedules['description'][$index];

                        $datetime = $conn->real_escape_string($datetime);
                        $description = $conn->real_escape_string($description);

                        $scheduleUpdate = "INSERT INTO eventschedules(eventID, scheduleDateTime, activityDescription)".
                            " VALUES ('$eventID', '$datetime', '$description')";
                        $conn->query($scheduleUpdate);
                    }

                    foreach ($highlights as $highlight) {
                        $highlightQuery = "INSERT INTO eventhighlights (eventID, highlights)"
                            . "VALUES ('$eventID', '$highlight')";
                        $conn->query($highlightQuery);
                    }

                    for ($i = 0; $i < count($guestName); $i++) {
                        $name = $conn->real_escape_string($guestName[$i]);
                        $bio = $conn->real_escape_string($guestBio[$i]);
                        $imagePath = $conn->real_escape_string($uploadedImages[$i]);

                        if (!empty($name)) {
                            $guestInsert = "
            INSERT INTO eventguests (eventID, guestName, guestBio, guestProfilePic) 
            VALUES ('$eventID', '$name', '$bio', '$imagePath')
        ";
                            if (!$conn->query($guestInsert)) {
                                echo "Error adding guest $name: " . $conn->error . "<br>";
                            }
                        }
                    }

                    echo "<script>alert('New Event Added'); window.location.href='admin_events.php';</script>";
                }
            }
        }
    }

    if ($action == "edit" || $action == "editPast"){
        echo "<h2>Update Event</h2>";
    }
    elseif ($action == "delete" || $action == "deletePast"){
        echo "<h2>Delete Event</h2>";
    }
    elseif ($action == "add"){
        echo "<h2>Add New Event</h2>";
    }

    ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-grp">
            <div class="event-image">
                <img src="<?php echo ($eventData['eventPic'])?>" alt="Event Image" id="eventImg" class="image">
            </div>
            <p>Event Image: </p>
            <input type="file" name="eventImage" id="uploadPic" accept="image/*" onchange="previewEventImage()">
        </div>

        <div class="form-grp">
            <p>Event Name:</p>
            <label><input type="text" name="eventName" value="<?php echo $eventData['eventName'] ?? ''; ?>" placeholder="Enter event name..."></label>
            <p class="error-message"><?= $errors['eventName'] ?? '' ?></p>
        </div>

        <div class="form-grp">
            <p>Event DateTime:</p>
            <div class="datetime">
                <label>Start DateTime: <input type="datetime-local" name="startDateTime" value="<?php echo ($eventData['start_dateTime'])?>"></label>
                <p class="error-message"><?= $errors['startDateTime'] ?? '' ?></p>
                <label>End DateTime: <input type="datetime-local" name="endDateTime" value="<?php echo ($eventData['end_dateTime'])?>"></label>
                <p class="error-message"><?= $errors['endDateTime'] ?? '' ?></p>
            </div>
        </div>

        <div class="form-grp">
            <p>Event Location:</p>
            <label><input type="text" name="location" value="<?php echo $eventData['location'] ?? '';?>" placeholder="Enter event location..."></label>
            <p class="error-message"><?= $errors['location'] ?? '' ?></p>
        </div>

        <div class="form-grp">
            <p>Event Details:</p>
            <label><input type="text" name="details" value="<?php echo $eventData['details'] ?? '';?>" placeholder="Enter brief event details..."></label>
        </div>

        <div class="form-grp">
            <p>Event Highlights:</p>
            <div id="highlights-container">
                <?php
                if ($eventID){
                    $highlightQuery = "SELECT * FROM eventhighlights WHERE eventId = '$eventID'";
                    $result = $conn->query($highlightQuery);
                    while ($highlight = $result->fetch_assoc()){
                        echo "<div class='dynamic-inputs'><label><input type='text' name='highlights[]' value='{$highlight['highlights']}' placeholder='Enter event highlights...'></label><button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button></div>";
                    }
                }
                else{
                    echo "<div class='dynamic-inputs'><label><input type='text' name='highlights[]' placeholder='Enter event highlights...'></label><button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button></div>";
                }
                ?>
                <button type="button" class="add-remove-btn" id="add-highlight-button" onclick="addHighlights()">+</button>
            </div>
        </div>

        <div class="form-grp">
            <p>Event Schedule:</p>
            <div id="schedule-container">
                <?php
                if ($eventID){
                    $scheduleQuery = "SELECT * FROM eventschedules WHERE eventID = '$eventID'";
                    $result = $conn->query($scheduleQuery);
                    while ($schedule = $result->fetch_assoc()){
                        //display schedule time date and activity
                        echo "<div class='dynamic-inputs'>
<label><input type='datetime-local' name='schedules[datetime][]' value='{$schedule['scheduleDateTime']}'>
<input type='text' name='schedules[description][]' value='{$schedule['activityDescription']}' placeholder='Enter event schedule...'></label>
<button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button></div>";
                    }
                }
                else{
                    echo "<div class='dynamic-inputs'>
<label><input type='datetime-local' name='schedules[datetime][]'>
<input type='text' name='schedules[description][]' placeholder='Enter event schedule...'></label>
<button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button></div>";
                }
                ?>
                <button type="button" id="add-schedule-button" class="add-remove-btn" onclick="addSchedule()">+</button>
            </div>
        </div>

        <div class="form-grp">
            <p>Featured Speaker/Event Guests:</p>
            <div id="guest-container">
                <?php
                $guestCounter = 0;

                if ($eventID) {
                    $guestQuery = "SELECT * FROM eventguests WHERE eventID = '$eventID'";
                    $result = $conn->query($guestQuery);

                    while ($guestList = $result->fetch_assoc()) {
                        $guestCounter++;
                        $imgId = "guestPic-$guestCounter";
                        $inputId = "uploadGuestPic-$guestCounter";

                        echo "<div class='dynamic-inputs'>";
                        echo "<div class='guestPic'>";
                        echo "<input type='hidden' name='existingGuestPics[]' value='{$guestList['guestProfilePic']}'>";
                        echo "<img src='{$guestList['guestProfilePic']}' alt='Guest Picture' id='$imgId' class='roundImage'>";
                        echo "</div>";
                        echo "<label>";
                        echo "<input type='file' name='guestPic[]' id='$inputId' accept='image/*' onchange='previewGuestImage(this, \"$imgId\")'>";
                        echo "<input type='text' name='guestName[]' value='{$guestList['guestName']}' placeholder='Enter guest name...'>";
                        echo "<input type='text' name='guestBio[]' value='{$guestList['guestBio']}' placeholder='Enter guest bio...'>";
                        echo "</label>";
                        echo "<button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button>";
                        echo "</div>";
                    }
                } else {
                    $guestCounter++;
                    $imgId = "guestPic-$guestCounter";
                    $inputId = "uploadGuestPic-$guestCounter";

                    echo "<div class='dynamic-inputs'>";
                    echo "<div class='guestPic'>";
                    echo "<img src='' alt='Guest Picture' id='$imgId' class='roundImage'>";
                    echo "</div>";
                    echo "<label>";
                    echo "<input type='file' name='guestPic[]' id='$inputId' accept='image/*' onchange='previewGuestImage(this, \"$imgId\")'>";
                    echo "<input type='text' name='guestName[]' placeholder='Enter guest name...'>";
                    echo "<input type='text' name='guestBio[]' placeholder='Enter guest bio...'>";
                    echo "</label>";
                    echo "<button type='button' class='add-remove-btn' onclick='removeRow(this)'>-</button>";
                    echo "</div>";
                }
                ?>
                <button type="button" id="add-guest-button" class="add-remove-btn" onclick="addGuest()">+</button>
            </div>
        </div>

        <div class="form-grp">
            <p>Participants Needed:</p>
            <label><input type="text" name="participantsNeeded" value="<?php echo $eventData['participantsNeeded'] ?? ''; ?>" placeholder="Enter Participants needed..."></label>
            <p class="error-message"><?= $errors['participantsNeeded'] ?? '' ?></p>
        </div>

        <div class="form-grp">
            <p>Volunteers Needed:</p>
            <label><input type="text" name="volunteersNeeded" value="<?php echo $eventData['volunteersNeeded'] ?? ''; ?>" placeholder="Enter Volunteers needed..."></label>
            <p class="error-message"><?= $errors['volunteersNeeded'] ?? '' ?></p>
        </div>

        <div class="form-grp">
            <p>Event Status:</p>
            <label><input type="text" name="eventStatus" value="<?php echo $eventData['eventStatus'] ?? '';?>" placeholder="Enter Event Type..."></label>
            <p class="error-message"><?= $errors['eventStatus'] ?? '' ?></p>
        </div>

        <?php if ($action == "editPast" || $action == "deletePast"){?>
            <div class="form-grp">
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
                <label><input type="text" name="attendees" value="<?= $attendeesCount ?>" disabled></label>
            </div>

            <div class="form-grp">
                <p>Impact and Outcomes:</p>
                <label><input type="text" name="impact" value="<?php echo $eventData['impact'] ?? '';?>"></label>
            </div>

            <div class="form-grp">
                <p>Photo Gallery:</p>
                <label><input type="file" name="photoGallery[]" accept="image/*" multiple onchange='previewPhotoGallery()'>
                    <div id="gallery-preview" class="gallery-img"></div>
                </label>
                <p class="note">You need to re-upload all pictures if you want to update the photo gallery.</p>
            </div>

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
            elseif ($action == "add") {
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
                elseif ($action == "add") {
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

    function previewPhotoGallery(){
        const files = document.querySelector('input[name="photoGallery[]"]').files;
        const preview = document.getElementById('gallery-preview');

        preview.innerHTML = '';

        for (let i=0; i<files.length; i++){
            const reader = new FileReader();
            reader.onload = function (e){
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('photo-gallery');
                preview.appendChild(img);
            };
            reader.readAsDataURL(files[i]);
        }
    }

    function previewGuestImage(inputElement, imgElementId) {
        const file = inputElement.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgElement = document.getElementById(imgElementId);
                imgElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function addHighlights() {
        const container = document.getElementById("highlights-container");
        const newHighlight = document.createElement("div");
        newHighlight.className = "dynamic-inputs";
        newHighlight.innerHTML = `
        <label><input type="text" name="highlights[]" placeholder="Enter event highlights..."></label>
        <button type="button" class="add-remove-btn" onclick="removeRow(this)">-</button>
    `;
        const addButton = document.getElementById('add-highlight-button');
        container.insertBefore(newHighlight, addButton);
    }

    function addSchedule() {
        const container = document.getElementById("schedule-container");
        const newSchedule = document.createElement("div");
        newSchedule.className = "dynamic-inputs";
        newSchedule.innerHTML = `
<label><input type='datetime-local' name='schedules[datetime][]'>
<input type='text' name='schedules[description][]' placeholder='Enter event schedule...'></label>
<button type='button' class="add-remove-btn" onclick='removeRow(this)'>-</button>
    `;
        const addButton = document.getElementById('add-schedule-button');
        container.insertBefore(newSchedule, addButton);
    }

    let guestCounter = <?php echo $guestCounter; ?>;

    function addGuest() {
        const container = document.getElementById("guest-container");

        guestCounter++;

        const newGuest = document.createElement("div");
        newGuest.className = "dynamic-inputs";

        const imgId = `guestPic-${guestCounter}`;
        const inputId = `uploadGuestPic-${guestCounter}`;

        newGuest.innerHTML = `
        <div class="guestPic">
            <img src='' alt='Guest Picture' id='${imgId}' class='roundImage'>
        </div>
        <label>
            <input type='file' name='guestPic[]' id='${inputId}' accept='image/*' onchange='previewGuestImage(this, "${imgId}")'>
            <input type="text" name="guestName[]" placeholder="Enter guest's name...">
            <input type="text" name="guestBio[]" placeholder="Enter guest's bio...">
        </label>
        <button type="button" class="add-remove-btn" onclick="removeRow(this)">-</button>
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