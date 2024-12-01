<?php
include ('cookie.php');
$conn = connection();
$memberID = isset($_GET['memberID']) ? $_GET['memberID']: '';
$memberData = null;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/assignment/vendor/autoload.php';

if (isset($_POST['submit'])){
    $email = $_POST['email'];

    $sql = "SELECT * FROM members WHERE memberID = $memberID";
    $result = mysqli_query($conn, $sql);
    $memberData = mysqli_fetch_assoc($result);

    if ($memberData['email'] == $email){
        $row = mysqli_fetch_assoc($result);
        $token = bin2hex(random_bytes(50)); //random token
        $expiry = time() + 3600;
        $sql = "INSERT INTO passwordresets(memberID, token, expiry) VALUES ('$memberID', '$token', '$expiry')";
        mysqli_query($conn, $sql);

        $resetLink = "http://localhost:63342/assignment/resetpassword.php?token=$token";

        sendMail($memberData['email'], $resetLink);
    }
    else{
        echo "No account found with this email address. Please try again!";
    }
}
function sendMail($recipientEmail, $resetLink){
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
        $email->addAddress($recipientEmail);

        //content
        $email->isHTML(true);
        $email->Subject = 'Password Reset Request!';
        $email->Body = "<h3>Password Reset</h3>
<p>Please click the following link to reset your password: <a href='$resetLink'>Reset Link</a></p>";

        $email->send();
        echo "Email sent successfully!";
        echo "<p>Reset password link has been sent to your email. Please check your email and reset your password in one hour time.</p>";

    }catch (Exception $e){
        echo "Email could not be sent. Error: {$email->ErrorInfo}";
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password Page</title>
</head>
<body>
<div class="change-pass-container">
    <div class="change-pass">
        <p>Please enter your email address to change your password.</p>
        <form action="" method="post">
            <label><input type="text" name="email" placeholder="Enter your email address here..."></label>
            <input type="submit" name="submit" value="submit">
        </form>
    </div>
</div>
</body>
</html>
