<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
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

        .navbar{
            z-index: 100;
        }

        .donation{
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 10px;
            margin-top: 30px;
            display: inline-block;
            padding-bottom: 10px;
            padding-top: 10px;
            border-bottom: 2px solid #d3a029;
        }

        .progress-text{
            text-align: left;
            padding-left: 10px;
            margin-bottom: 0;
        }

        .progress-container {
            width: 100%;
            height: 40px;
            border-radius: 20px;
            background-color: #f2e9df;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #C5B4A5, #F0D9C9, #ffffff);
            text-align: center;
            line-height: 40px;
            color: #5C4033;
            font-weight: bold;
            font-size: 16px;
            border-radius: 20px;
            transition: width 0.5s ease-in-out;
            box-shadow: inset 0 -2px 6px rgba(0, 0, 0, 0.1);
        }

        .donations-buttons{
            text-align: center;
            padding-top: 20px;
            padding-bottom: 5px;
        }

        .donation-btn {
            margin: 5px;
            padding: 12px 30px;
            background: linear-gradient(90deg, #C5B4A5, #D9C3AF);
            color: #5C4033;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .donation-btn:hover {
            background: linear-gradient(90deg, #D9C3AF, #F2E9DF);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        #donation-input {
            width: 380px;
            padding: 14px;
            border: 2px solid #C5B4A5;
            border-radius: 10px;
            text-align: center;
            font-size: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        #donation-input:focus {
            border-color: #5C4033;
            outline: none;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
        }

        .donate-submit {
            padding: 14px 30px;
            background: linear-gradient(90deg, #C5B4A5, #D9C3AF);
            color: #5C4033;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .donate-submit:hover {
            background: linear-gradient(90deg, #D9C3AF, #F2E9DF);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .donations-container{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 20px;
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

        .donation-popup, .payment-popup, .feedback-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f4f4f4;
            padding: 20px;
            border: 1px solid #7F6C54;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
            width: 450px;
            max-width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .donation-popup h3, .payment-popup h3, .feedback-popup h3 {
            color: #7F6C54;
            font-family: 'Arial', sans-serif;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .donation-popup button, .payment-popup button, .feedback-popup button {
            background-color: #7F6C54;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            transition: background-color 0.3s ease;
            margin: 10px 5px;
            width: 120px;
        }

        .donation-popup button:hover, .payment-popup button:hover, .feedback-popup button:hover {
            background-color: #5c4939;
        }

        input[type="text"], input[type="number"], input[type="email"], input[type="password"], input[type="tel"], input[type="month"], select {
            width: 100%;
            padding: 8px;
            margin: 4px 0 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="tel"]:focus, input[type="month"]:focus, select:focus {
            border-color: #7F6C54;
        }

        label {
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            color: #7F6C54;
            display: block;
            margin-bottom: 4px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 20px;
        }

        ul li {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }

        .feedback-popup {
            width: 350px;
        }

        .donation-popup button[type="button"], .payment-popup button[type="button"], .feedback-popup button[type="button"] {
            background-color: #d1d1d1;
            font-size: 14px;
            width: 100px;
        }

        .donation-popup button[type="button"]:hover, .payment-popup button[type="button"]:hover, .feedback-popup button[type="button"]:hover {
            background-color: #aaa;
        }

        .impact-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-bottom: 20px;
        }

        .chart-container {
            width: 60%;
            padding-left: 40px;
        }

        .info-container {
            width: 200px;
            height: 200px;
            background-color: #f5ead9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .info-container .number {
            font-size: 3.7rem;
            font-weight: bold;
            color: #3e3e3e;
            margin: 0;
        }

        .info-container h1 {
            font-size: 1.1rem;
            font-weight: normal;
            color: #3e3e3e;
            margin: 10px 0 0;
        }

        .info-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-right: 50px;
            padding-left: 20px;
            margin-top: 100px;
        }

        .info-container:hover{
            transform: translateY(-5px);
        }

        .feedback-container {
            display: flex;
            justify-content: space-between;
            gap: 60px;
            margin: 20px auto;
            max-width: 900px;
            padding: 20px;

        }

        .feedback-column {
            flex: 1;
            max-width: 48%;
        }

        .feedback-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            background-color: #fff7e6;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .member-profile {
            margin-right: 15px;
        }

        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .feedback-content {
            flex-grow: 1;
            padding-left: 20px;
        }

        .member-name {
            font-weight: bold;
            font-size: 16px;
            color: #6d4c41;
            margin-bottom: 5px;
        }

        .feedback-content p {
            margin: 0;
            font-size: 14px;
            text-align: left;
            color: #333;
            line-height: 1.6;
        }

    </style>
</head>
<body>
<?php
$fundraisingGoal = 5000;
$maxMealsProvided = 1000;
$maxPeopleSupported = 500;

$conn = connection();

$sql = "SELECT SUM(amount) AS total_donations FROM donations";
$result = $conn->query($sql);
$totalDonations = ($result->num_rows > 0) ? $result->fetch_assoc()['total_donations'] : 0;

$mealsProvided = floor($totalDonations / 10);
$mealsProvidedPercentage = floor(min(100, ($mealsProvided / $maxMealsProvided) * 100));

$count = "SELECT COUNT(*) AS total_people FROM donations";
$result = $conn->query($count);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

$peopleSupported = $row['total_people'];
$peopleSupportedPercentage = floor(min(100, ($peopleSupported / $maxPeopleSupported) * 100));

if (isset($_GET['action']) && $_GET['action'] == 'getProgress') {
    $progressPercentage = floor(min(100,($totalDonations / $fundraisingGoal) * 100)); //cap at 100%

}
?>
<header>
    <nav>
        <div id="navbar" class="navbar">
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
<main class="donation">
    <h2>Our Fundraising Goal</h2>
    <p id="progressText" class="progress-text">Raised: RM <?=$totalDonations?> / RM <?=$fundraisingGoal?></p>
    <div class="progress-container">
        <div id="progress-bar" class="progress-bar" style="width:<?= round(($totalDonations / $fundraisingGoal) *100) ?>%;">
            <?= floor(min(100, ($totalDonations / $fundraisingGoal) *100) )?>%
        </div>
    </div>


    <h2>Make a Donation</h2>
    <?php
    $conn = connection();
    $memberID = isset($_SESSION['memberID']) ? $_SESSION['memberID'] : 0;
    //if member only can donate

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmDonate'])) {
        $amount = $_POST['confirm-amount'];
        $paymentMethod = $_POST['payment-method'];

        $errors = [];

        if (empty($amount)) {
            $errors['amount'] = 'You must enter an amount.';
        }
        elseif (!preg_match('/^[1-9][0-9]*$/', $amount)) {
            $errors['amount'] = 'The amount must be a positive number.';
        }

        if (empty($errors)) {
            $sql = "INSERT INTO donations (memberID, amount, paymentMethod) VALUES ('$memberID', '$amount', '$paymentMethod')";
            if ($conn->query($sql) === TRUE) {

                //points get from donating
                $donationID = $conn->insert_id;

                $pointsEarned = floor($amount / 10);
                $updateSql = "UPDATE members SET points = points + $pointsEarned WHERE memberID = '$memberID'";
                $conn->query($updateSql);

                echo "<script>alert('Thank you for your donation!'); window.location.href = window.location.href + '?showFeedback=true'</script>";
                $_SESSION['donationID'] = $donationID;
            }
            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-feedback'])) {
        $feedback = $_POST['feedback'];
        $donationID = $_SESSION['donationID'];

        $sql = "UPDATE donations SET feedback = '$feedback' WHERE donationID = '$donationID'";
        if ($conn->query($sql) === TRUE) {
            unset($_SESSION['donationID']);
            echo "<script>alert('Thank you for your feedback!'); window.location.href = window.location.href.split('?')[0]</script>";
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="donations-buttons">
            <button type="button" class="donation-btn" name="amount" value="10">RM10</button>
            <button type="button" class="donation-btn" name="amount" value="20">RM20</button>
            <button type="button" class="donation-btn" name="amount" value="50">RM50</button>
            <button type="button" class="donation-btn" name="amount" value="100">RM100</button>
        </div>
        <label><input id="donation-input" type="text" name="amount" placeholder="Specify the amount you want to donate (RM)..."></label>
        <?php if ($memberID) { ?>
        <button type="button" class="donate-submit" onclick="displayDonationPopup()">Donate</button>
        <p style="color: red"><?= isset ($errors['amount']) ? $errors['amount'] : '' ?></p>
        <?php } else { ?>
        <button type="button" class="donate-submit" onclick="alert('Please login or sign up to donate'); window.location.href = 'login.php';">Donate</button>
        <?php } ?>

        <div id="donation-popup" class="donation-popup" style="display:none;">
            <h3>Confirm Donation?</h3>
            <p>You are about to donate: RM<span id="confirm-amount"></span></p>
            <input type="hidden" name="confirm-amount" id="confirm-amount-input">

            <label for="payment-method">Choose a payment method:</label>
            <select name="payment-method" id="payment-method" required>
                <option value="credit-card">Credit Card</option> <!--link to each different payment page for different methods, same pop up box-->
                <option value="tng">Touch n Go</option>
                <option value="bank-transfer">Bank Transfer</option>
            </select>

            <button type="button" onclick="proceed()">Proceed</button>
            <button type="button" onclick="closePopup()">Cancel</button>
        </div>

        <div id="payment-popup" class="payment-popup" style="display:none;">
            <div id="credit-card-info" style="display:none;">
                <p>Please enter your Credit Card details.</p>
                <p>We accept the following credit cards:</p>
                <ul>
                    <li>Visa</li>
                    <li>MasterCard</li>
                    <li>American Express (AMEX)</li>
                    <li>PayPal</li>
                </ul>

                <label for="cardNumber">Card Number:</label><br>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required><br><br>

                <label for="expirationDate">Expiration Date:</label><br>
                <input type="month" id="expirationDate" name="expirationDate" required><br><br>

                <label for="cvv">CVV (Security Code):</label><br>
                <input type="text" id="cvv" name="cvv" placeholder="123" required><br><br>

                <label for="cardHolderName">Cardholder's Name:</label><br>
                <input type="text" id="cardHolderName" name="cardHolderName" placeholder="full name" required><br><br>

            </div>
            <div id="tng-info" style="display:none;">
                <p>Please provide your Touch 'n Go (TNG) details.</p>
                <p>To proceed, please provide the following details:</p>
                <ul>
                    <li>Phone Number (linked to your TNG account)</li>
                    <li>PIN/Password for TNG account verification</li>
                </ul>

                <label for="phoneNumber">Phone Number:</label><br>
                <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" required><br><br>

                <label for="tngPin">TNG 6 digit PIN/Password:</label><br>
                <input type="password" id="tngPin" name="tngPin" placeholder="Enter your 6 digit PIN/Password" required><br><br>

                <label for="tngAmount">Amount to Reload/Transfer:</label><br>
                <input type="number" id="tngAmount" name="tngAmount" placeholder="Enter amount" required><br><br>
            </div>

            <div id="bank-transfer-info" style="display:none;">
                <p>Please provide your Bank Transfer details.</p>
                <p>To proceed, please provide the following bank transfer details:</p>
                <ul>
                    <li>Your Bank Account Number</li>
                    <li>Your Bank Name</li>
                    <li>Recipient's Bank Name</li>
                    <li>Recipient's Bank Account Number</li>
                    <li>Amount to Transfer</li>
                    <li>Reference/Payment Purpose (Optional)</li>
                </ul>

                <label for="bankAccountNumber">Your Bank Account Number:</label><br>
                <input type="text" id="bankAccountNumber" name="bankAccountNumber" placeholder="Enter your account number" required><br><br>

                <label for="bankName">Your Bank Name:</label><br>
                <input type="text" id="bankName" name="bankName" placeholder="Enter your bank name" required><br><br>

                <label for="recipientBankName">Recipient's Bank Name:</label><br>
                <input type="text" id="recipientBankName" name="recipientBankName" placeholder="Enter recipient's bank name" required><br><br>

                <label for="recipientAccountNumber">Recipient's Bank Account Number:</label><br>
                <input type="text" id="recipientAccountNumber" name="recipientAccountNumber" placeholder="Enter recipient's account number" required><br><br>

                <label for="transferAmount">Amount to Transfer:</label><br>
                <input type="number" id="transferAmount" name="transferAmount" placeholder="Enter amount" required><br><br>

                <label for="paymentReference">Reference/Payment Purpose (Optional):</label><br>
                <input type="text" id="paymentReference" name="paymentReference" placeholder="Enter reference or payment purpose (optional)"><br><br>

                <label for="email">Email (for receipt/confirmation):</label><br>
                <input type="email" id="email" name="email" placeholder="youremail@example.com" required><br><br>
            </div>

            <button type="submit" name="confirmDonate">Donate</button>
            <button type="button" onclick="closePopup()">Cancel</button>
        </div>
    </form>

    <form method="post" enctype="multipart/form-data">
        <!--after donate successfully, show do u wanna leave a feedback, pop up-->
        <div id="feedback-popup" class="feedback-popup" style="display: none">
            <h3>Donated successfully!</h3>
            <p>Do you want to leave a feedback?</p>
            <label><input type="text" name="feedback" placeholder="Leave your feedback here if you have any..."></label>
            <button type="submit" name="submit-feedback">Submit</button>
            <button type="button" onclick="closeFeedback()">No</button>
        </div>
    </form>

    <h2>Where your donations goes?</h2>
    <div class="donations-container">
        <div class="donation-card">
            <h3>Mercy Malaysia</h3>
            <img src="mercy.jpeg" alt="Mercy Malaysia">
        </div>
        <div class="donation-card">
            <h3>Kechara Soup Kitchen</h3>
            <img src="kechara.jpeg" alt="Kechara Soup Kitchen">
        </div>
        <div class="donation-card">
            <h3>The Assembly Soup Kitchen</h3>
            <img src="assemblysoupkitchen.jpeg" alt="The Assembly Soup Kitchen">
        </div>
    </div>

    <h2>Our Collective Impact</h2>
    <div class="impact-container">
        <div class="chart-container">
            <canvas id="impactChart" width="400" height="200"></canvas>
        </div>
        <div class="info-row">
            <div class="info-container">
                <div class="number" id="mealsText"><?= $mealsProvided ?></div>
                <h1>Total Meals Provided</h1>
            </div>
            <div class="info-container">
                <div class="number" id="peopleText"><?= $peopleSupported ?></div>
                <h1>Total People Supported</h1>
            </div>
        </div>
    </div>

    <h2>Community Feedback</h2>
    <?php
    $conn = connection();

    $sql = "SELECT d.feedback, m.memberName, m.memberProfile 
        FROM donations d 
        JOIN members m ON d.memberID = m.memberID 
        WHERE d.hidden = 0 AND d.feedback <> '' AND d.feedback IS NOT NULL 
        ORDER BY d.donationDate DESC 
        LIMIT 6";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='feedback-container'>";

        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }
        $half = ceil(count($feedbacks) / 2);

        echo "<div class='feedback-column'>";
        for ($i = 0; $i < $half; $i++) {
            $row = $feedbacks[$i];
            echo "<div class='feedback-row'>";
            echo "<div class='member-profile'>";
            echo "<img src='" . $row["memberProfile"] . "' alt='Member Profile' class='profile-pic' />";
            echo "</div>";
            echo "<div class='feedback-content'>";
            echo "<p class='member-name'>" . str_repeat('*', strlen($row['memberName'])) . "</p>";
            echo "<p>" . $row["feedback"] . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        echo "<div class='feedback-column'>";
        for ($i = $half; $i < count($feedbacks); $i++) {
            $row = $feedbacks[$i];
            echo "<div class='feedback-row'>";
            echo "<div class='member-profile'>";
            echo "<img src='" . $row["memberProfile"] . "' alt='Member Profile' class='profile-pic' />";
            echo "</div>";
            echo "<div class='feedback-content'>";
            echo "<p class='member-name'>" . str_repeat('*', strlen($row['memberName'])) . "</p>";
            echo "<p>" . $row["feedback"] . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        echo "</div>";
    } else {
        echo "<p>No feedback available.</p>";
    }
    ?>

</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let impactChart;

    fetch('helpers/donation_chart.php?fetchData=true')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched Data:', data);
            const mealsPercentage = data.mealsPercentage;
            const peoplePercentage = data.peoplePercentage;
            updateChart(mealsPercentage, peoplePercentage);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });

    //chart
    function updateChart(mealsPercentage, peoplePercentage) {
        const ctx = document.getElementById('impactChart').getContext('2d');

        if (impactChart) {
            impactChart.destroy();
        }

        impactChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Meals Provided', 'People Supported'],
                datasets: [
                    {
                        label: 'Meals Provided',
                        data: [mealsPercentage, null],
                        backgroundColor: '#d4a373',
                        borderColor: '#b07b4e',
                        borderWidth: 1,
                        barThickness: 200
                    },
                    {
                        label: 'People Supported',
                        data: [null, peoplePercentage],
                        backgroundColor: '#a3b18a',
                        borderColor: '#6b8f59',
                        borderWidth: 1,
                        barThickness: 200
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        beginAtZero: true,
                        max: 100, // 0-100%
                        ticks: {
                            callback: function (value) {
                                return value % 20 === 0 ? `${value}%` : '';
                            },
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `${tooltipItem.dataset.label}: ${tooltipItem.raw}%`;
                            },
                        },
                    },
                },
            },
        });
    }

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
    updateProgress();

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

        confirmAmount.textContent = amount;
        confirmAmountInput.value = amount;

        document.getElementById('donation-popup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('donation-popup').style.display = 'none';
        document.getElementById('payment-popup').style.display = 'none';

        closePopup.style.display = 'none';
    }

    function closeFeedback(){
        document.getElementById('feedback-popup').style.display = 'none';

        const url = new URL(window.location.href);
        url.searchParams.delete('showFeedback');
        window.history.replaceState({}, document.title, url);
    }

    function proceed(){

        const paymentMethod = document.getElementById('payment-method').value;

        document.getElementById('credit-card-info').style.display = 'none';
        document.getElementById('tng-info').style.display = 'none';
        document.getElementById('bank-transfer-info').style.display = 'none';

        document.querySelectorAll('#payment-popup input').forEach(input => {
            input.required = false;
        });

        let sectionToShow;
        if (paymentMethod === "credit-card") {
            sectionToShow = 'credit-card-info';
        } else if (paymentMethod === "tng") {
            sectionToShow = 'tng-info';
        } else if (paymentMethod === "bank-transfer") {
            sectionToShow = 'bank-transfer-info';
        }

        //clear required for each input so that it wouldn't capture all even though not choosing that method
        if (sectionToShow) {
            document.getElementById(sectionToShow).style.display = 'block';
            document.querySelectorAll(`#${sectionToShow} input`).forEach(input => {
                input.required = true;
            });
        }

        document.getElementById('donation-popup').style.display = 'none';
        document.getElementById('payment-popup').style.display = 'block';
    }


    function getURLParameter(name) {
        return new URLSearchParams(window.location.search).get(name);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (getURLParameter('showFeedback') === 'true') {
            document.getElementById('feedback-popup').style.display = 'block';
        } else {
            document.getElementById('feedback-popup').style.display = 'none';
        }
    });


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