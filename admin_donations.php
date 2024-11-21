<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Donations Management Page</title>

    <style>
        body {
            background-color: #F5EEDC;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            padding: 15px 20px;
            background-color: #5C4033;
        }
        .main-links {
            display: flex;
            justify-content: center;
            gap: 40px;
        }
        .main-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .donations {
            padding: 20px 30px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .search{
            text-align: center
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
        button {
            padding: 10px 15px;
            background-color: #5C4033;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer; /* set how the mouse looks like when pointing on the button */
            margin-left: 10px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #7B5A46;
        }
        .donations-table {
            margin-top: 20px;
            overflow-x: auto; /* To allow scrolling on smaller screens */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #5C4033;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="main-links">
                <a href="admin_main.php">Home</a>
                <a href="admin_members.php">Members</a>
                <a href="admin_events.php">Events</a>
                <a href="admin_donations.php">Donation</a>
            </div>
        </div>
    </nav>
</header>
<main>
    <section class="donations">
        <h2>Donations Management</h2>
        <div class="search">
            <label><input type="text" placeholder="Search donations..."></label>
            <button>Search</button> <!--search feature-->

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
                $sql = "SELECT d.*, m.memberName FROM donations d, members m WHERE d.memberID = m.memberID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-donation-id='" .htmlspecialchars($row["donationID"]) ."'>" .str_repeat('*', strlen($row["donationID"]))."</td>";
                        echo "<td>" . $row["memberName"] . "</td>";
                        echo "<td>" . $row["amount"] . "</td>";
                        echo "<td>" . $row["donationDate"] . "</td>";
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
                $sql = "SELECT d.feedback, m.memberName FROM donations d, members m WHERE d.memberID = m.memberID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["memberName"] . "</td>";
                        echo "<td>" . $row["feedback"] . "</td>";
                        echo "<td><button>Delete</button>"; //delete - pop up - comfirm delete - delete feedback from donation table
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>