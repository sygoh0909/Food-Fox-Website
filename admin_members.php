<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Members Management Page</title>

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
        .members {
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
        .member-table {
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
    <section class="members">
        <h2>Members Management</h2>
        <div style="text-align: center;">
            <label><input type="text" placeholder="Search members..."></label>
            <button>Search</button>
            <a href="newmember.php"><button id="button1">Add New Member</button></a>
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
                $dbhost = "localhost";
                $dbuser = "root";
                $dbpass = "";
                $dbname = "foodfoxdb";
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT memberID, memberName, email, joinDate FROM members";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["memberID"] . "</td>";
                        echo "<td>" . $row["memberName"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["joinDate"] . "</td>";
                        echo "<td><button>Edit</button><button>Delete</button><button>View Full Details</button></td>";
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
