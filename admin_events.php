<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Events Management Page</title>

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
        .events {
            padding: 20px 30px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
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
        .upcoming-events-table {
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
    <section class="events">
        <h2>Events Management</h2>
        <div style="text-align: center;">
            <label><input type="text" placeholder="Search events..."></label>
            <button>Search</button> <!--search feature-->
            <a href="newevent.php"><button id="button1">Add New Event</button></a>
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
                        echo "<td>" . $row["eventID"] . "</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["totalRegistrations"] . "</td>";
                        echo "<td><a href='newevent.php?eventID=" .$row['eventID']. "&action=edit'><button>Edit</button></a><a href='newevent.php?eventID=" .$row['eventID']. "&action=delete'><button>Delete</button></a><button>View Registrations</button></td>";
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
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT e.eventID, e.eventName, COUNT(r.registrationID) AS totalRegistrations FROM events e LEFT JOIN registrations r ON e.eventID = r.eventID WHERE e.eventStatus='Past' GROUP BY e.eventID, e.eventName";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["eventID"] . "</td>";
                        echo "<td>" . $row["eventName"] . "</td>";
                        echo "<td>" . $row["totalRegistrations"] . "</td>";
                        echo "<td><a href='action_event.php?eventID=" .$row['eventID']. "&action=edit'><button>Edit</button></a><a href='action_event.php?eventID=" .$row['eventID']. "&action=delete'><button>Delete</button></a><button>View Registrations</button></td>";
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
