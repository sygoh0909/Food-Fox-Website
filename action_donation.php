<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Donation Page</title>
    <link rel="stylesheet" href="form.css?v=1.0">

    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #F5EEDC;
            margin: 0;
            padding: 0;
        }

        main {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            gap: 20px;
            border: 2px solid #C5B4A5;
        }

        /* Form Layout */
        form {
            display: grid;
            grid-template-columns: 1fr; /* Single column layout */
            gap: 20px;
            align-items: start;
            justify-items: center;
        }

        /* Form Group */
        .form-grp {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            max-width: 600px;
        }

        p {
            margin: 0;
            font-weight: bold;
            color: #444444;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #A89E92;
            border-radius: 8px;
            font-size: 16px;
            background-color: #FFFFFF;
            color: #444444;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #7F6C54;
            box-shadow: 0 0 5px #7F6C54;
        }

        /* Error Message */
        .error-message {
            color: red;
            font-size: 14px;
        }

        /* Buttons */
        button {
            padding: 12px 25px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s ease;
            color: white;
        }

        button[type="submit"],
        button[type="button"] {
            background-color: #7F6C54;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #6B5A48;
            transform: translateY(-2px);
        }

        a button {
            background-color: #A89E92;
            color: white;
        }

        a button:hover {
            background-color: #7F6C54;
            transform: translateY(-2px);
        }

        /* Popup Styling */
        .action-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            z-index: 1000;
            border: 2px solid #C5B4A5;
        }

        .action-popup h2 {
            margin-bottom: 20px;
            color: #444444;
            font-size: 20px;
        }

        .action-popup button {
            margin: 10px;
            padding: 10px 25px;
        }

        .action-popup button:nth-child(1) {
            background-color: #7F6C54;
        }

        .action-popup button:nth-child(1):hover {
            background-color: #6B5A48;
        }

        .action-popup button:nth-child(2) {
            background-color: #D9534F;
        }

        .action-popup button:nth-child(2):hover {
            background-color: #C9302C;
        }

    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $donationID = isset($_GET["donationID"])?$_GET["donationID"]:null;
    $action = isset($_GET["action"])?$_GET["action"]:null;
//    $memberName = isset($_GET["memberName"])?$_GET["memberName"]:null;
    $donationDetails = null;

    if ($donationID){
        $sql = "SELECT m.memberName, d.* FROM donations d, members m WHERE d.memberID = m.memberID AND d.donationID = '$donationID'";
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

        <div class="form-grp">
            <p>Member Name:</p>
            <?php echo $donationDetails['memberName']; ?>
        </div>

        <div class="form-grp">
            <p>Donation Amount:</p> <!--can edit amount a bit weird-->
            <label><input type="text" name="amount" value="<?php echo isset($donationDetails['amount'])?$donationDetails['amount']:'';?>"></label>
            <p class="error-message"><?= isset($errors['amount']) ? $errors['amount'] :''?></p>
        </div>

        <div class="form-grp">
            <p>Donation Date:</p>
            <?php echo date('d-m-Y', strtotime($donationDetails["donationDate"]));?>
        </div>

        <div class="form-grp">
            <p>Feedback:</p>
            <label><input type="text" name="feedback" value="<?php echo $donationDetails['feedback'];?>"></label>
        </div>

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