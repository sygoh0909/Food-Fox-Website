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
    <title>Admin Rewards Management Page</title>

    <style>
        .rewards {
            padding: 20px 30px;
        }
        .rewards-table {
            margin-top: 20px;
            overflow-x: auto;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
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
    <section class="rewards">
        <h2>Rewards Items Management</h2>
        <div class="search">
            <form method="get" action="">
                <label><input type="text" name="search" placeholder="Search rewards items..."></label>
                <button type="submit">Search</button>
                <a href="action_rewards.php?action=add"><button type="button">Add New Rewards</button></a>
            </form>
        </div>
        <div class="rewards-table">
            <table>
                <tr>
                    <th>Reward ID</th>
                    <th>Reward Name</th>
                    <th>Points Needed</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();

                $searchQuery = isset($_GET['search']) ? $_GET['search'] : "";

                $sql = "SELECT rewardID, rewardName, pointsNeeded FROM rewards";

                if (!empty($searchQuery)) {
                    $sql .= " WHERE rewardName LIKE '%$searchQuery%' OR pointsNeeded LIKE '%$searchQuery%'";
                }

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-reward-id='" . htmlspecialchars($row["rewardID"]) . "'>" . str_repeat('*', strlen($row['rewardID'])). "</td>";
                        echo "<td>" . $row["rewardName"] . "</td>";
                        echo "<td>" . $row["pointsNeeded"] . "</td>";
                        echo "<td><a href='action_rewards.php?rewardID=" .$row['rewardID'] ."&action=edit'><button>Edit</button></a><a href='action_rewards.php?rewardID=" . $row['rewardID'] ."&action=delete'><button>Delete</button></a></td>";
                        echo "</tr>";
                    }
                }else{
                    echo "<tr><td colspan='4'>No Rewards found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>
