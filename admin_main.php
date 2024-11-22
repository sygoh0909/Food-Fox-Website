<?php
ob_start();
include ('cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Main Page</title>
    <style>
        .admin-dashboard {
            padding: 20px;
            text-align: center;
        }

        .admin-dashboard h2 {
            font-size: 28px;
            color: #5C4033;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            background-color: #C5B4A5;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .stats p {
            flex: 1 1 200px;
            background: #FFF;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px;
        }

        .stats p span {
            display: block;
            font-size: 36px;
            font-weight: bold;
            color: #5C4033;
            margin-top: 10px;
        }

        .stats p:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .stats p button {
            background-color: #5C4033;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .stats p button:hover {
            background-color: #7B5A46;
        }

        .button-links {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button-links a {
            text-decoration: none;
        }

        .button-links button {
            background-color: #5C4033;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-links button:hover {
            background-color: #7B5A46;
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
    <section class="admin-dashboard">
        <h2>Admin Dashboard Overview</h2>
        <div class="stats">
            <p>Total Members
                <span>
                    <?php
                    $conn = connection();
                    $sql = "SELECT COUNT(*) AS total_members FROM members";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row["total_members"];
                    } else {
                        echo "0";
                    }
                    ?>
                </span>
            </p>
            <p>Total Events
                <span>
                    <?php
                    $sql = "SELECT COUNT(*) AS total_events FROM events";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row["total_events"];
                    } else {
                        echo "0";
                    }
                    ?>
                </span>
            </p>
            <p>Total Donations
                <span>
                    <?php
                    $sql = "SELECT COUNT(*) AS total_donations FROM donations";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row["total_donations"];
                    } else {
                        echo "0";
                    }
                    ?>
                </span>
            </p>
        </div>
        <div class="button-links">
            <a href="admin_members.php"><button>View Members</button></a>
            <a href="admin_events.php"><button>View Events</button></a>
            <a href="admin_donations.php"><button>View Donations</button></a>
        </div>
    </section>
</main>
</body>
</html>
