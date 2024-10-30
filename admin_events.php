<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Events Management Page</title>
</head>
<body>
<header>
    <nav>
        <div class="navbar">
            <div class="main-links">
                <a href="admin_main.php">Home</a>
                <a href="admin_events.php">Events</a>
                <a href="damin_volunteers.php">Volunteers</a>
                <a href="admin_donations.php">Donation</a>
            </div>
        </div>
    </nav>
</header>
<main>
   <section class="events">
       <h2>Events Management</h2>
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
               $sql = "SELECT e.eventID, e.eventName, COUNT(r.eventID) AS totalRegistrations FROM events e, registrations r WHERE e.eventID = r.eventID AND e.eventStatus='Upcoming' GROUP BY e.eventID, e.eventName";
               $result = $conn->query($sql);
               if ($result->num_rows > 0) {
                   while($row = $result->fetch_assoc()) {
                       echo "<tr>";
                       echo "<td>" . $row["eventID"] . "</td>";
                       echo "<td>" . $row["eventName"] . "</td>";
                       echo "<td>" . $row["totalRegistrations"] . "</td>";
                       echo "<td><button>Edit</button></td>";
                       echo "</tr>";
                   }
               }
               else{
               }
               $conn->close();
               ?>
           </table>
       </div>
   </section>
</main>
</body>
<style>
    body{
        background-color: #F5EEDC;
        margin: 0;
        font-family: Arial, sans-serif;
    }
    .navbar{
        padding: 15px 20px;
        background-color: #5C4033;
    }
    .main-links{
        display: flex;
        justify-content: center;
        gap: 40px;
    }
    .main-links a{
        text-decoration: none;
        color: white;
    }
</style>
</html>
