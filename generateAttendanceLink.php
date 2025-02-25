<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
$conn = connection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/Food-Fox-Website/vendor/autoload.php';

$eventID = $_GET['eventID'] ?? '';
if ($eventID){
    $sql = "SELECT m.memberID, m.email, e.eventName FROM registrations r INNER JOIN members m ON r.memberID = m.memberID INNER JOIN events e ON r.eventID = e.eventID WHERE r.eventID = $eventID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)){
            $memberEmail = $row['email'];
            $eventName = $row['eventName'];

            $loginFormUrl = 'http://localhost/Food-Fox-Website/attendance.php?eventID=' .$eventID . "&memberID=" .$row['memberID'];
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
        $email->Subject = 'Attendance link!';
        $email->Body = "<h3>Attendance link</h3>
<p>You have registered for the event $eventName. Please use the link below to mark your attendance:</p>
<a href='$loginFormUrl'>Click here</a>";

        $email->send();
        echo "<script>alert('Attendance link sent successfully!'); window.location.href = 'admin_events.php';</script>";
    }
    catch (Exception $e){
        echo "Email could not be sent. Error: {$email->ErrorInfo}";
    }
}
?>
