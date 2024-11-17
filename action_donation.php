<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Donation Page</title>

    <style>
        body {
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
        }
        main{
            background-color: #C5B4A5;
            padding: 20px 40px;
            border-radius: 20px;
        }
        h2{
            text-align: center;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $donationID = isset($_GET["donationID"])?$_GET["donationID"]:null;
    $donationDetails = null;

    if ($donationID){
        $sql = "SELECT * FROM `donations` WHERE `donationID` = $donationID";
        $result = $conn->query($sql);
        $donationDetails = $result->fetch_assoc();
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <p>Donation ID</p>
        <?php echo $donationDetails["donationID"]; ?> <!--should donation id and member id can edit by admin?-->

        <p>Member ID</p>
        <?php echo $donationDetails["memberID"]; ?>

        <p>Donation Amount</p>
        <label><input type="text" name="amount" value="<?php echo isset($donationDetails['amount'])?$donationDetails['amount']:'';?>"></label>

        <p>Donation Date</p>
        <?php echo $donationDetails["donationDate"]; ?>

        <p>Feedback</p>

    </form>
</main>
</body>
</html>