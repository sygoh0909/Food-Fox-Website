<?php
include("cookie.php");
$visitCount = cookie();

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "foodfoxdb";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}
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
                $sql = "INSERT INTO participants (registrationID, specialAccommodation) VALUES ('$registrationID', '$sizes')";
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
    <style>
        body{
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #5C4033;
        }
        .social-media{
            display: flex;
            gap: 10px;
        }
        .social-media a{
            color: white;
            font-size: 20px;
            text-decoration: none;
        }
        .nav-links{
            display: flex;
            gap: 30px;
        }
        .roundButton{
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        .main{
            color: white;
        }
        .login{
            background-color: white;
            color: #d3a029;
            font-size: smaller;
        }
        .signup{
            background-color: #d3a029;
            color: white;
            font-size: smaller;
        }
        .login:hover, .signup:hover{
            transform: translateY(-2px);
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
                <a href="volunteers.php" class="roundButton main">Volunteers</a>
                <a href="donations.php" class="roundButton main">Donation</a>
            </div>

            <div class="nav-links">
                <?php
                if (isset($_SESSION['memberID'])) {
                    $memberID = $_SESSION['memberID'];
                    echo "<a href='profile.php?id=$memberID'>Member ID: $memberID</a>";
                    echo "<p>Welcome back! This is your visit number $visitCount.</p>"; //testing
                } else {
                    echo "<a href='login.php' class='roundButton login'>Login</a>";
                    echo "<a href='signup.php' class='roundButton signup'>Sign Up</a>";
                    echo "<p>This is your visit number $visitCount.</p>";
                }
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

    <?php if (isset($_SESSION['memberID'])) {
        $sql = "SELECT email, phoneNum FROM members WHERE memberID = $memberID";
        $result = mysqli_query($conn, $sql);
        $memberData = mysqli_fetch_assoc($result);
    }?>

    <p>Email:</p>
    <label><input type="text" name="email" value="<?php echo isset($memberData['email']) ? $memberData['email']: '';?>"></label>

    <p>Phone Number:</p>
    <label><input type="text" name="phoneNum" value="<?php echo isset($memberData['phoneNum']) ? $memberData['phoneNum']:'';?>"></label>

    <?php
    if (empty($memberData['phoneNum'])) {
        echo "You haven't provide a phone number in your profile.";
        echo "Update your profile? <a href='profile.php'><button>Yes</button></a><button>No</button>";
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
</html>
