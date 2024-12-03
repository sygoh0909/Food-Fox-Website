<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css%20&%20js/login.css">
    <style>
        .required{
            color: red;
            font-weight: bold;
        }
        p.error{
            color: red;
            visibility: hidden;
        }
        p.error-message{
            color: red;
        }
    </style>
</head>
<body>

<?php
include('cookie.php');

$conn = connection();

if($conn->connect_error){
    die ("Connection failed ".$conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //retrieve and assign to a variable
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //regular expressions
    $passwordPattern = '/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/';

    //validation
    $errors = [];
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    else {
        $nameParts = explode(" ", $name);
        if (count($nameParts) < 2) {
            $errors['name'] = "Please enter your full name (first and last name)";
        }
    }
    if (empty($email)) {
        $errors['email'] = "Email is required";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

        <!--name-->
        <p><span class="required">* </span>Name:</p>
        <label><input type="text" id="name" name="name" oninput="validateName()"></label>
        <p class="error" id="name-error"></p>
        <p class="error-message"><?= isset ($errors['name']) ? $errors['name'] :''?></p>

        <!--email-->
        <p><span class="required">* </span>Email Address:</p>
        <label><input type="text" id="email" name="email" oninput="validateEmail()"></label>
        <p class="error" id="email-error"></p>
        <p class="error-message"><?= isset ($errors['email']) ? $errors['email'] : ''?></p>

        <!--password-->
        <p><span class="required">* </span>Password:</p>
        <label>
            <input type="password" id="password" name="password" oninput="validatePassword()">
        </label>
        <p class="error" id="password-error"></p>
        <p class="error-message"><?= isset ($errors['password']) ? $errors['password'] : ''?></p>

        <!--confirm pass-->
        <p><span class="required">* </span>Confirm your password:</p>
        <label>
            <input type="password" id="confirmPassword" name="confirmPassword" oninput="validatePassword()">
        </label>
        <p class="error" id="confirm-password-error"></p> <!--hide php error when javascript error occurs-->
        <p class="error-message"><?= isset ($errors['confirmPassword']) ? $errors['confirmPassword'] : ''?></p>

        <button type="submit">Sign Up</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Sign in now!</a></p>

</div>
<script>
    document.getElementById('name').addEventListener('input', validateName);
    document.getElementById('email').addEventListener('input', validateEmail);
    document.getElementById('password').addEventListener('input', validatePassword);
    document.getElementById('confirmPassword').addEventListener('input', validatePassword)

    function validateName() {
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('name-error');

        const nameValue = nameInput.value.trim();
        const nameParts = nameValue.split(/\s+/); //split by whitespaces

        if (nameValue === ''){
            nameError.textContent = "Name is required";
            nameError.style.visibility = 'visible';
        }
        else if (nameParts.length < 2){
            nameError.textContent = "Please enter your full name (first and last name)"
            nameError.style.visibility = 'visible';
        }else{
            nameError.style.visibility = 'hidden';
        }
    }

    function validateEmail(){
        const emailInput = document.getElementById('email').value.trim();
        const emailError = document.getElementById('email-error');
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (emailInput === ''){
            emailError.textContent = "Email is required";
            emailError.style.visibility = 'visible';
        }
        else if (!emailPattern.test(emailInput)){
            emailError.textContent = "Invalid email format";
            emailError.style.visibility = 'visible';
        }
        else{
            emailError.style.display = 'hidden';
        }
    }

    function validatePassword(){
        const passwordInput = document.getElementById('password').value.trim();
        const passwordError = document.getElementById('password-error');
        const passwordPattern = /^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/;

        const confirmPasswordInput = document.getElementById('confirmPassword').value.trim();
        const confirmPasswordError = document.getElementById('confirm-password-error');

        if (passwordInput === ''){
            passwordError.textContent = "Password is required";
            passwordError.style.visibility = 'visible';
        }
        else if (!passwordPattern.test(passwordInput)){
            passwordError.textContent = "Password must at least be 8 characters long, with at least one letter and one number.";
            passwordError.style.visibility = 'visible';
        }
        else{
            passwordError.style.visibility = 'hidden';
        }

        if (confirmPasswordInput === ''){
            confirmPasswordError.textContent = "Confirm password is required";
            confirmPasswordError.style.visibility = 'visible';
        }
        else if (passwordInput !== confirmPasswordInput){
            confirmPasswordError.textContent = "Password do not match";
            confirmPasswordError.style.visibility = 'visible';
        }
        else{
            confirmPasswordError.style.visibility = 'hidden';
        }
    }
</script>
</body>
</html>