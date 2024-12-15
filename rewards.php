<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main {
            color: white;
        }

        .reward{
            text-align: center;
        }

        h1{
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 10px;
            margin-top: 30px;
            display: inline-block;
            padding-bottom: 10px;
            padding-top: 10px;
            border-bottom: 2px solid #d3a029;
        }

        h2 a {
            text-decoration: none;
            color: inherit;
        }

        h2 i.fa-shopping-cart {
            color: #5a3e36;
            margin-left: 10px;
            font-size: 24px;
            vertical-align: middle;
        }

        .points-dashboard {
            background-color: #d7c4b7;
            border-radius: 15px;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .points-dashboard h2 {
            font-size: 28px;
            color: #5a3e36;
            margin-bottom: 10px;
        }

        .points-dashboard span {
            display: block;
            font-size: 72px;
            font-weight: bold;
            color: #000;
            margin: 10px 0;
        }

        .points-dashboard p {
            font-size: 18px;
            color: #333;
            margin-top: 0;
        }

        .available-rewards {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
            padding: 10px 20px;
        }

        .reward-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            width: 180px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .reward-card img {
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .reward-card p {
            font-size: 14px;
            color: #333;
            margin: 5px 0;
        }

        .reward-card button {
            background-color: #5a3e36;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reward-card button:hover {
            background-color: #8b5c4b;
        }

        .reward-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
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
                <?php loginSection(); ?>
            </div>
        </div>
    </nav>
</header>

<main class="reward">
    <h1 class="reward-text">Rewards</h1>
    <div class="points-dashboard">
        <h2>Your Total Points</h2>
        <?php
        $conn = connection();
        $memberID = $_SESSION['memberID'] ?? "";
        $memberPoints = 0;

        if ($memberID) {
            $sql = "SELECT points FROM members WHERE memberID = $memberID";
            if ($result = mysqli_query($conn, $sql)) {
                $row = mysqli_fetch_assoc($result);
                $memberPoints = $row['points'];
            }
        }
        ?>
        <span><?php echo $memberPoints ?? 0; ?></span>
        <p>points</p>
    </div>

    <?php
    $rewardsSql = "SELECT * FROM rewards";
    $result = $conn->query($rewardsSql);

    if (isset($_POST['redeem'])) {
        $rewardID = $_POST['rewardID'];
        $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 1;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (isset($_SESSION['cart'][$rewardID])) {
            $_SESSION['cart'][$rewardID] += $amount;
        } else {
            $_SESSION['cart'][$rewardID] = $amount;
        }
    }

    $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

    ?>
    <h2>Available Rewards
        <a href="cart.php">
            <i class="fa fa-shopping-cart"></i>
            <span id="cart-count">
                <?php echo $cartCount; ?>
            </span>
        </a>
    </h2>
    <div class="available-rewards">
        <?php
        $rewardsSql = "SELECT * FROM rewards";
        $result = $conn->query($rewardsSql);

        while ($reward = $result->fetch_assoc()) {
            echo "<div class='reward-card'>
                    <img src='{$reward['rewardPic']}' alt='Reward Picture'>
                    <p>{$reward['rewardName']}</p>
                    <p>{$reward['pointsNeeded']} points</p>
                    <form method='post' action=''>
                        <input type='hidden' name='rewardID' value='{$reward['rewardID']}'>
                        <button type='submit' name='redeem'>Redeem</button>
                    </form>
                </div>";
        }
        ?>
    </div>
</main>

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
