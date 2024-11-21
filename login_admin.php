<?php
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css">
    <style>

    </style>
</head>
<body>

<?php
$conn = connection();

if($conn->connect_error){
    die ("Connection failed ".$conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = $_POST['loginInput'];
    $password = $_POST['password'];

    $error = '';
    if (empty($loginInput) AND empty($password)) {
        $error = "All fields are required";
    }

    //set an admin username and password
    $adminID = '1234';
    $admin_password = 'admin1234';

    if ($adminID == $loginInput && $admin_password == $password) {
        session_start();
        $_SESSION['adminID'] = $adminID;
        $_SESSION['password'] = $admin_password;
        echo "<script> alert('Login successful!'); window.location.href='admin_main.php'; </script>";
    }
    else{
        $error = "Admin username or password is incorrect";
    }
}
?>

<div class="container">
    <div class="welcome-section">
        <h2>Welcome Admin!</h2>
    </div>
    <div class="social-media">
        <a href="https://facebook.com" class="fa fa-facebook"></a>
        <a href="https://instagram.com" class="fa fa-instagram"></a>
        <a href="https://youtube.com" class="fa fa-youtube"></a>
    </div>
</div>
<div class="login-form">
    <h2>Login</h2>
    <h2>Welcome Admin!</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>Admin ID: </p>
        <label><input type="text" name="loginInput"></label>

        <p>Password: </p>
        <label><input type="text" name="password"></label>

        <button type="submit">Sign In</button>
    </form>

    <p class="login-link">xxx <a href="login.php">Sign in as member!</a></p>

</div>
</body>
</html>