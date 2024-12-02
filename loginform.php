<?php
include('cookie.php');
$conn = connection();

$memberID = isset($_GET['memberID']) ? $_GET['memberID'] :'';
$eventID = isset($_GET['eventID']) ? $_GET['eventID'] : '';

if ($memberID && $eventID){
    $sql = "SELECT eventName FROM events WHERE eventID = $eventID";
    $result = mysqli_query($conn, $sql);
    $event = mysqli_fetch_assoc($result);

    if($event){
        $eventName = $event['eventName'];
        echo "<h3>Marked attendance for event: $eventName";

        $updateSql = "UPDATE registrations SET attendance = 1 WHERE eventID = $eventID AND memberID = $memberID";
        if (mysqli_query($conn, $updateSql)){
            echo "<script>alert('Your attendance has been successfully marked for this event!')</script>";
            //points?
        }
        else{
            echo "<script>alert('Failed to mark attendance. Please try again.')</script>";
        }
    }
    else{
        echo "Failed to mark attendance. Event not found.";
        exit();
    }
}
?>
