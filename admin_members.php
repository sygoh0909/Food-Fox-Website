<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Members Management Page</title>

    <style>
        .members {
            padding: 20px 30px;
        }
        .member-table {
            margin-top: 20px;
            overflow-x: auto; /* To allow scrolling on smaller screens */
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
            <div class="nav-links">
                <?php
                adminLoginSection();
                ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <section class="members">
        <h2>Members Management</h2>
        <div style="text-align: center;">
            <label><input type="text" placeholder="Search members..."></label>
            <button>Search</button>
            <!--search feature-->

        </div>
        <div class="member-table">
            <table>
                <tr>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Email</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();
                $sql = "SELECT memberID, memberName, email, joinDate FROM members";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-member-id='" . htmlspecialchars($row["memberID"]) . "'>" . str_repeat('*', strlen($row["memberID"])) . "</td>";
                        echo "<td>" . $row["memberName"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["joinDate"] . "</td>";
                        echo "<td><a href='action_members.php?memberID=" .$row['memberID']. "&action=edit'><button>Edit</button></a><a href='action_members.php?memberID=" .$row['memberID']. "&action=delete'><button>Delete</button></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-results'>No members found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>
