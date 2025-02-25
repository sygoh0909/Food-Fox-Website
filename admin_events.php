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
    <title>Admin Events Management Page</title>

    <style>
        .events {
            padding: 20px 30px;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        h3{
            margin-top: 30px;
            text-align: center;
        }

        .upcoming-events-table, .past-events-table {
            margin-top: 20px;
            overflow-x: auto;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 230px;
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
    <section class="events">
        <h2>Events Management</h2>
        <div class="search">
            <form method="get" action="">
                <label><input type="text" name="search" placeholder="Search by event name or status..."></label>
                <button type="submit">Search</button> <!--search feature-->
                <a href="action_event.php?action=add"><button id="button1" type="button">Add New Event</button></a>
            </form>
        </div>
        <div class="upcoming-events-table">
            <table>
                <h3>Upcoming Events</h3>
                <tr>
                    <th>Event ID</th>
                    <th>Event Name</th>
                    <th>Total Registrations</th>
                    <th>Actions</th>
                    <th>Attendance Link</th>
                </tr>
                <?php
                $conn = connection();

                $searchQuery = $_GET['search'] ?? '';
                $upcomingSql = "SELECT e.eventID, e.eventName, COUNT(r.registrationID) AS totalRegistrations FROM events e LEFT JOIN registrations r ON e.eventID = r.eventID WHERE e.eventStatus = 'Upcoming'";

                if (!empty ($searchQuery)) {
                    $upcomingSql .= " AND (e.eventName LIKE '%$searchQuery%' OR e.eventStatus LIKE '%$searchQuery%')";
                }

                $upcomingSql .= " GROUP BY e.eventID, e.eventName";
                $result = $conn->query($upcomingSql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-event-id='" .htmlspecialchars($row["eventID"]) ."'>" .str_repeat('*', strlen($row["eventID"]))."</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["totalRegistrations"] . "</td>";
                        echo "<td><a href='action_event.php?eventID=" .$row['eventID']. "&action=edit'><button>Edit</button></a><a href='action_event.php?eventID=" .$row['eventID']. "&action=delete'><button>Delete</button></a><a href='admin_registrations.php?eventID=" .$row['eventID']."'><button>View Registrations</button></a></td>";
                        echo "<td><a href='generateAttendanceLink.php?eventID=" .$row['eventID'] ."'><button>Generate Attendance Link</button></a>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-results'>No upcoming events found.</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="past-events-table">
            <table>
                <h3>Past Events</h3>
                <tr>
                    <th>Event ID</th>
                    <th>Event Name</th>
                    <th>Attendees</th> <!--count for ppl attended-->
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();

                $searchQuery = $_GET['search'] ?? '';
                $pastSql = "SELECT e.eventID, e.eventName, COUNT(r.attendance) AS attendees FROM events e LEFT JOIN registrations r ON e.eventID = r.eventID AND r.attendance = 1 WHERE e.eventStatus = 'Past'";

                if (!empty ($searchQuery)){
                    $pastSql .= " AND (e.eventName LIKE '%$searchQuery%' OR e.eventStatus LIKE '%$searchQuery%')";
                }
                $pastSql .= " GROUP BY e.eventID, e.eventName";
                $result = $conn->query($pastSql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-event-id='" .htmlspecialchars($row["eventID"]) ."'>" .str_repeat('*', strlen($row["eventID"]))."</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["attendees"] . "</td>";
                        echo "<td><a href='action_event.php?eventID=" .$row['eventID']. "&action=editPast'><button>Edit</button></a><a href='action_event.php?eventID=" .$row['eventID']. "&action=deletePast'><button>Delete</button></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-results'>No past events found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>
