<?php
include('cookie.php');
$conn = connection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require('C:/xampp/htdocs/assignment/phpmailer/src/Exception.php');
//require('C:/xampp/htdocs/assignment/phpmailer/src/PHPMailer.php');
//require('C:/xampp/htdocs/assignment/phpmailer/src/SMTP.php');
require 'C:/xampp/htdocs/assignment/vendor/autoload.php';

$registrationID = isset($_GET['registrationID']) ? $_GET['registrationID'] : null;
$registrationData = null;

if ($registrationID) {
    $sql = "SELECT r.*, e.eventName, e.location, e.start_dateTime, e.end_dateTime, m.email FROM registrations r 
            INNER JOIN events e ON r.eventID = e.eventID INNER JOIN members m ON r.memberID = m.memberID
            WHERE r.registrationID = $registrationID";
    $result = mysqli_query($conn, $sql);
    $registrationData = mysqli_fetch_assoc($result);

    if ($registrationData) {
        sendMail($registrationData['email'], $registrationData);

    }else{

    }

} else {
    echo "<p>Invalid Registration ID.</p>";
    exit;
}

function sendMail($recipientEmail, $registrationData){
    global $registrationID, $conn;
    $email = new PHPMailer(true);

    try{
        $email->isSMTP();
        $email->Host       = 'smtp.gmail.com'; //gmail
        $email->SMTPAuth   = true;
        $email->Username   = 'shuyigoh2004@gmail.com';
        $email->Password   = 'htqw qlqx eosg iehv';
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port       = 587;

        $email->setFrom('shuyigoh2004@gmail.com', 'Food Fox');
        $email->addAddress($recipientEmail);

        //content
        $email->isHTML(true);
        $email->Subject = 'Thank you for registering!';
        $email->Body = "<h3>This is your registration details.</h3>
<p><strong>Event Name: </strong>{$registrationData['eventName']}</p>
<p><strong>Date and Time: </strong>{$registrationData['start_dateTime']} - {$registrationData['end_dateTime']}</p>
<p><strong>Location: </strong>{$registrationData['location']}</p>
<p><strong>Register Type: </strong>{$registrationData['registerType']}</p>
<p><strong>Dietary Restrictions: </strong>{$registrationData['dietaryRestrictions']}</p>
<!--follow register type, if participants, get info from participants table, if volunteer...-->";
        if ($registrationData['registerType'] == 'Participant') {
            $sql = "SELECT * FROM participants WHERE registrationID = $registrationID";
            $result = mysqli_query($conn, $sql);
            $participantData = mysqli_fetch_assoc($result);

            $email->Body .= "<p><strong>Special Accommodation: </strong>{$participantData['specialAccommodation']}</p>
<p><strong>T-shirt Size: </strong>{$participantData['shirtSize']}</p>";
        }
        elseif ($registrationData['registerType'] == 'Volunteer') {
            $sql = "SELECT * FROM volunteers WHERE registrationID = $registrationID";
            $result = mysqli_query($conn, $sql);
            $volunteerData = mysqli_fetch_assoc($result);

            $email->Body .= "<p><strong>Relevant Skills: </strong>{$volunteerData['relevantSkills']}</p>";
        }
        else{

        }
        $email->send();
//        echo "Email sent successfully!";

    }catch (Exception $e){
//        echo "Email could not be sent. Error: {$email->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Info</title>
    <style>
        .registration-info {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .registration-info h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-info p {
            margin: 10px 0;
            font-size: 16px;
        }
        .button-container {
            text-align: center;
        }
        .button-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #7F6C54;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color: #6B5A48;
        }
    </style>
</head>
<body>
<div class="registration-info">
    <h2>Thank you for registering!</h2>
    <p>Your registration for <strong> <?= $registrationData['eventName'] ?></strong> has been successfully received.</p>
    <p>We have sent a confirmation email to <strong><?= $registrationData['email'] ?></strong> with all of the details.</p>
    <p>If you need to make any changes or have any questions, please contact us at <strong> +603-0929 0501</strong> or <strong> info@foodfox.org.my</strong></p>
    <div class="button-container">
        <a href="events.php">Back to Events</a>
        <a href="mailto:<?= $registrationData['email'] ?>">Check Your Email</a> <!--no need mail to-->
    </div>
</div>
</body>
</html>
