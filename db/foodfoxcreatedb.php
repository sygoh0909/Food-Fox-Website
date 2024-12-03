<html>
<head>
    <title>Connecting MySQLi Server</title>
</head>

<body>
<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'foodfoxdb';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (! $conn){
    echo 'Connected failure<br>';
}
echo 'Connected successfully<br>';

$db_check_query = "SHOW DATABASES LIKE '$dbname'";
$db_check_result = mysqli_query($conn, $db_check_query);

if (mysqli_num_rows($db_check_result) > 0){
    echo "Database '$dbname' already exists.";
}
else{
    $sql = "CREATE DATABASE '$dbname'";
    if (mysqli_query($conn, $sql)){
        echo "Database '$dbname' has been created successfully.";
    }
    else{
        echo "Error creating database: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
</body>
</html>