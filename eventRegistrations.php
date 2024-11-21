<?php
include("cookie.php");
$visitCount = cookie();

$conn = connection();
$selectedEventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
$selectedEventData = null;
$memberID = $_SESSION['memberID']; //is there way to retrieve from cookie idk

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
    $registerType = $_POST["registrations"];
    $dietaryRestrictions = $_POST["dietaryRestrictions"];

    $errors = []; //check for errors

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
            echo "Registration successful!";
        }
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
        $sql = "SELECT email, phoneNum FROM members WHERE memberID = $memberID";
        $result = mysqli_query($conn, $sql);
        $memberData = mysqli_fetch_assoc($result);
    }?>

        <!--sync info from profile, if user havent fill or wanna change, direct them to profile-->
        <!--name????-->
    <p>Email:</p>
    <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']: '';?>"></label>

    <p>Phone Number:</p>
    <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>

    <?php
    if (empty($memberData['phoneNum'])) {
        echo "You haven't provide a phone number in your profile.";
        echo "Update your profile? <a href='profile.php'><button>Yes</button></a><button type='button'>No</button>";
    }
    ?>

        <p>Dietary Restrictions:</p>
        <label><input type="text" name="dietaryRestrictions" placeholder="Enter any dietary restrictions if got..."></label>

    <label for="registrations">Choose a register type: </label>
    <select name="registrations" id="registrations" onchange="showFields()">
        <option>Participant</option>
        <option>Volunteer</option>
    </select>

    <div class="participant-field">

        <p>Special Accommodation:</p>
        <label><input type="text" name="specialAccommodation" placeholder="Enter any special accomodation if got..."></label>

        <p>T-Shirt Size</p>
        <label for="sizes">Choose a T-Shirt Size:</label>
        <select name="sizes" id="sizes">
            <option>XS</option>
            <option>S</option>
            <option>M</option>
            <option>L</option>
            <option>XL</option>
        </select>
        <!--provide t-shirt size chart also-->
    </div>

    <div class="volunteer-field">
        <p>Please note that you should be free the whole day as volunteer. </p>

        <p>Relevant skills:</p>
        <label><input type="text" name="skills" placeholder="Enter any relevant skills you have..."></label>
    </div>

    <button type="submit">Submit</button>

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
</html>
