<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit();
}

// Database connection details
$servername = "localhost"; // Replace with your server name
$username = "FitPlus"; // Replace with your database username
$password = "-kLpc_0I_HFlm1G4"; // Replace with your database password
$dbname = "fitplus"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Retrieve the user's username
$sql = "SELECT username, lastname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username']; // Retrieve the username from the database
    $name = $row['username'] . " " . $row['lastname'];
} else {
    $name = "User";
    $username = ""; // Set username to empty string if not found
}

$stmt->close();

// Retrieve the user's goals
$sql = "SELECT goal_type, goal_focus, start_date, end_date, selected_workout 
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
<style>
 {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Basic styles for the body */
body {
    background: url('https://t4.ftcdn.net/jpg/02/51/45/49/360_F_251454966_MSoiZITSgkSgIs2qGr1SnfJOYdhd6ieJ.jpg') no-repeat center center/cover;
    color: #fff;
    line-height: 1.6;
}

/* Styles for the navigation bar */
nav {
    background-color: transparent; /* Transparent background */
    color: #fff;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav__logo img {
    width: 100px;
}

.nav__links {
    list-style: none;
    display: flex;
}

.nav__links li {
    margin-left: 20px;
}

.nav__links a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

.nav__links a:hover {
    text-decoration: underline;
}

/* Styles for the header */
.header__container {
    background: rgba(0, 0, 0, 0.5); /* Add a semi-transparent black overlay */
    height: 50vh;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.header__content {
    position: relative;
    z-index: 1;
}

.user-name {
    font-size: 3.0rem; /* Bigger font size */
    margin-bottom: 0.5rem;
}

/* Styles for sections */
.section__container {
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.section__container h2 {
    margin-bottom: 1.5rem;
    color: #ff9800; /* Orange color for headings */
    text-align: center;
}

.section__container p {
    margin-bottom: 1rem;
    line-height: 1.5;
}

.section__container hr {
    margin: 2rem 0;
    border: 1px solid #ff9800; /* Orange color for hr */
}

/* Add some styles for mobile responsiveness */
@media (max-width: 768px) {
    .nav__links {
        flex-direction: column;
    }

    .nav__links li {
        margin: 10px 0;
    }

    .header__container {
        height: 30vh;
    }

    .user-name {
        font-size: 2.5rem; /* Adjusted for mobile */
    }

    .user-lastname {
        font-size: 1rem;
    }
}

.chart-container {
            width: 80%;
            margin: auto;
        }
</style>
    <title>Dashboard</title>
</head>
<body>
    <nav>
        <div class="nav__logo">
            <a href="#"><img src="https://dcassetcdn.com/design_img/3571610/515701/515701_19560887_3571610_166c5b12_image.jpg" alt="logo" /></a>
        </div>
        <ul class="nav__links">
            <li class="link"><a href="logout.php">Log Out</a></li>
            <li class="link"><a href="goalsetting.php">Goal Setting</a></li>
            <li class="link"><a href="progresstracking.php">Progress Tracking</a></li>
        </ul>
    </nav>

    <header class="section__container header" id="home">
         <?php
                echo "<h4 class='user-name'>Welcome, " . htmlspecialchars($name) . "!</h4>";
                ;
            ?>
    
    </header>
    
    <section class="section__container goals__container">
        <h2>Goal Setting</h2>
        <?php
        // Check if there are any goals set by the user
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Display the goal information in HTML format
                echo "<p>Goal Type: " . htmlspecialchars($row['goal_type']) . "</p>";
                echo "<p>Goal Focus: " . htmlspecialchars($row['goal_focus']) . "</p>";
                echo "<p>Start Date: " . htmlspecialchars($row['start_date']) . "</p>";
                echo "<p>End Date: " . htmlspecialchars($row['end_date']) . "</p>";
                echo "<p>Selected Workout: " . htmlspecialchars($row['selected_workout']) . "</p>";
                echo "<hr>";
            }
        } else {
            echo "No goals set yet.";
        }
        $stmt->close();
        ?>
    </section>

    <section class="section__container workouts__container">
        <h2>Progress Tracking</h2>
        <?php
        // Retrieve the user's workouts
        $sql = "SELECT date, workout, duration, calories_burned 
                FROM workouts 
                WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are any workouts
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Display the workout information in HTML format
                echo "<p>Date: " . htmlspecialchars($row['date']) . "</p>";
                echo "<p>Workout: " . htmlspecialchars($row['workout']) . "</p>";
                echo "<p>Duration: " . htmlspecialchars($row['duration']) . " minutes</p>";
                echo "<p>Calories Burned: " . htmlspecialchars($row['calories_burned']) . " kcal</p>";
                echo "<hr>";
            }
        } else {
            echo "No workouts logged yet.";
        }
        $stmt->close();
        ?>
    </section>

    <section class="section__container chart__container">
        <h2>Workout Progress</h2>
        <?php
$username = "$name"; // Assuming you have a session or a variable with the username

$sql = "SELECT date, workout, duration, calories_burned 
        FROM workouts 
        WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Process the workout data
$dataPoints = array_fill(1, 7, 0); // Initialize an array for 7 days (1 to 7)

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = new DateTime($row['date']);
        $dayOfWeek = $date->format('N'); // Get the day of the week as a number (1 for Monday, 7 for Sunday)

        // Accumulate the calories burned for each day
        $dataPoints[$dayOfWeek] += $row['calories_burned'];
    }
} else {
    echo "No workouts logged yet.";
}

$stmt->close();
$conn->close();

// Prepare data points for the chart
$chartDataPoints = array();
for ($i = 1; $i <= 7; $i++) {
    $chartDataPoints[] = array(
        "y" => $dataPoints[$i],
        "label" => (string)$i // Use numerical labels 1, 2, 3, etc.
    );
}
?>

<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
    title: {
        text: "Calories Burned Over a Week"
    },
    axisX: {
        title: "Day",
        interval: 1,
        valueFormatString: "0" // Ensure the x-axis labels are shown as numbers
    },
    axisY: {
        title: "Calories Burned"
    },
    data: [{
        type: "line",
        dataPoints: <?php echo json_encode($chartDataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>