<?php

include ('../db/db_conn.php');

$fundraisingGoal = 10000;
$maxMealsProvided = 1000;
$maxPeopleSupported = 200;

$conn = connection();

$sql = "SELECT SUM(amount) AS total_donations FROM donations";
$result = $conn->query($sql);
$totalDonations = ($result->num_rows > 0) ? $result->fetch_assoc()['total_donations'] : 0;

$mealsProvided = floor($totalDonations / 10);
$mealsProvidedPercentage = floor(min(100, ($mealsProvided / $maxMealsProvided) * 100)); //cap at 100%

$count = "SELECT COUNT(*) AS total_people FROM donations";
$result = $conn->query($count);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

$peopleSupported = $row['total_people'];
$peopleSupportedPercentage = floor(min(100, ($peopleSupported / $maxPeopleSupported) * 100));

if (isset($_GET['action']) && $_GET['action'] == 'getProgress') {
    $progressPercentage = floor(min(100,($totalDonations / $fundraisingGoal) * 100));
}

if (isset($_GET['fetchData']) && $_GET['fetchData'] === 'true') {
    $data = [
        'mealsPercentage' => $mealsProvidedPercentage,
        'peoplePercentage' => $peopleSupportedPercentage
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

