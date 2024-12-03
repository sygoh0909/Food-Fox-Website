<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
$conn = connection();
$memberData = null;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/assignment/vendor/autoload.php';

if (isset($_POST['submit'])){
    $email = $_POST['email'];

    $sql = "SELECT * FROM members WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0){
        $memberData = mysqli_fetch_assoc($result);
        $memberID = $memberData['memberID'];
        $token = bin2hex(random_bytes(50)); //random token
        $expiry = time() + 3600;
        $sql = "INSERT INTO passwordresets(memberID, token, expiry) VALUES ('$memberID', '$token', '$expiry')";
        mysqli_query($conn, $sql);

        $resetLink = "http://localhost/assignment/resetpassword.php?token=$token";

        sendMail($memberData['email'], $resetLink);
    }

    else{
        echo "<script>alert('No account found with this email address. Please try again!');</script>";
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
        echo "<div class='success-message'>";
        echo "<p>Email sent successfully!</p>";
        echo "<p>Reset password link has been sent to your email. Please check your email and reset your password within one hour.</p>";
        echo "</div>";

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .change-pass-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .change-pass-container p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        .change-pass-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"], input[type="submit"] {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="text"] {
            width: 100%;
        }

        .btn {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            margin: 20px auto;
            border-radius: 5px;
            max-width: 400px;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        .success-message p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="change-pass-container">
    <div class="change-pass">
        <p>Please enter your email address to change your password.</p>
        <form action="" method="post">
            <label><input type="text" name="email" placeholder="Enter your email address here..." required></label>
            <button type="submit" class="btn" name="submit">Submit</button>
        </form>
    </div>
</div>
</body>
</html>