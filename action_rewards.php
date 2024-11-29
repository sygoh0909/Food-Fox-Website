<?php
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
    <title>Edit/Delete/Add Rewards Page</title>

    <style>
        .image {
            width: 120px;
            height: 100px;
            object-fit: cover;
            border: 3px solid #C5B4A5;
            margin-bottom: 10px;
            border-radius: 10px;
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
            object-fit: cover;
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

    if ($rewardID) {
        $sql = "SELECT * FROM rewards WHERE rewardID = '$rewardID'";
        $result = mysqli_query($conn, $sql);
        $rewardData = mysqli_fetch_assoc($result);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $rewardID = $_POST['rewardID'];
            $rewardName = $_POST['rewardName'];
            $pointsNeeded = $_POST['pointsNeeded'];
            $rewardPicPath = $rewardData['rewardPic'];

            if (isset($_FILES['rewardPic']) && $_FILES['rewardPic']['error'] == 0) {
                $target_dir = "uploads/";
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
                    $sql = "INSERT INTO rewards WHERE rewardName = '$rewardName', pointsNeeded = '$pointsNeeded', rewardPic = '$rewardPicPath'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Reward added successfully'); window.location.href = 'admin_rewards.php';</script>";
                    }
                }
                else{
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
        if ($action == "edit"){
            echo "<h2>Edit Reward</h2>";
        }
        elseif ($action == "delete"){
            echo "<h2>Delete Reward</h2>";
        }
        elseif ($action == "add"){
            echo "<h2>Add Reward</h2>";
        }
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        <!--<p>Reward ID:</p>-->

        <p>Reward Picture:</p>
        <div class="reward-picture">
            <img src="<?php echo $rewardData['rewardPic']; ?>" alt="Reward Picture" id="rewardPic" class="image">
        </div>
        <input type="file" name="rewardPic" id="uploadPic" accept="image/*" onchange="previewRewardPic()">

        <p>Reward Name:</p>
        <label><input type="text" name="rewardName" value="<?php echo isset ($rewardData['rewardName']) ? $rewardData['rewardName'] : '';?>"></label>

        <p>Points Needed:</p>
        <label><input type="text" name="pointsNeeded" value="<?php echo isset ($rewardData['pointsNeeded']) ? $rewardData['pointsNeeded'] : '';?>"</label>

        <div class="button">
            <?php
            $buttonText = '';

            if ($rewardID && $action == "edit"){
                $buttonText = "Update Event";
            }
            elseif ($rewardID && $action == "delete"){
                $buttonText = "Delete Event";
            }
            elseif ($rewardID && $action == "add"){
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
                elseif ($rewardID && $action == "add"){
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