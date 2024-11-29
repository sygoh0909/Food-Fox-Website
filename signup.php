<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css">
    <style>
        p.error-message{
            color: red;
        }
    </style>
</head>
<body>

<?php
include('cookie.php');
$visitCount = cookie(); //dunno need or not

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "foodfoxdb";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die ("Connection failed ".$conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve and assign to a variable
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //regular expressions
    $namePattern = '/^[a-zA-Z]+$/';
    $emailPattern = '/^[\w\-\.]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/';
    $passwordPattern = '/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/';

    //validation
    $errors = [];
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    elseif (!preg_match($namePattern, $name)) {
        $errors['name'] = "Name can contain only letters and spaces.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required";
    }
    elseif (!preg_match($emailPattern, $email)) {
        $errors['email'] = "Enter a valid email address.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }
    elseif (!preg_match($passwordPattern, $password)) {
        $errors['password'] = "Password must at least be 8 characters long, with at least one letter and one number.";
    }
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }
    if (empty($confirmPassword)) {
        $errors['confirmPassword'] = "Confirm Password is required";
    }
    //if no errors, save to database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO members (memberName, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        if (mysqli_query($conn, $query)) {
            $memberId = mysqli_insert_id($conn); // Get the new member ID
            $_SESSION['memberID'] = $memberId;
            echo "<p>Registration successful. Your Member ID is: $memberId</p>";
            echo "<form method='POST' action='mainpage.php'><button type='submit'>OK</button></form>";
        }
        else{
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }
//        else{
//            foreach ($errors as $error) {
//                echo "<p style='color:red;'>$error</p>";
//            }
//        }
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
<div class="sign-up-form">
    <h2>Sign Up</h2>
    <h2>Welcome User!</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>Name: </p>
        <label><input type="text" name="name"</label>
        <p class="error-message"><?= isset ($errors['name']) ? $errors['name'] : ''?></p>

        <p>Email Address: </p>
        <label><input type="text" name="email"></label>
        <p class="error-message"><?= isset ($errors['email']) ? $errors['name'] : ''?></p>

        <p>Password: </p>
        <label><input type="text" name="password"></label>
        <p class="error-message"><?= isset ($errors['password']) ? $errors['password'] : ''?></p>

        <p>Confirm your password: </p>
        <label><input type="text" name="confirmPassword"></label>
        <p class="error-message"><?= isset ($errors['confirmPassword']) ? $errors['confirmPassword'] : ''?></p>

        <button type="submit">Sign Up</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Sign in now!</a></p>

</div>
</body>
</html>