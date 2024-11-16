<?php
include ('cookie.php');
$visitCount = cookie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit/Delete Event Page</title>

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
        .registrations {
            padding: 20px 30px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .search{
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
        .registration-table {
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
                    <th>Event ID</th>
                    <th>Member ID</th>
                    <th>Register Type</th>
                    <th>Registration Date</th> <!--include attendance???-->
                    <th>Actions</th>
                </tr>
                <?php
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    die ("Connection failed: " . $conn->connect_error);
                }
                $eventID = isset($_GET['eventID']) ? $_GET['eventID'] : null;
                $registrationID = null;

                if ($eventID){
                    $sql = "SELECT * FROM registrations WHERE eventID = '$eventID'";
                    $result = mysqli_query($conn, $sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["registrationID"] . "</td>";
                            echo "<td>" . $row["eventID"] . "</td>";
                            echo "<td>" . $row["memberID"] . "</td>";
                            echo "<td>" . $row["registerType"] . "</td>";
                            echo "<td>" . $row["registrationDate"] . "</td>";
                            //idk the attendance
                            echo "<td><a href='action_registrations?registrationID=" .$row['registrationID']." &action=edit'><button>Edit</button></a><a href='action_registrations.php?registrationID=" .$row['registrationID']." &action=delete'><button>Delete</button></a></td>";
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

