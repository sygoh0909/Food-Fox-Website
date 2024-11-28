<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
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
    $action = isset($_GET["action"])?$_GET["action"]:null;
    $memberName = isset($_GET["memberName"])?$_GET["memberName"]:null;
    $donationDetails = null;

    if ($donationID){
        $sql = "SELECT * FROM `donations` WHERE `donationID` = $donationID";
        $result = $conn->query($sql);
        $donationDetails = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmAction"])){
            $amount = $_POST["amount"];
            $feedback = $_POST["feedback"];

            $errors = [];

            if (empty($amount)){
                $errors['amount'] = "Amount is required";
            }

            if (empty($errors)){
                if ($action == "edit"){ //confirm edit and delete b4 sending to database
                    $sql = "UPDATE donations SET amount = '$amount', feedback = '$feedback' WHERE donationID = $donationID";
                    if ($conn->query($sql) === TRUE){
                        echo "<script>
                              alert('Donation Info Updated Successfully');
                              window.location.href = 'admin_donations.php';
                        </script>";
                        exit;
                    }
                }
                if ($action == "delete"){
                    $sql = "DELETE FROM `donations` WHERE `donationID` = $donationID";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>
                              alert('Donation Info Deleted Successfully');
                              window.location.href = 'admin_donations.php';
                        </script>";
                        exit;
                    }
                }
            }
//            foreach ($errors as $error) {
//                echo "<p style='color:red;'>$error</p>";
//            }
        }
        if ($action == "edit"){
            echo "<h2>Update Donation</h2>";
        }
        elseif ($action == "delete"){
            echo "<h2>Delete Donation</h2>";
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <p>Donation ID</p>
        <?php echo str_repeat('*', strlen($donationDetails["donationID"]));?>

        <p>Member Name</p>
        <?php echo $memberName; ?> <!--show member name-->

        <p>Donation Amount</p> <!--can edit amount a bit weird-->
        <label><input type="text" name="amount" value="<?php echo isset($donationDetails['amount'])?$donationDetails['amount']:'';?>"></label>
        <p class="error-message"><?= isset($errors['amount']) ? $errors['amount'] :''?></p>

        <p>Donation Date</p>
        <?php echo $donationDetails["donationDate"]; ?>

        <p>Feedback</p>
        <label><input type="text" name="feedback" value="<?php echo $donationDetails['feedback'];?>"</label>

        <button type="button" onclick="displayActionPopup()"><?php echo $donationID && $action=="edit"?'Update donation details':'Delete donation details'?></button>
        <a href="admin_donations.php"><button type="button">Cancel</button></a>


        <div id="action-popup" class="action-popup" style="display:none;">
            <h2><?php echo $donationID && $action=="edit"?'Confirm to update donation details?':'Confirm to delete donation details?'?></h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </div>
    </form>
</main>
</body>
<script src="main.js"></script>
</html>