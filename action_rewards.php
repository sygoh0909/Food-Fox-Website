<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete/Add Rewards Page</title>
    <link rel="stylesheet" href="form.css?v=1.0">

    <style>
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

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            align-items: start;
            justify-items: center;
        }

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

        .reward-picture {
            width: 140px;
            height: 120px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #666;
            margin-bottom: 20px;
            border: 1px dashed #A89E92;
        }

        .reward-picture .image {
            width: 120px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

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
    $rewardID = isset($_GET['rewardID']) ? $_GET['rewardID'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $rewardData = null;

    if ($rewardID && ($action == "edit" || $action == "delete")) {
        $sql = "SELECT * FROM rewards WHERE rewardID = '$rewardID'";
        $result = mysqli_query($conn, $sql);
        $rewardData = mysqli_fetch_assoc($result);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $rewardID = $_POST['rewardID'];
        $rewardName = $_POST['rewardName'];
        $pointsNeeded = $_POST['pointsNeeded'];
        $rewardPicPath = $rewardData['rewardPic'];

        if (isset($_FILES['rewardPic']) && $_FILES['rewardPic']['error'] == 0) {
            $target_dir = "../uploads/";
            $rewardPicPath = $target_dir . basename($_FILES['rewardPic']['name']);
            move_uploaded_file($_FILES['rewardPic']['tmp_name'], $rewardPicPath);
        }

        $errors = array();

        if (empty($rewardName)) {
            $errors[] = "Reward Name is required";
        }
        if (empty($pointsNeeded)) {
            $errors[] = "Points needed is required";
        }
        //elseif points needed is not int num

        if (empty($errors)){
            if ($action == "edit"){
                $sql = "UPDATE rewards SET rewardName = '$rewardName', pointsNeeded = '$pointsNeeded', rewardPic = '$rewardPicPath' WHERE rewardID = '$rewardID'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Reward updated successfully'); window.location.href = 'admin_rewards.php';</script>')";
                }
            }
            elseif ($action == "delete"){
                $sql = "DELETE FROM rewards WHERE rewardID = '$rewardID'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Reward deleted successfully'); window.location.href = 'admin_rewards.php';</script>";
                }
            }
            elseif ($action == "add"){
                $sql = "INSERT INTO rewards (rewardName, pointsNeeded, rewardPic) VALUES ('$rewardName','$pointsNeeded','$rewardPicPath')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Reward added successfully'); window.location.href = 'admin_rewards.php';</script>";
                }
            }
            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    ?>
    <h2><?php
        if ($action == "edit"){
            echo "<h2>Edit Reward</h2>";
        }
        elseif ($action == "delete"){
            echo "<h2>Delete Reward</h2>";
        }
        elseif ($action == "add"){
            echo "<h2>Add Reward</h2>";
        }?></h2>
    <form method="post" enctype="multipart/form-data">
        <!--<p>Reward ID:</p>-->

        <div class="form-grp">
            <p>Reward Picture:</p>
            <div class="reward-picture">
                <img src="<?php echo $rewardData['rewardPic']; ?>" alt="Reward Picture" id="rewardPic" class="image">
            </div>
            <input type="file" name="rewardPic" id="uploadPic" accept="image/*" onchange="previewRewardPic()">
        </div>

        <div class="form-grp">
            <p>Reward Name:</p>
            <label><input type="text" name="rewardName" value="<?php echo isset ($rewardData['rewardName']) ? $rewardData['rewardName'] : '';?>"></label>
        </div>

        <div class="form-grp">
            <p>Points Needed:</p>
            <label><input type="text" name="pointsNeeded" value="<?php echo isset ($rewardData['pointsNeeded']) ? $rewardData['pointsNeeded'] : '';?>"></label>
        </div>

        <div class="button">
            <?php
            $buttonText = '';

            if ($rewardID && $action == "edit"){
                $buttonText = "Update Event";
            }
            elseif ($rewardID && $action == "delete"){
                $buttonText = "Delete Event";
            }
            elseif ($action == "add"){
                $buttonText = "Add Event";
            }
            echo "<button type='button' onclick='displayActionPopup()'>{$buttonText}</button>";
            ?>
            <a href="admin_rewards.php"><button type="button">Cancel</button></a>
        </div>

        <div id="action-popup" class="action-popup" style="display:none;">
            <h2><?php
                $buttonText = '';

                if ($rewardID && $action == "edit"){
                    $buttonText = "Confirm to update reward info?";
                }
                elseif ($rewardID && $action == "delete"){
                    $buttonText = "Confirm to delete reward info?";
                }
                elseif ($action == "add"){
                    $buttonText = "Confirm to add reward?";
                }
                echo "{$buttonText}";
                ?>
            </h2>
            <button type="submit" name="confirmAction">Yes</button>
            <button type="button" onclick="closeActionPopup()">No</button>
        </div>

    </form>
</main>
<script src="main.js"></script>
<script>
    function previewRewardPic(){
        const fileInput = document.getElementById('uploadPic');
        const eventImg = document.getElementById('rewardPic');

        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                eventImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>