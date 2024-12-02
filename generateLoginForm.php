<?php
include('cookie.php');
$conn = connection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/assignment/vendor/autoload.php';

$eventID = isset($_GET['eventID']) ? $_GET['eventID'] :'';
if ($eventID){
    $sql = "SELECT m.memberID, m.email, e.eventName FROM registrations r INNER JOIN members m ON r.memberID = m.memberID INNER JOIN events e ON r.eventID = e.eventID WHERE r.eventID = $eventID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)){
            $memberEmail = $row['email'];
            $eventName = $row['eventName'];

            $loginFormUrl = 'http://localhost:8080/assignment/loginform.php?eventID=' .$eventID . "&memberID=" .$row['memberID'];
            sendMail($eventName, $loginFormUrl, $memberEmail);

        }
    }
}else{
    echo "No event ID found!";
}

function sendMail($eventName, $loginFormUrl, $memberEmail){
    $email = new PHPMailer(true);

    try{
        $email->isSMTP();
        $email->Host       = 'smtp.gmail.com';
        $email->SMTPAuth   = true;
        $email->Username   = 'shuyigoh2004@gmail.com';
        $email->Password   = 'htqw qlqx eosg iehv';
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port       = 587;

        $email->setFrom('shuyigoh2004@gmail.com', 'Food Fox');
        $email->addAddress($memberEmail);

        $email->isHTML(true);
        $email->Subject = 'Attendance login form!';
        $email->Body = "<h3>Attendance login form</h3>
<p>You have registered for the event $eventName. Please use the link below to access the login form and mark your attendance:</p>
$loginFormUrl";

        $email->send();
        echo "<p>Email sent successfully!</p>";
    }
    catch (Exception $e){
        echo "Email could not be sent. Error: {$email->ErrorInfo}";
    }
}
?>
