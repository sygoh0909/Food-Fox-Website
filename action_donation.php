<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions for Donation Page</title>
    <link rel="stylesheet" href="form.css">

    <style>
        form {
            display: flex;
            gap: 20px;
            align-items: start;
            justify-items: center;
        }
    </style>
</head>
<body>
<main>
    <?php
    $conn = connection();
    $donationID = $_GET["donationID"] ?? null;
    $action = $_GET["action"] ?? null;
    $donationDetails = null;

    if ($donationID){
        $sql = "SELECT m.memberName, d.* FROM donations d, members m WHERE d.memberID = m.memberID AND d.donationID = '$donationID'";
        $result = $conn->query($sql);
        $donationDetails = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
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

        if ($action == "view"){
            echo "<h2>View Donation</h2>";
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
            <p>Donation Amount:</p>
            <?php echo $donationDetails['amount'] ?? '';?>
        </div>

        <div class="form-grp">
            <p>Payment Method:</p>
            <?php echo $donationDetails['paymentMethod']?>
        </div>

        <div class="form-grp">
            <p>Donation Date:</p>
            <?php echo date('d-m-Y', strtotime($donationDetails["donationDate"]));?>
        </div>

        <div class="form-grp">
            <p>Feedback:</p>
            <?php echo $donationDetails['feedback'];?>
        </div>

        <?php if ($donationID && $action == "delete"): ?>
        <div class="btn">
            <button type="button" onclick="displayActionPopup()">Delete</button>
            <a href="admin_donations.php"><button type="button">Cancel</button></a>
        </div>

            <div id="action-popup" class="action-popup" style="display:none;">
                <h2>Confirm to delete donation record?</h2>
                <button type="submit" name="confirmAction">Yes</button>
                <button type="button" onclick="closeActionPopup()">No</button>
            </div>

        <?php else: ?>
        <div class="btn">
            <a href="admin_donations.php"><button type="button">Cancel</button></a>
        </div>

        <?php endif; ?>

    </form>
</main>
</body>
<script src="main.js"></script>
</html>