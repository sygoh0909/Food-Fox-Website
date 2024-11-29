<?php
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Add/Edit/Delete Event Page</title>

    <style>
        .registrations {
            padding: 20px 30px;
        }
        .registration-table {
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
    <section class="registrations">
        <h2>Registrations Management</h2>
        <div class="search">
            <label><input type="text" placeholder="Search registrations..." </label>
            <button>Search</button>
        </div>
        <div class="registration-table">
            <table>
                <tr>
                    <th>Registration ID</th>
                    <th>Event Name</th>
                    <th>Member Name</th>
                    <th>Register Type</th>
                    <th>Registration Date</th> <!--include attendance???-->
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();
                $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
                $registrationID = null;

                if ($eventID){
                    $sql = "SELECT r.*, e.eventName, m.memberName from registrations r, events e, members m WHERE r.eventID = e.eventID AND r.memberID = m.memberID AND r.eventID = '$eventID'";
                    $result = mysqli_query($conn, $sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-registration-id='" .htmlspecialchars($row["registrationID"]) ."'>" .str_repeat('*', strlen($row["registrationID"]))."</td>";
                            echo "<td>" . $row["eventName"] . "</td>";
                            echo "<td>" . $row["memberName"] . "</td>";
                            echo "<td>" . $row["registerType"] . "</td>";
                            echo "<td>" . $row["registrationDate"] . "</td>";
                            //idk the attendance
                            echo "<td><a href='action_registrations.php?registrationID=" .$row['registrationID']." &action=edit'><button>Edit</button></a><a href='action_registrations.php?registrationID=" .$row['registrationID']." &action=delete'><button>Delete</button></a></td>";
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>

