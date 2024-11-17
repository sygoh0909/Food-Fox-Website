<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        body{
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #5C4033;
        }
        .social-media{
            display: flex;
            gap: 10px;
        }
        .social-media a{
            color: white;
            font-size: 20px;
            text-decoration: none;
        }
        .nav-links{
            display: flex;
            gap: 30px;
        }
        .roundButton{
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        .main{
            color: white;
        }
        .login{
            background-color: white;
            color: #d3a029;
            font-size: smaller;
        }
        .signup{
            background-color: #d3a029;
            color: white;
            font-size: smaller;
        }
        .login:hover, .signup:hover{
            transform: translateY(-2px);
        }
        .progress-container {
            width: 100%;
            height: 30px;
            background-color: #ccc;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php
$fundraisingGoal = 1000;
$conn = connection();

$sql = "SELECT SUM(amount) AS total_donations FROM donations";
$result = $conn->query($sql);
$totalDonations = ($result->num_rows>0) ? $result->fetch_assoc()['total_donations'] : 0;

if (isset($_GET['action']) && $_GET['action'] == 'getProgress') {
    $progressPercentage = $totalDonations / $fundraisingGoal * 100;
    echo json_encode([
        'totalDonations' => $totalDonations,
        'progressPercentage' => $progressPercentage
    ]);
    exit;
}

?>
<header>
    <nav>
        <div class="navbar">
            <div class="social-media">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>

            <div class="main-links">
                <a href="mainpage.php" class="roundButton main">Home</a>
                <a href="events.php" class="roundButton main">Events</a>
                <a href="donations.php" class="roundButton main">Donation</a>
                <a href="contact.php" class="roundButton main">Contact</a>
            </div>

            <div class="nav-links">
                <?php
                loginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <h2>Our Fundraising Goal</h2>
    <p id="progressText">Raised: $<?=$totalDonations?> / $<?=$fundraisingGoal?></p>
    <div class="progress-container">
        <div id="progress-bar" class="progress-bar" style="width:<?=($totalDonations/$fundraisingGoal)*100?>%;">
            <?=round($totalDonations/$fundraisingGoal)*100?>%
        </div>
    </div>


    <h2>Make a Donation</h2>
    <?php
    $conn = connection();
    $memberID = isset($_SESSION['memberID']) ? $_SESSION['memberID'] : "";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $amount = $_POST['amount'];

        $errors = [];

        if (empty($amount)) {
            $errors[] = 'You must enter an amount.';
        }

        if (empty($errors)) {
            $sql = "INSERT INTO donations (memberID, amount) VALUES ('$memberID', '$amount')";
            if ($conn->query($sql) === TRUE) {
                echo "Donated successfully";
            }
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <label><input type="text" name="amount" placeholder="Enter the amount you want to donate"></label>
        <button>Donate</button>
        <!--after donate successfully, ask if they wan to leave feedback-->
    </form>

    <h2>Our Collective Impact</h2>
    <!--a chart displaying-->

    <h2>Community Feedback</h2>
    <?php
    $conn = connection();

    $sql = "SELECT feedback FROM donations";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo $row["feedback"];
        }
    }
    ?>
</main>
</body>
<script>
    function updateProgress(){
        fetch (window.location.href + '?action=getProgress')
            .then(response => response.json())
            .then(data => {
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progressText');

                progressBar.style.width = `${data.progressPercentage}%`;
                progressBar.textContent = `${Math.round(data.progressPercentage)}%`;

                progressText.textContent = `Raised: $${data.totalDonations} / $<?= $fundraisingGoal ?>`;
            })
    }
    setInterval(updateProgress, 5000) //refresh every 5 seconds
</script>
</html>