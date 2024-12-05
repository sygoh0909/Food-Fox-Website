<?php
include('cookie/cookie.php');
include ('db/db_conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="main.css">
    <title>Admin Donations Management Page</title>

    <style>
        .donations {
            padding: 20px 30px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .donations-table {
            margin-top: 20px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="main-links">
                <a href="admin_main.php" class="roundButton">Home</a>
                <a href="admin_members.php" class="roundButton">Members</a>
                <a href="admin_events.php" class="roundButton">Events</a>
                <a href="admin_donations.php" class="roundButton">Donation</a>
                <a href="admin_rewards.php" class="roundButton">Rewards</a>
            </div>
            <div class="nav-links">
                <?php
                adminLoginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <section class="donations">
        <h2>Donations Management</h2>
        <div class="search">
            <form method="get" action="">
                <label><input type="text" name="search" placeholder="Search donations..."></label>
                <button type="submit">Search</button> <!--search feature-->
            </form>
        </div>
        <div class="donations-table">
            <table>
                <tr>
                    <th>Donation ID</th>
                    <th>Member Name</th>
                    <th>Amount</th>
                    <th>Donation Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();

                $searchQuery = isset($_GET['search']) ? $_GET['search'] : "";

                $sql = "SELECT d.*, m.memberName FROM donations d JOIN members m ON d.memberID = m.memberID";

                if(!empty($searchQuery)){
                    $sql .= " WHERE memberName LIKE '%".$searchQuery."%' OR amount LIKE '%".$searchQuery."%' OR donationDate LIKE '%".$searchQuery."%'";
                }

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $dateFormatted = date('d-m-Y', strtotime($row["donationDate"]));
                        echo "<tr>";
                        echo "<td data-donation-id='" .htmlspecialchars($row["donationID"]) ."'>" .str_repeat('*', strlen($row["donationID"]))."</td>";
                        echo "<td>" . $row["memberName"] . "</td>";
                        echo "<td>" . $row["amount"] . "</td>";
                        echo "<td>" . $dateFormatted . "</td>";
                        echo "<td><a href='action_donation.php?donationID=" . $row["donationID"] . "&action=edit '><button>Edit</button></a><a href='action_donation.php?donationID=" . $row["donationID"] . "&action=delete '><button>Delete</button></a></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
        <div class="feedback-table">
            <h2>Feedback Management</h2>
            <table>
                <tr>
                    <th>Member Name</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();
                $sql = "SELECT d.donationID, d.feedback, m.memberName FROM donations d, members m WHERE d.memberID = m.memberID AND d.hidden = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if (!empty($row["feedback"])) {
                            echo "<tr id='feedback-" . $row["donationID"] . "'>";
                            echo "<td>" . $row["memberName"] . "</td>";
                            echo "<td>" . $row["feedback"] . "</td>";
                            echo "<td><button type='button' name='hide' onclick='displayActionPopup(" . $row["donationID"] . ")'>Hide</button>";
                            echo "</tr>";
                        }
                    }
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmAction'])) {
                    $donationID = $_POST['donationID'];

                    $sql = "UPDATE donations SET hidden = 1 WHERE donationID = '$donationID'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Feedback hidden successfully!'); window.location.href = window.location.href </script>')";
                    }
                }
                ?>
                <form method="post" action="">
                    <div id="action-popup" class="action-popup" style="display:none;">
                        <h2>Confirm to hide this feedback?</h2>
                        <input type="hidden" name="donationID" id="donationID">
                        <button type="submit" name="confirmAction">Yes</button>
                        <button type="button" onclick="closeActionPopup()">No</button>
                    </div>
                </form>
            </table>
        </div>
    </section>
</main>
<script>
    function displayActionPopup(donationID) {
        document.getElementById('donationID').value = donationID;
        document.getElementById('action-popup').style.display = 'block';
    }

    function closeActionPopup() {
        document.getElementById('action-popup').style.display = 'none';
    }

</script>
</body>
</html>