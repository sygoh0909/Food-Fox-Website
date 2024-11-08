<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event Page</title>

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
    <h2>Add New Event</h2>

    <?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "foodfoxdb";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
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

        $startDateTime = $startDate . " " . $startTime;
        $endDateTime = $endDate . " " . $endTime;

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
        if (empty($registrationsNeeded)) {
            $errors[] = "Registrations Needed is required";
        }
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

        if (empty($errors)){
            $query = "INSERT INTO events (eventName, start_dateTime, end_dateTime, location, details, registrationsNeeded, eventStatus, eventPic) 
            VALUES ('$eventName', '$startDateTime', '$endDateTime', '$location', '$details', '$registrationsNeeded', '$eventStatus', '$eventImagePath')";

            if ($conn->query($query) === TRUE) {
                $eventID = $conn->insert_id;

                //save each schedule/highlight as a new row in table (now is split by comma)
                
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
                $guestName = $_POST['guestName'];
                $guestBio = $_POST['guestBio'];
                if (!empty($guestName)){
                    $guestQuery = "INSERT INTO eventguests (eventID, guestName, guestBio, guestProfilePic)". "VALUES ('$eventID', '$guestName', '$guestBio', '$guestImagePath')";
                    $conn->query($guestQuery);
                }
            }
            echo "New event added successfully";
        }
    }
    ?>

    <form method="POST" enctype="multipart/form-data"">
        <p>Event Image:</p>
        <label><input type="file" name="eventImage" accept="image/*" onchange='previewEventImage()'">
            <img id="eventImagePreview" class="event-image-preview" alt="Event Image Preview" style="display: none">
        </label>

        <p>Event Name:</p>
        <label><input type="text" name="eventName" placeholder="Enter event name..."></label>

        <p>Event Date:</p>
        <label>Start Date: <input type="date" name="startDate"></label>
        <label>End Date: <input type="date" name="endDate"></label>

        <p>Event Time:</p>
        <label>Start Time: <input type="time" name="startTime"></label>
        <label>End Time: <input type="time" name="endTime"></label>

        <p>Event Location:</p>
        <label><input type="text" name="location" placeholder="Enter event location..."></label>

        <p>Event Details:</p>
        <label><input type="text" name="details" placeholder="Enter brief event details..."></label>

        <p>Event Highlights:</p>
        <div id="highlights-container">
            <div class="dynamic-inputs">
                <label><input type="text" name="highlights" placeholder="Enter event highlights..."></label>
                <button type="button" onclick="addHighlights()">+</button>
            </div>
        </div>

        <p>Event Schedule:</p>
        <div id="schedule-container">
            <div class="dynamic-inputs">
                <label><input type="text" name="schedules" placeholder="Enter date/time, activity description..."></label>
                <button type="button" onclick="addSchedule()">+</button>
            </div>
        </div>

        <p>Featured Speaker/Event Guests:</p>
        <label><input type="file" name="guestImage" accept="image/*" onchange="previewGuestImage()">
            <img id="guestImagePreview" class="guest-image-preview" alt="Guest Image Preview" style="display: none">
            <input type="text" name="guestName" placeholder="Enter guests name...">
            <input type="text" name="guestBio" placeholder="Enter guests bio...">
        </label>

        <p>Registrations Needed:</p>
        <label><input type="text" name="registrationsNeeded" placeholder="Enter Registrations needed..."></label>

        <p>Event Status:</p>
        <label><input type="text" name="eventStatus" placeholder="Enter Event Type..." </label>

        <div class="button">
            <button type="submit">Add</button>
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

    function removeRow(button){
        button.parentElement.remove();
    }
</script>
</body>
</html>