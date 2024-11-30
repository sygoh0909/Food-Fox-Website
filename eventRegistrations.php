<?php
include("cookie.php");

$conn = connection();
$selectedEventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
$selectedEventData = null;
$memberID = $_SESSION['memberID'];

if ($selectedEventID){
    $sql = "SELECT eventName FROM events WHERE eventID = '$selectedEventID'";
    $result = mysqli_query($conn, $sql);
    $selectedEventData = mysqli_fetch_assoc($result);
}
$sql = "SELECT eventID, eventName FROM events";
$result = mysqli_query($conn, $sql);
$events = []; //array to keep events
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registerType = isset($_POST['registrations']) ? trim($_POST['registrations']) : '';
    $dietaryRestrictions = $_POST["dietaryRestrictions"];

    $errors = []; //check for errors
    if (empty ($registerType)) {
        $errors['registrations'] = "Registration type is required";
    }
    elseif (!in_array($registerType, ["Participant", "Volunteer"])) {
        $errors['registrations'] = "Registration type must be either Participant or Volunteer.";
    }

    if (empty($errors)){
        //need event id, and member id also
        $sql = "INSERT INTO registrations (eventID, memberID, registerType, dietaryRestrictions) VALUES ('$selectedEventID', '$memberID', '$registerType', '$dietaryRestrictions')";
        if (mysqli_query($conn, $sql)){
            $registrationID = mysqli_insert_id($conn);

            if ($registerType == "Participant"){
                $specialAccommodation = $_POST["specialAccommodation"];
                $sizes = $_POST["sizes"];
                $sql = "INSERT INTO participants (registrationID, specialAccommodation, shirtSize) VALUES ('$registrationID', '$specialAccommodation', '$sizes')";
                mysqli_query($conn, $sql);
            }
            else if ($registerType == "Volunteer"){
                $relevantSkills = $_POST["skills"];
                $sql = "INSERT INTO volunteers (registrationID, relevantSkills) VALUES ('$registrationID', '$relevantSkills')";
                mysqli_query($conn, $sql);
            }
            echo "<script>alert('Registered successfully!'); window.location.href = 'events.php';</script>";
        }
    }
    else{
        //error
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Registrations Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="form.css">
    <style>
        .main{
            color: white;
        }
        .participant-field, .volunteer-field{
            display: none;
        }
        .note {
            font-size: 14px;
            color: #5C4033;
            margin-top: 10px;
            text-align: center;
        }
        img {
            margin-top: 10px;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .missing-info-alert {
            background-color: #ffe6e6;
            border: 1px solid #ffcccc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .missing-info-alert p {
            color: #cc0000;
            font-weight: bold;
        }

        .missing-info-alert a button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .missing-info-alert a button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="social-media">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>

            <div class="main-links">
                <a href="mainpage.php" class="roundButton main">Home</a>
                <a href="events.php" class="roundButton main">Events</a>
                <a href="donations.php" class="roundButton main">Donation</a>
                <a href="contact.php" class="roundButton main">Contact</a>
            </div>

            <div class="nav-links">
                <?php
                loginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <form method="POST" enctype="multipart/form-data">
    <label for="events">Choose an event: </label>
    <select name="events" id="events">
        <?php
        foreach ($events as $event): ?>
        <option value="<?php echo $event['eventID']; ?>"
            <?php echo $selectedEventID == $event['eventID'] ? 'selected' : ''; ?>>
            <?php echo $event['eventName']; ?>
        </option>
        <?php endforeach; ?>
    </select>

    <?php
    if ($memberID) {
        $sql = "SELECT memberID, memberName, email, phoneNum FROM members WHERE memberID = $memberID";
        $result = mysqli_query($conn, $sql);
        $memberData = mysqli_fetch_assoc($result);
    }?>

        <!--sync info from profile (show a note also if wanna change info, change from profile), if user havent fill, pop up a alert message and direct them to profile-->
        <p class="note">Please note that name, email, and phone number will be automatically synced from your profile. Please proceed to profile page to change any information. </p>


        <p>Name:</p>
        <?php echo isset($memberData['memberName']) ? $memberData['memberName']: '';
        if (empty($memberData['memberName'])){
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                echo "<p class='error-message'>Please enter your name.</p>";
            }
        }?>

    <p>Email:</p>
    <?php echo isset($memberData['email']) ? $memberData['email']: '';
    if (empty($memberData['email'])){
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            echo "<p class='error-message'>Please enter an email address.</p>";
        }
    }?>

    <p>Phone Number:</p>
    <?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>

        <?php if (empty($memberData['phoneNum'])):
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                echo "<p class='error-message''>Please enter a phone number.</p>";
            }
            ?>
            <div class="missing-info-alert">
                <p>You haven't provided a phone number in your profile.</p>
                <p>Please fill in your phone number in your profile</p>
                <?php echo "<a href='profile.php?memberID=". $memberData['memberID']."&action=registration'><button type='button'>Proceed to Profile Page</button></a>"?>
            </div>
        <?php endif; ?>

        <p>Dietary Restrictions:</p>
        <label><input type="text" name="dietaryRestrictions" placeholder="Enter any dietary restrictions if got..."></label>

    <label for="registrations">Registration type: </label>
    <select name="registrations" id="registrations" onchange="showFields()">
        <option value="" disabled selected>Select registration type</option>
        <option value="Participant">Participant</option>
        <option value="Volunteer">Volunteer</option>
    </select>
        <p class="error-message""><?= isset($errors['registrations']) ? $errors['registrations'] : '' ?></p>

    <div class="participant-field">

        <p>Special Accommodation:</p>
        <label><input type="text" name="specialAccommodation" placeholder="Enter any special accomodation if got..."></label>

        <p>T-Shirt Size</p>
        <label for="sizes"></label>
        <select name="sizes" id="sizes">
            <option value="" disabled selected>Choose a T-Shirt Size</option>
            <option>XS</option>
            <option>S</option>
            <option>M</option>
            <option>L</option>
            <option>XL</option>
        </select>
        <!--provide t-shirt size chart also-->
        <img src="https://www.tshirtprint2u.com.my/images/sizechart_tshirtprint2u.jpg">
    </div>

    <div class="volunteer-field">
        <p class="note">Please note that you are expected to be free for the whole day as a volunteer. </p>

        <p>Relevant skills:</p>
        <label><input type="text" name="skills" placeholder="Enter any relevant skills you have..."></label>
    </div>

    <button type="submit">Submit</button>

    </form>

</main>
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
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>About Us</h4>
            <p>Food Fox is a Malaysian-based non-profit organization focused on providing food to the underprivileged community.</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="mainpage.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Follow Us</h4>
            <div class="social-links">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>
        </div>
        <div class="footer-section">
            <h4>Contact Info</h4>
            <p>Email: info@foodfox.org.my</p>
            <p>Phone Number: +603-0929 0501</p>
            <p>Food Fox Headquarters: 51, Jalan Binjai, KLCC, KL City Centre, Kuala Lumpur</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Food Fox. All rights reserved. | Powered by <a href="https://foodfox.com" target="_blank">Food Fox</a></p>
    </div>
</footer>
</body>
</html>
