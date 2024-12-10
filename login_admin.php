<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
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
        p.error-message {
            color: red;
        }
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

    $error = array();
    if (empty($loginInput) OR empty($password)) {
        $errors['emptyFields'] = "All fields are required";
    }

    //set an admin username and password
    $adminID = '1234';
    $admin_password = 'admin1234';

    if ($adminID == $loginInput && $admin_password == $password) {
        session_start();
        $_SESSION['adminID'] = $adminID;
        $_SESSION['password'] = $admin_password;
        echo "<script>window.location.href='admin_main.php'; </script>";
    }
    else{
        $errors['incorrectFields'] = "Admin username or password is incorrect";
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
    <form method="POST" enctype="multipart/form-data">
        <p><span class="required">* </span>Admin ID:</p>
        <label><input type="text" name="loginInput" required></label>

        <p><span class="required">* </span>Password:</p>
        <label><input type="password" name="password" required></label>
        <p class="error-message"><?=isset ($errors['emptyFields']) ? $errors['emptyFields'] :''?></p>
        <p class="error-message"><?=isset ($errors['incorrectFields']) ? $errors['incorrectFields'] : ''?></p>

        <button type="submit">Sign In</button>
    </form>

    <p class="login-link">Not an admin? <a href="login.php">Sign in as member!</a></p>

</div>
</body>
</html>