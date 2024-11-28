<?php
ob_start();
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main{
            color: white;
        }
        h2 {
            color: #5C4033;
            margin-bottom: 10px;
        }
        .progress-container {
            width: 100%;
            height: 30px;
            border-radius: 25px;
            background-color: #C5B4A5;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .progress-bar {
            height: 100%;
            background-color: #d3a029;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
            border-radius: 25px;
            transition: width 0.5s ease-out;
        }

        .impact-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .chart-container {
            width: 500px;
            height: 300px;
        }

        .info-container {
            width: 35%;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .info-container p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .donations-buttons {
            margin-bottom: 20px;
        }

        .donation-btn {
            margin: 5px;
            padding: 10px 20px;
            background-color: #d3a029;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .donation-btn:hover {
            background-color: #5c4033;
        }

        #donation-input {
            width: 250px;
            padding: 10px;
            border: 1px solid #7F6C54;
            border-radius: 5px;
            text-align: center;
        }
        .donate-submit{
            margin: 5px;
            padding: 10px 20px;
            background-color: #d3a029;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .donations-container{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .donation-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .donation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .donation-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .donation-card h3 {
            font-size: 1.5rem;
            color: #5C4033;
            margin: 15px 10px;
        }

        .donation-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f4f4f4;
            padding: 20px;
            border: 1px solid #7F6C54;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        .donation-popup h2 {
            color: #7F6C54;
            font-family: 'Arial', sans-serif;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .donation-popup button {
            background-color: #7F6C54;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            transition: background-color 0.3s ease;
            margin: 0 5px;
        }
    </style>
</head>
<body>
<?php
$fundraisingGoal = 5000;
$maxMealsProvided = 10000;
$maxPeopleSupported = 5000;
$conn = connection();

$sql = "SELECT SUM(amount) AS total_donations FROM donations";
$result = $conn->query($sql);
$totalDonations = ($result->num_rows>0) ? $result->fetch_assoc()['total_donations'] : 0;

$mealsProvided = floor($totalDonations / 10);
$mealsProvidedPercentage = min(100, ($mealsProvided / $maxMealsProvided) * 100); //cap at 100%

$count = "SELECT COUNT(*) AS total_people FROM donations";
$result = $conn->query($count);
if ($result->num_rows > 0) {
    $row  = $result->fetch_assoc();
}

$peopleSupported = $row['total_people'];
$peopleSupportedPercentage = min(100, ($peopleSupported / $maxPeopleSupported) * 100);

if (isset($_GET['action']) && $_GET['action'] == 'getProgress') {
    $progressPercentage = min(100, ($totalDonations / $fundraisingGoal * 100));

//    if ($progressPercentage > 100) {
//        $progressPercentage = 100;
//    }

    $data = [
        'totalDonations' => $totalDonations,
        'progressPercentage' => $progressPercentage,
        'mealsPercentage' => $mealsProvidedPercentage,
        'peoplePercentage' => $peopleSupportedPercentage
    ];
    echo json_encode($data);
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
        <div id="progress-bar" class="progress-bar" style="width:<?= round(($totalDonations / $fundraisingGoal) * 100) ?>%;">
            <?= floor(($totalDonations / $fundraisingGoal) * 100) ?>%
        </div>
    </div>


    <h2>Make a Donation</h2>
    <?php
    $conn = connection();
    $memberID = isset($_SESSION['memberID']) ? $_SESSION['memberID'] : 0;
    //if member only can donate

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmDonate'])) {
        $amount = $_POST['confirm-amount'];
        $paymentMethod = $_POST['payment-method'];

        $errors = [];

        if (empty($amount)) {
            $errors[] = 'You must enter an amount.';
        }
        elseif (!preg_match('/^[1-9][0-9]*$/', $amount)) {
            $errors[] = 'The amount must be a positive number.';
        }

        if (empty($errors)) {
            $sql = "INSERT INTO donations (memberID, amount, paymentMethod) VALUES ('$memberID', '$amount', '$paymentMethod')";
            if ($conn->query($sql) === TRUE) {

                //points get from donating
                $pointsEarned = floor($amount / 10);
                $updateSql = "UPDATE members SET points = points + $pointsEarned WHERE memberID = '$memberID'";
                $conn->query($updateSql);

                echo "<script>alert('Thank you for your donation!'); window.location.href = window.location.href</script>"; //refresh page so that member points refreshed also
                //do u wan to leave a feedback?
            }
            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="donations-buttons">
            <button type="button" class="donation-btn" name="amount" value="10">10</button>
            <button type="button" class="donation-btn" name="amount" value="20">20</button>
            <button type="button" class="donation-btn" name="amount" value="50">50</button>
            <button type="button" class="donation-btn" name="amount" value="100">100</button>
        </div>
        <label><input id="donation-input" type="text" name="amount" placeholder="Enter the amount you want to donate..."></label>
        <button type="button" class="donate-submit" onclick="displayDonationPopup()">Donate</button>

        <div id="donation-popup" class="donation-popup" style="display:none;">
            <h2>Confirm Donation?</h2>
            <p>You are about to donate: <span id="confirm-amount"></span></p>
            <input type="hidden" name="confirm-amount" id="confirm-amount-input">

            <label for="payment-method">Choose a payment method:</label>
            <select name="payment-method" id="payment-method" required>
                <option value="credit-card">Credit Card</option> <!--link to each different payment page for different methods, same pop up box-->
                <option value="tng">Touch n Go</option>
                <option value="bank-transfer">Bank Transfer</option>
            </select>

            <button type="submit" name="confirmDonate">Yes</button>
            <button type="button" onclick="closeDonationPopup()">No</button>
        </div>

        <!--after donate successfully, show do u wanna leave a feedback, same pop up box-->

    </form>

    <h2>Where your donations goes?</h2>
    <div class="donations-container">
        <div class="donation-card">
            <h3>Mercy Malaysia</h3>
            <img src="mercy.png" alt="Mercy Malaysia"> <!--updated when-->
        </div>
        <div class="donation-card">
            <h3>Kechara Soup Kitchen</h3>
            <img src="kechara.png" alt="Kechara Soup Kitchen">
        </div>
        <div class="donation-card">
            <h3>The Assembly Soup Kitchen</h3>
            <img src="assemblysoup.png" alt="The Assembly Soup Kitchen">
        </div>
    </div>

    <h2>Our Collective Impact</h2>
    <div class="impact-container">
        <div class="chart-container">
            <canvas id="impactChart" width="400" height="200"></canvas>
        </div>
        <div class="info-container">
            <p id="mealsText">Meals Provided: <?=$mealsProvided?></p> <!--here show numbers not percentage-->
            <p id="peopleText">People Supported: <?=$peopleSupported?></p>
        </div>
    </div>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    //chart
    fetch('?fetchData=true')
        .then(response => response.json())
        .then(data => {

            const ctx = document.getElementById('impactChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Meals Provided', 'People Supported'],
                    datasets: [{
                        label: '% Achieved',
                        data: [data.mealsPercentage, data.peoplePercentage],
                        backgroundColor: ['#4CAF50', '#2196F3'],
                        borderColor: ['#388E3C', '#1976D2'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: value => value + '%'
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));

    function updateProgress(){
        fetch (window.location.href + '?action=getProgress')
            .then(response => response.json())
            .then(data => {
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progressText');
                const mealsText = document.getElementById('mealsText')
                const peopleText = document.getElementById('peopleText');

                progressBar.style.width = `${data.progressPercentage}%`;
                progressBar.textContent = `${data.progressPercentage}%`;

                progressText.textContent = `Raised: $${data.totalDonations} / $<?= $fundraisingGoal ?>`;

                mealsText.textContent = `Meals Provided: ${data.mealsProvided}`;
                peopleText.textContent = `People Supported: ${data.peopleSupported}`;

                impactChart.data.datasets[0].data = [data.mealsProvided, 0];
                impactChart.data.datasets[1].data = [0, data.peopleSupported];
                impactChart.update();
            })
            .catch(error => console.error('Error fetching progress:', error));
    }
    setInterval(updateProgress, 5000) //refresh every 5 seconds

    //buttons
    const buttons = document.querySelectorAll('.donation-btn');
    const donationInput = document.getElementById('donation-input');
    const confirmAmount = document.getElementById('confirm-amount');
    const confirmAmountInput = document.getElementById('confirm-amount-input');

    buttons.forEach(button =>{
        button.addEventListener('click', () => {
            donationInput.value = button.value;
        })
    })

    function displayDonationPopup() {
        const amount = donationInput.value.trim();

        if (amount === "" || isNaN(amount) || parseInt(amount) <= 0) {
            alert('Please enter a valid donation amount.');
            return;
        }

        confirmAmount.textContent = amount;
        confirmAmountInput.value = amount;

        document.getElementById('donation-popup').style.display = 'block';
    }

    function closeDonationPopup() {
        document.getElementById('donation-popup').style.display = 'none';
    }
</script>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>About Us</h4>
            <p>Food Fox is a Malaysian-based non-profit organization focused on providing food to the underprivileged community.</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="mainpage.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Follow Us</h4>
            <div class="social-links">
                <a href="https://facebook.com" class="fa fa-facebook"></a>
                <a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://youtube.com" class="fa fa-youtube"></a>
            </div>
        </div>
        <div class="footer-section">
            <h4>Contact Info</h4>
            <p>Email: info@foodfox.org.my</p>
            <p>Phone Number: +603-0929 0501</p>
            <p>Food Fox Headquarters: 51, Jalan Binjai, KLCC, KL City Centre, Kuala Lumpur</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Food Fox. All rights reserved. | Powered by <a href="https://foodfox.com" target="_blank">Food Fox</a></p>
    </div>
</footer>
</body>
</html>