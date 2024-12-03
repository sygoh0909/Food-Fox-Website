<?php
include('../cookie/cookie.php');
include('../db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <style>
        p.error-message {
            color: red;
        }
        p.forgot-pass{
            color: #2196f3;
        }
        a{
            color: #2196f3;
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

    $errors[] = array();

    if (empty($loginInput) OR empty($password)) {
        $errors['emptyFields'] = "All fields are required";
    }

    //check from database if password and id/email matches
    $query = "SELECT * FROM members WHERE memberID = '$loginInput' OR email = '$loginInput' OR password = '$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user){
        if (password_verify($password, $user['password'])){
            session_start();
            $_SESSION['memberID'] = $user['memberID'];
            echo "<script> alert('Login Successfully!'); window.location.href='mainpage.php'; </script>";
        }
        $errors['incorrectField'] = "Member ID/email or password is incorrect";
    }
    else{
        $errors['incorrectField'] = "Member ID/email or password is incorrect";
    }
}
?>

<div class="container">
    <div class="welcome-section">
        <h2>Welcome User!</h2>
    </div>
    <div class="social-media">
        <a href="https://facebook.com" class="fa fa-facebook"></a>
        <a href="https://instagram.com" class="fa fa-instagram"></a>
        <a href="https://youtube.com" class="fa fa-youtube"></a>
    </div>
</div>
<div class="login-form">
    <h2>Login</h2>
    <h2>Welcome User!</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>Member ID/Email: </p>
        <label><input type="text" name="loginInput"></label>

        <p>Password: </p>
        <label><input type="text" name="password"></label>
        <a href='forgotpassword.php'><p class="forgot-pass">Forgot password?</p></a>

        <p class="error-message"><?= isset ($errors['emptyFields']) ? $errors['emptyFields'] : ''?></p>
        <p class="error-message"><?= isset($errors['incorrectField']) ? $errors['incorrectField'] : ''?></p>

        <button type="submit">Sign In</button>
    </form>

    <p class="signup-link">Haven't had an account? <a href="signup.php">Sign Up now!</a></p>
    <a href="../admin_pages/main/login_admin.php"><button>Sign in as admin</button></a>

</div>
</body>
</html>