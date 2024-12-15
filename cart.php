<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
$conn = connection();

$cartItems = $_SESSION['cart'] ?? array();

// Handle amount update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['amounts'])) {
        foreach ($_POST['amounts'] as $rewardID => $newAmount) {
            $newAmount = intval($newAmount); //make sure its int
            if ($newAmount > 0 && isset($cartItems[$rewardID])) {
                $cartItems[$rewardID] = $newAmount;
            }
        }
        $_SESSION['cart'] = $cartItems;
    }

    if (isset($_POST['remove_item'])) {
        $rewardID = $_POST['remove_item'];
        unset($cartItems[$rewardID]);
        $_SESSION['cart'] = $cartItems;
    }

    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = array();
        $cartItems = array();
    }

    if (isset($_POST['checkout'])) {
        $memberID = $_SESSION['memberID'] ?? null;
        if($memberID) {
            //must be member, but logically only member can access to reward page which link here
            $selectedItems = $_POST['selected_items'] ?? array();

            if (!empty($selectedItems)){
                $totalPointsRequired = 0;

                foreach ($cartItems as $rewardID => $quantity){
                    $sql = "SELECT pointsNeeded FROM rewards WHERE rewardID = $rewardID";
                    $result = mysqli_query($conn, $sql);

                    if ($result && $row = mysqli_fetch_assoc($result)){
                        $totalPointsRequired += $row['pointsNeeded'] * $quantity;
                    }
                }

                $memberSql = "SELECT points FROM members WHERE memberID = $memberID";
                $memberResult = mysqli_query($conn, $memberSql);

                if ($memberResult && $memberRow = mysqli_fetch_assoc($memberResult)){
                    $memberPoints = $memberRow['points'];

                    if ($memberPoints >= $totalPointsRequired){
                        $newPoints = $memberPoints - $totalPointsRequired;
                        $updateSql = "UPDATE members SET points = $newPoints WHERE memberID = $memberID";
                        mysqli_query($conn, $updateSql);

                        foreach ($selectedItems as $rewardID) {
                            unset($cartItems[$rewardID]);
                        }
                        $_SESSION['cart'] = $cartItems;
                        echo "<script>alert('Checkout successful!');</script>";
                    }
                    else {
                        echo "<script>alert('Insufficient points!');</script>";
                    }
                }
            }
        }
    }
}

if (!empty($cartItems)) {
    $rewardIds = implode(',', array_keys($cartItems));
    $sql = "SELECT * FROM rewards WHERE rewardID IN ($rewardIds)";
    $result = mysqli_query($conn, $sql);
} else {
    $result = null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .main {
            color: white;
        }

        h1 {
            font-size: 2rem;
            color: #5C4033;
            margin-bottom: 10px;
            margin-top: 30px;
            display: inline-block;
            padding-bottom: 10px;
            padding-top: 10px;
            border-bottom: 2px solid #d3a029;
        }

        .h1 {
            text-align: center;
        }

        .cart-container {
            max-width: 800px;
            margin: 20px auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 10px;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
        }

        .cart-item .info {
            flex: 1;
            margin-left: 20px;
        }

        .cart-item .info p {
            font-size: 14px;
            color: #333;
            margin: 5px 0;
        }

        .cart-item .amount {
            text-align: center;
            min-width: 120px;
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 20px;
        }

        .cart-buttons {
            text-align: center;
            margin: 20px 0;
        }

        .cart-buttons button {
            background-color: #5a3e36;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart-buttons button:hover {
            background-color: #8b5c4b;
        }

        .cart-item button {
            background-color: #d3a029;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-to-rewards {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-to-rewards a {
            background-color: #5a3e36;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-to-rewards a:hover {
            background-color: #8b5c4b;
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
                <?php
                loginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <div class="h1"><h1>Shopping Cart</h1></div>
    <div class="cart-container">
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
            <form id="cart-form" method="post" enctype="multipart/form-data">
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    $rewardID = $row['rewardID'];
                    $quantity = $cartItems[$rewardID];
                    ?>
                    <div class="cart-item">
                        <input type="checkbox" name="selected_items[]" value="<?php echo $rewardID; ?>" checked>
                        <img src="<?php echo $row['rewardPic']; ?>" alt="Reward Picture">
                        <div class="info">
                            <p><strong>Reward Name:</strong> <?php echo $row['rewardName']; ?></p>
                            <p><strong>Points Needed:</strong> <?php echo $row['pointsNeeded']; ?></p>
                        </div>
                        <div class="amount">
                            <label for="amount">Amount:</label>
                            <select name="amounts[<?php echo $rewardID; ?>]" onchange="updateCartOnChange()">
                                <?php for ($i = 1; $i <= 10; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php echo $i == $quantity ? 'selected' : ''; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" name="remove_item" value="<?php echo $rewardID; ?>">Remove</button>
                    </div>
                <?php } ?>
                <div class="cart-buttons">
                    <button type="submit" name="clear_cart">Clear Cart</button>
                    <button type="submit" name="checkout">Checkout</button>
                </div>
            </form>
        <?php } else { ?>
            <p class="empty-cart">Your cart is empty</p>
        <?php } ?>
    </div>
    <div class="back-to-rewards">
        <a href="rewards.php">Back to Rewards</a>
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
<script>
    function updateCartOnChange() {
        document.getElementById('cart-form').submit();
    }
</script>
</body>
</html>
