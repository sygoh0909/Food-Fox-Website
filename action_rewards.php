<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions for Rewards Page</title>
    <link rel="stylesheet" href="form.css">

    <style>
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
    </style>

</head>
<body>
<main>
    <?php
    $conn = connection();
    $rewardID = $_GET['rewardID'] ?? '';
    $action = $_GET['action'] ?? '';
    $rewardData = null;

    if ($rewardID && ($action == "edit" || $action == "delete")) {
        $sql = "SELECT * FROM rewards WHERE rewardID = '$rewardID'";
        $result = mysqli_query($conn, $sql);
        $rewardData = mysqli_fetch_assoc($result);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rewardName = $_POST['rewardName'];
        $pointsNeeded = $_POST['pointsNeeded'];
        $rewardPicPath = $rewardData['rewardPic'] ?? '';

        if (isset($_FILES['rewardPic']) && $_FILES['rewardPic']['error'] == 0) {
            $target_dir = "../uploads/";
            $rewardPicPath = $target_dir . basename($_FILES['rewardPic']['name']);
            move_uploaded_file($_FILES['rewardPic']['tmp_name'], $rewardPicPath);
        }

        $errors = array();

        if (empty($rewardName)) {
            $errors['rewardName'] = "Reward Name is required";
        }
        if (empty($pointsNeeded)) {
            $errors['points'] = "Points needed is required";
        }
        elseif (!filter_var($pointsNeeded, FILTER_VALIDATE_INT) || $pointsNeeded < 0) {
            $errors['points'] = "Points needed must be a positive integer";
        }

        if ($action == "edit" || $action == "add"){
            if (empty($errors)){
                $sql = "UPDATE rewards SET rewardName = '$rewardName', pointsNeeded = '$pointsNeeded', rewardPic = '$rewardPicPath' WHERE rewardID = '$rewardID'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Reward updated successfully'); window.location.href = 'admin_rewards.php';</script>')";
                }
                if ($action == "add"){
                    $sql = "INSERT INTO rewards (rewardName, pointsNeeded, rewardPic) VALUES ('$rewardName','$pointsNeeded','$rewardPicPath')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Reward added successfully'); window.location.href = 'admin_rewards.php';</script>";
                    }
                }
            }
        }
        elseif ($action == "delete"){
            $sql = "DELETE FROM rewards WHERE rewardID = '$rewardID'";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Reward deleted successfully'); window.location.href = 'admin_rewards.php';</script>";
            }
        }
    }
    ?>
    <h2>
        <?php
        if ($action == "edit"){
            echo "<h2>Edit Reward</h2>";
        }
        elseif ($action == "delete"){
            echo "<h2>Delete Reward</h2>";
        }
        elseif ($action == "add"){
            echo "<h2>Add Reward</h2>";
        }
        ?>
    </h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-grp">
            <p>Reward Picture:</p>
            <div class="reward-picture">
                <img src="<?php echo $rewardData['rewardPic']; ?>" alt="Reward Picture" id="rewardPic" class="image">
            </div>
            <input type="file" name="rewardPic" id="uploadPic" accept="image/*" onchange="previewRewardPic()">
        </div>

        <div class="form-grp">
            <p>Reward Name:</p>
            <label><input type="text" name="rewardName" value="<?php echo $rewardData['rewardName'] ?? '';?>"></label>
            <p class="error-message"><?= $errors['rewardName'] ?? '' ?></p>
        </div>

        <div class="form-grp">
            <p>Points Needed:</p>
            <label><input type="text" name="pointsNeeded" value="<?php echo $rewardData['pointsNeeded'] ?? '';?>"></label>
            <p class="error-message"><?= $errors['points'] ?? '' ?></p>
        </div>

        <div class="button">
            <?php
            $buttonText = '';

            if ($rewardID && $action == "edit"){
                $buttonText = "Update Reward";
            }
            elseif ($rewardID && $action == "delete"){
                $buttonText = "Delete Reward";
            }
            elseif ($action == "add"){
                $buttonText = "Add Reward";
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