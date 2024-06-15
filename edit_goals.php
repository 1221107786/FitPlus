<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit();
}

// Database connection details
$servername = "localhost";
$username = "FitPlus";
$password = "T24icnPT46C*54Od";
$dbname = "fitplus";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Retrieve the user's goals
$sql = "SELECT goalsetting.id, goal_type, goal_focus, start_date, end_date, selected_workout 
        FROM goalsetting
        INNER JOIN users ON goalsetting.username = users.username
        WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<nav>
    <ul class="nav__links">
        <li class="link"><a href="profile.php">BACK TO PROFILE</a></li>
    </ul>
</nav>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Goals</title>
    <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('https://img.pikbest.com/ai/illus_our/20230422/ca48cc2ea0c23c387add249759ba3a33.jpg!w700wp');
    background-size: cover;
    background-position: center;
    color: #FFF; /* White text color */
}

/* Navigation bar styles */
nav {
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff; /* White text color */
    padding: 1rem; /* Padding around the navigation */
    display: flex; /* Display flex container */
    justify-content: space-between; /* Space between logo and links */
    align-items: center; /* Center align items vertically */
}

.nav__logo img {
    width: 100px; /* Adjust logo width as needed */
}

.nav__links {
    list-style: none; /* Remove default list styles */
    display: flex; /* Display links in a row */
}

.nav__links li {
    margin-left: 20px; /* Space between list items */
}

.nav__links a {
    color: #fff; /* White text color */
    text-decoration: none; /* Remove underline from links */
    font-weight: bold; /* Bold text */
}

.nav__links a:hover {
    text-decoration: underline; /* Underline on hover */
}

/* Main container styles */
.container {
    background-color: rgba(0, 0, 0, 0.7);
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px; /* Rounded corners for the container */
}

/* Header styles */
h1 {
    text-align: center;
    color: #FFA500; /* Orange */
}

/* Table styles */
table {
    width: 50%;
    border-collapse: collapse;
    margin: 20px auto; /* Center the table */
    color: #FFF; /* White text color for table content */
}

table thead {
    background-color: #FFA500; /* Orange background for table headers */
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

table th {
    text-transform: uppercase;
    font-weight: bold;
}

table tbody tr:nth-child(even) {
    background-color: rgba(255, 165, 0, 0.1); /* Light orange background for even rows */
}

table tbody tr:hover {
    background-color: rgba(255, 165, 0, 0.2); /* Darker orange background on hover */
}

a {
    color: #FFA500; /* Orange text color */
    text-decoration: none;
}

a:hover {
    text-decoration: underline; /* Underline on hover */
}

/* Message styles */
.message {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    background-color: rgba(255, 165, 0, 0.1); /* Light orange background */
    color: #FFF; /* White text color */
}

/* Form styles */
form {
    margin-bottom: 20px;
}

input[type="date"], input[type="text"], input[type="number"], select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #FFA500; /* Orange */
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #fffefe;
    color: #7e786a;
}

button {
    background-color: #FFA500; /* Orange */
    color: #111; /* Black */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #D2691E; /* Dark Orange */
}

canvas {
    max-width: 100%;
    margin-bottom: 20px;
}

</style>
</head>
<body>
    <h1>Edit Goals</h1>
    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<thead><tr><th>Goal Type</th><th>Goal Focus</th><th>Start Date</th><th>End Date</th><th>Selected Workout</th><th>Actions</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['goal_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['goal_focus']) . '</td>';
            echo '<td>' . htmlspecialchars($row['start_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['selected_workout']) . '</td>';
            echo '<td><a href="edit_goal_form.php?id=' . $row['id'] . '">Edit</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No goals set yet.";
    }
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
