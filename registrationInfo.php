<?php
include('cookie.php');
$conn = connection();

$registrationID = isset($_GET['registrationID']) ? $_GET['registrationID'] : null;

if ($registrationID) {
    $sql = "SELECT r.*, e.eventName FROM registrations r 
            JOIN events e ON r.eventID = e.eventID 
            WHERE r.registrationID = $registrationID";
    $result = mysqli_query($conn, $sql);
    $registrationData = mysqli_fetch_assoc($result);
} else {
    echo "<p>Invalid Registration ID.</p>";
    exit;
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
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="registration-info">
    <h2>Registration Details</h2>
    <p><strong>Registration ID:</strong> <?= $registrationData['registrationID'] ?></p>
    <p><strong>Event Name:</strong> <?= $registrationData['eventName'] ?></p>
    <p><strong>Register Type:</strong> <?= $registrationData['registerType'] ?></p>
    <p><strong>Contact:</strong> 123-456-7890</p>
    <div class="button-container">
        <a href="mailto:<?= $registrationData['email'] ?>">Check Your Email</a>
    </div>
</div>
</body>
</html>
