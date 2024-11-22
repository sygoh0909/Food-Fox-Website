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
    <title>Admin Events Management Page</title>

    <style>
        .events {
            padding: 20px 30px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .upcoming-events-table {
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
    <section class="events">
        <h2>Events Management</h2>
        <div class="search">
            <label><input type="text" placeholder="Search events..."></label>
            <button>Search</button> <!--search feature-->
            <a href="action_event.php?action=add"><button id="button1">Add New Event</button></a>
        </div>
        <div class="upcoming-events-table">
            <table>
                <tr>
                    <th>Upcoming Event ID</th>
                    <th>Upcoming Event Name</th>
                    <th>Total Registrations</th>
                    <th>Actions</th>
                </tr>
                <?php
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT e.eventID, e.eventName, COUNT(r.registrationID) AS totalRegistrations FROM events e LEFT JOIN registrations r ON e.eventID = r.eventID WHERE e.eventStatus='Upcoming' GROUP BY e.eventID, e.eventName";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-event-id='" .htmlspecialchars($row["eventID"]) ."'>" .str_repeat('*', strlen($row["eventID"]))."</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["totalRegistrations"] . "</td>";
                        echo "<td><a href='action_event.php?eventID=" .$row['eventID']. "&action=edit'><button>Edit</button></a><a href='action_event.php?eventID=" .$row['eventID']. "&action=delete'><button>Delete</button></a><a href='admin_registrations.php?eventID=" .$row['eventID']."'><button>View Registrations</button></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-results'>No upcoming events found.</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>
        <div class="past-events-table">
            <table>
                <tr>
                    <th>Past Event ID</th>
                    <th>Past Event Name</th>
                    <th>Total Registrations</th>
                    <th>Actions</th>
                </tr>
                <?php
                $conn = connection();
                $sql = "SELECT e.eventID, e.eventName, COUNT(r.registrationID) AS totalRegistrations FROM events e LEFT JOIN registrations r ON e.eventID = r.eventID WHERE e.eventStatus='Past' GROUP BY e.eventID, e.eventName";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td data-event-id='" .htmlspecialchars($row["eventID"]) ."'>" .str_repeat('*', strlen($row["eventID"]))."</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["totalRegistrations"] . "</td>";
                        echo "<td><a href='action_event.php?eventID=" .$row['eventID']. "&action=editPast'><button>Edit</button></a><a href='action_event.php?eventID=" .$row['eventID']. "&action=deletePast'><button>Delete</button></a><a href='admin_registrations.php?eventID=" .$row['eventID']."'><button>View Registrations</button></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-results'>No past events found.</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>
