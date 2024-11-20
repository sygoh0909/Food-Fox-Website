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
    <title>Add/Edit/Delete Event Page</title>

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
    $conn = connection();
    //check for event id presence
    $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $eventData = null;

    if ($eventID) {
        $sql = "SELECT * FROM events WHERE eventID = '$eventID'";
        $result = $conn->query($sql);
        $eventData = $result->fetch_assoc(); //retrieves the data as an associative array

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $eventName = $_POST['eventName'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $startTime = $_POST['startTime'];
            $endTime = $_POST['endTime'];
            $location = $_POST['location'];
            $details = $_POST['details'];
            $registrationsNeeded = $_POST['registrationsNeeded'];
            $eventStatus = $_POST['eventStatus'];
            $highlights = $_POST['highlights'];
            $schedules = $_POST['schedules'];
            $guestName = $_POST['guestName'];
            $guestBio = $_POST['guestBio'];

            $startDateTime = $startDate . " " . $startTime;
            $endDateTime = $endDate . " " . $endTime;

            //regular expressions


            $errors = [];
            $eventImagePath = '';
            $guestImagePath = '';

            //validation
            if (empty($eventName)) {
                $errors[] = "Event Name is required";
            }
            if (empty($startDate)) {
                $errors[] = "Start Date is required";
            }
            if (empty($endDate)) {
                $errors[] = "End Date is required";
            }
            if (empty($startTime)) {
                $errors[] = "Start Time is required";
            }
            if (empty($endTime)) {
                $errors[] = "End Time is required";
            }
            if (empty($location)) {
                $errors[] = "Location is required";
            }
            //only can be numbers - regular expression
            if (empty($registrationsNeeded)) {
                $errors[] = "Registrations Needed is required";
            }
            //only can be upcoming or past status
            if (empty($eventStatus)) {
                $errors[] = "Event Status is required";
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

            //update event
            if (empty($errors)){
                if ($action=="edit"){
                    //update pics??? display pics that saved???
                    $updateQuery = "UPDATE events SET eventName = '$eventName', start_dateTime = '$startDateTime', end_dateTime = '$endDateTime', location = '$location', details = '$details', registrationsNeeded = '$registrationsNeeded', eventStatus = '$eventStatus', eventPic = '$eventImagePath' WHERE eventID = '$eventID'";

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
                        echo "<script>alert('Event Updated'); window.location.href='admin_events.php';</script>";
                    }
                    }
                }
                elseif ($action == "delete"){
                    $sql = "DELETE FROM events WHERE eventID = '$eventID'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Event Deleted'); window.location.href='admin_events.php';</script>";
                    }
                }
                //add new event
                else{
                    if (empty($errors)){
                        $query = "INSERT INTO events (eventName, start_dateTime, end_dateTime, location, details, registrationsNeeded, eventStatus, eventPic) VALUES ('$eventName', '$startDateTime', '$endDateTime', '$location', '$details', '$registrationsNeeded', '$eventStatus', '$eventImagePath')";

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
                    }
                }
            }
        if ($action == "edit"){
            echo "<h2>Update Event</h2>";
        }
        elseif ($action == "delete"){
            echo "<h2>Delete Event</h2>";
        }
        else{
            //add event
            echo "<h2>Add New Event</h2>";
        }
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <p>Event Image:</p>
        <label><input type="file" name="eventImage" accept="image/*" onchange='previewEventImage()'> <!--show the image saved in database-->
            <img id="eventImagePreview" class="event-image-preview" alt="Event Image Preview" style="display: none">
        </label>

        <p>Event Name:</p>
        <label><input type="text" name="eventName" value="<?php echo isset($eventData['eventName']) ? $eventData['eventName'] : ''; ?>" placeholder="Enter event name..."></label>

        <p>Event Date:</p>
        <label>Start Date: <input type="date" name="startDate" value="<?php echo isset($eventData['start_dateTime']) ? substr($eventData['start_dateTime'], 0,10) : '';?>"></label>
        <label>End Date: <input type="date" name="endDate" value="<?php echo isset ($eventData['end_dateTime']) ? substr($eventData['end_dateTime'], 0, 10): '';?>"</label>

        <p>Event Time:</p>
        <label>Start Time: <input type="time" name="startTime" value="<?php echo isset($eventData['start_dateTime']) ? substr($eventData['start_dateTime'], 11, 5): '';?>"</label>
        <label>End Time: <input type="time" name="endTime" value="<?php echo isset($eventData['end_dateTime']) ? substr($eventData['end_dateTime'], 11, 5): '';?>"></label>

        <p>Event Location:</p>
        <label><input type="text" name="location" value="<?php echo isset ($eventData['location']) ? $eventData['location']:'';?>" placeholder="Enter event location..."></label>

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
            <button type="button" onclick="addHighlights()">+</button>
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
            <button type="button" onclick="addSchedule()">+</button>
        </div>

        <p>Featured Speaker/Event Guests:</p>
        <div id="guest-container">
            <?php
            if ($eventID) {
                $guestQuery = "SELECT * FROM eventguests WHERE eventID = '$eventID'";
                $result = $conn->query($guestQuery);
                while ($guestList = $result->fetch_assoc()) {
                    echo "<div class='dynamic-inputs'><label><input type='file' name='guestImage' accept='image/*'' onchange='previewGuestImage()'><img id='guestImagePreview' class='guest-image-preview' alt='Guest Image Preview' style='display: none'><input type='text' name='guestName[]' value='{$guestList['guestName']}' placeholder='Enter guests name...'><input type='text' name='guestBio[]' placeholder='Enter guests bio...'></label>";
                }
            }
            else{
                echo "<div class='dynamic-inputs'><label><input type='file' name='guestImage' accept='image/*'' onchange='previewGuestImage()'><img id='guestImagePreview' class='guest-image-preview' alt='Guest Image Preview' style='display: none'><input type='text' name='guestName[]' placeholder='Enter guests name...'><input type='text' name='guestBio[]' placeholder='Enter guests bio...'></label>";
            }
            ?>
            <button type="button" onclick="addGuest()">+</button>
        </div>

        <p>Registrations Needed:</p>
        <label><input type="text" name="registrationsNeeded" value="<?php echo isset ($eventData['registrationsNeeded']) ? $eventData['registrationsNeeded']: ''; ?>" placeholder="Enter Registrations needed..."></label>

        <p>Event Status:</p>
        <label><input type="text" name="eventStatus" value="<?php echo isset ($eventData['eventStatus']) ? $eventData['eventStatus']: '';?>" placeholder="Enter Event Type..."></label>

        <div class="button">
            <button type="submit"><?php echo $eventID && $action=='edit'? 'Update Event' : 'Add Event'; ?></button>
            <a href="admin_events.php"><button id="button1">Cancel</button></a>
        </div>
    </form>
</main>
<script>
    function previewEventImage(event){
        const eventImagePreview = document.getElementById('eventImagePreview');
        eventImagePreview.src = URL.createObjectURL(event.target.files[0]);
        eventImagePreview.style.display = 'block';
    }

    function previewGuestImage(event){
        const guestImagePreview = document.getElementById('guestImagePreview');
        guestImagePreview.src = URL.createObjectURL(event.target.files[0]);
        guestImagePreview.style.display = 'block';
    }

    function addHighlights(){
        const container = document.getElementById('highlights-container');
        const newInput = document.createElement('div');
        newInput.classList.add('dynamic-inputs');
        newInput.innerHTML = `<label><input type="text" name="highlights[]" placeholder="Enter event highlights..."></label>
                              <button type="button" onclick="removeRow(this)">-</button>`;
        container.appendChild(newInput);
    }

    function addSchedule(){
        const container = document.getElementById('schedule-container');
        const newInput = document.createElement('div');
        newInput.classList.add('dynamic-inputs'); /*add css styles to here*/
        newInput.innerHTML = `<label><input type="text" name="schedules[]" placeholder="Enter date/time, activity description..."></label>
                              <button type="button" onclick="removeRow(this)">-</button>`;
        container.appendChild(newInput);
    }

    function addGuest(){
        const container = document.getElementById('guest-container');
        const newInput = document.createElement('div');
        newInput.classList.add('dynamic-inputs');
        newInput.innerHTML = `<label><input type="file" name="guestImage" accept="image/*" onchange="previewGuestImage()">
            <img id="guestImagePreview" class="guest-image-preview" alt="Guest Image Preview" style="display: none">
            <input type="text" name="guestName[]" placeholder="Enter guests name...">
            <input type="text" name="guestBio[]" placeholder="Enter guests bio...">
                              <button type="button" onclick="removeRow(this)">-</button>`;
        container.appendChild(newInput);
    }

    function removeRow(button){
        button.parentElement.remove();
    }
</script>
</body>
</html>