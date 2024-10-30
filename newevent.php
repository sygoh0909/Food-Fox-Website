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
    <form method="POST" enctype="multipart/form-data">
        <p>Event Image:</p>
        <label><input type="file" accept="image/*" onchange="previewEventImage(event)">
            <img id="eventImagePreview" class="event-image-preview" alt="Event Image Preview" style="display: none">
        </label>

        <p>Event Name:</p>
        <label><input type="text" placeholder="Enter event name..."></label>

        <p>Event Date:</p>
        <label>Start Date: <input type="date"></label>
        <label>End Date: <input type="date"></label>

        <p>Event Time:</p>
        <label>Start Time: <input type="time"></label>
        <label>End Time: <input type="time"></label>

        <p>Event Location:</p>
        <label><input type="text" placeholder="Enter event location..."></label>

        <p>Event Details:</p>
        <label><input type="text" placeholder="Enter brief event details..."></label>

        <p>Event Highlights:</p>
        <div id="highlights-container">
            <div class="dynamic-inputs">
                <label><input type="text" placeholder="Enter event highlights..."></label>
                <button type="button" onclick="addHighlights()">+</button>
            </div>
        </div>

        <p>Event Schedule:</p>
        <div class="schedule-container">
            <div class="dynamic-inputs">
                <label><input type="text" placeholder="Enter event schedule..."></label>
                <button type="button" onclick="addSchedule()">+</button>
            </div>
        </div>

        <p>Featured Speaker/Event Guests:</p>
        <label><input type="file" accept="image/*" onchange="previewGuestImage(event)">
            <img id="guestImagePreview" class="guest-image-preview" alt="Guest Image Preview" style="display: none">
        </label>
        <label><input type="text" placeholder="Enter guests bio..."></label>

        <p>Registrations Needed:</p>
        <label><input type="text" placeholder="Enter Registrations needed..."></label>

        <div class="button">
            <button type="button" onclick="addEvent()">Add</button>
            <button type="button" onclick="window.location.href='admin_events.php">Cancel</button>
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

    function addEvent(){
        /*form validation, and add events to database*/
        <?php
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'foodfoxdb';
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($conn->connect_error) {
            die ("Connection failed: " . $conn->connect_error);
        }
        ?>
        alert('Event added successfully!')
    }
</script>
</body>
</html>
