<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
$conn = connection();
$token = isset($_GET['token']) ? $_GET['token'] : '';
$resetData = null;

if ($token){
    $sql = "SELECT * FROM passwordresets WHERE token = '$token' AND expiry >" .time();
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0){
        $resetData = mysqli_fetch_assoc($result);

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            $errors = array();

            if (!preg_match('/^(?=.*[a-zA-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
                $errors['newPassword'] = "Password must at least be 8 characters long, with at least one letter and one number.";
            }
            if ($newPassword !== $confirmPassword) {
                $errors['confirmPassword'] = "Passwords do not match.";
            }

            if (empty($errors)){
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $memberID = $resetData['memberID'];
                $updateSql = "UPDATE members SET password = '$hashedPassword' WHERE memberID = $memberID";

                if (mysqli_query($conn, $updateSql)) {
                    $deleteTokenSql = "DELETE FROM passwordresets WHERE token = '$token'";
                    mysqli_query($conn, $deleteTokenSql);
                    echo "<script>alert('Your password has been successfully changed. Please proceed to login.'); window.location.href = 'login.php';</script>";
                }
            }
        }
    }
    else{
        echo "<script>alert('Invalid or expired page')</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>
</head>
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

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 400px;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }

    label {
        font-size: 16px;
        margin-bottom: 8px;
        text-align: left;
    }

    input[type="password"] {
        font-size: 16px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
    }

    button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .required {
        color: red;
        font-weight: bold;
    }

    p {
        font-size: 14px;
        color: red;
        margin-top: 5px;
    }

    form .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

</style>
<body>
<h1>Reset Password</h1>
<form method="POST" action="">
    <label>
        <span class="required">*</span>New Password:
        <input type="password" name="newPassword" placeholder="Enter your new password" required>
        <p class="error-message""><?= isset($errors['newPassword']) ? $errors['newPassword'] : '' ?></p>
    </label>
    <br>
    <label>
        <span class="required">*</span>Confirm Password:
        <input type="password" name="confirmPassword" placeholder="Confirm your new password" required>
        <p class="error-message"><?= isset($errors['confirmPassword']) ? $errors['confirmPassword'] : '' ?></p>
    </label>
    <br>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>

