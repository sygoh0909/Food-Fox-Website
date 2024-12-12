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
    <title>Add/Edit/Delete Event Page</title>

    <style>
        .registrations {
            padding: 20px 30px;
        }

        .registration-table {
            margin-top: 20px;
            overflow-x: auto;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 320px;
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
            <form method="get" action="">
                <label>
                    <input type="hidden" name="eventID" value="<?= isset($_GET['eventID']) ? $_GET['eventID'] : null ?>">
                    <input type="text" name="search" placeholder="Search by member name, register type, or date..."></label>
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="registration-table">
            <table>
                <tr>
                    <th>Registration ID</th>
                    <th>Event Name</th>
                    <th>Member Name</th>
                    <th>Register Type</th>
                    <th>Registration Date</th>
                    <th>Attendance</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();
                $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
                $searchQuery = isset($_GET['search']) ? $_GET['search'] : "";

                if ($eventID){
                    $sql = "SELECT r.*, e.eventName, m.memberName FROM registrations r JOIN events e ON r.eventID = e.eventID JOIN members m ON r.memberID = m.memberID WHERE r.eventID = '$eventID'";

                    if (!empty($searchQuery)){
                        $sql .= " AND (m.memberName LIKE '%$searchQuery%' OR r.registrationDate LIKE '%$searchQuery%' OR r.registerType LIKE '%$searchQuery%' OR r.attendance LIKE '%$searchQuery%')";
                    }

                    $result = mysqli_query($conn, $sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $dateFormatted = date('d-m-Y', strtotime($row["registrationDate"]));
                            echo "<tr>";
                            echo "<td data-registration-id='" .htmlspecialchars($row["registrationID"]) ."'>" .str_repeat('*', strlen($row["registrationID"]))."</td>";
                            echo "<td>" . $row["eventName"] . "</td>";
                            echo "<td>" . $row["memberName"] . "</td>";
                            echo "<td>" . $row["registerType"] . "</td>";
                            echo "<td>" . $dateFormatted . "</td>";
                            echo "<td>" . $row["attendance"] . "</td>";
                            echo "<td><a href='action_registrations.php?registrationID=" .$row['registrationID']." &action=edit'><button>Edit</button></a><a href='action_registrations.php?registrationID=" .$row['registrationID']." &action=delete'><button>Delete</button></a></td>";
                            echo "</tr>";
                        }
                    }
                    else{
                        echo "<tr><td colspan='6' class='no-results'>No registrations found.</td></tr>";
                    }
                }else{
                    echo "<tr><td colspan='6' class='no-results'>No event ID found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>

