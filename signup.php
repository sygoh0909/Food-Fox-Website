<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
        }

    </style>
</head>
<body>

<?php
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
        $errors[] = "Name is required";
    }
    elseif (!preg_match($namePattern, $name)) {
        $errors[] = "Name can contain only letters and spaces.";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    elseif (!preg_match($emailPattern, $email)) {
        $errors[] = "Enter a valid email address.";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    elseif (!preg_match($passwordPattern, $password)) {
        $errors[] = "Password must at least be 8 characters long, with at least one letter and one number.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    //if no errors, save to database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO members (memberName, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        if (mysqli_query($conn, $query)) {
            $memberId = mysqli_insert_id($conn); // Get the new member ID
            echo "<p>Registration successful. Your Member ID is: $memberId</p>";
        }
        else{
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }
        else{
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
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
<div class="sign-up-form">
    <h2>Sign Up</h2>
    <h2>Welcome User!</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>Name: </p>
        <label><input type="text" name="name"</label>

        <p>Email Address: </p>
        <label><input type="text" name="email"></label>

        <p>Password: </p>
        <label><input type="text" name="password"></label>

        <p>Confirm your password: </p>
        <label><input type="text" name="confirmPassword"></label>

        <button type="submit">Sign Up</button>
    </form>

    <p class="signin-link">Already have an account? <a href="signin.php">Sign in now!</a></p>

</div>
</body>
</html>