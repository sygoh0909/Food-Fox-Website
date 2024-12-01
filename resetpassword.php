<?php
include ('cookie.php');
$conn = connection();
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($token){
    $sql = "SELECT * FROM passwordresets WHERE token = $token";

}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>
</head>
<body>
</body>
</html>

