<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Timeout</title>
    <style>
        .timeout-container {
            text-align: center;
            padding: 50px;
            margin: auto;
            max-width: 500px;
            background-color: #f8f5f2;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .timeout-container h1 {
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        .timeout-container p {
            color: #7F6C54;
            margin-bottom: 30px;
        }

        .timeout-container .button {
            background-color: #d3a029;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .timeout-container .button:hover {
            background-color: #7F6C54;
        }

    </style>
</head>
<body>
<div class="timeout-container">
    <h1>Session Timeout</h1>
    <p>Your session has expired due to inactivity. Please log in again to continue.</p>
    <a href="login.php" class="button">Log In</a>
</div>
</body>
</html>
