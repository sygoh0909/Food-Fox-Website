<html>
<head>
    <title>Connecting MySQLi Server</title>
</head>

<body>
<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (! $conn){
    echo 'Connected failure<br>';
}
echo 'Connected successfully<br>';
$sql = "CREATE DATABASE foodfoxdb";

if (mysqli_query($conn, $sql)){
    echo "Database created successfully";
} else {
    echo "Error creating database: " . mysqli_error($conn);

}
mysqli_close($conn);
?>
</body>
</html>