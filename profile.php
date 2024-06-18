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
$password = "T24icnPT46C*54Od"; // Replace with your database password
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->

    <style>
body {
    background: url('https://t4.ftcdn.net/jpg/02/51/45/49/360_F_251454966_MSoiZITSgkSgIs2qGr1SnfJOYdhd6ieJ.jpg') no-repeat center center/cover;
    color: #fff;
    line-height: 1.6;
    font-family: Arial, sans-serif;
}

nav {
    color: #fff;
    display: flex;
    justify-content: flex-start; /* Align items to the left */
    align-items: center;
    padding: 1rem;
    position: relative;
}



.nav__links {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: none; /* Initially hide the navigation links */
    flex-direction: column; /* Stack links vertically */
    background-color: rgba(0, 0, 0, 0.7); /* Transparent background */
    position: absolute;
    top: 100%; /* Position it below the nav bar */
    left: 0;
    width: 50%; /* Full width */
    padding: 1rem; /* Padding for links */
}

.nav__links.open {
    display: flex; /* Show links when the open class is applied */
}

.nav__links .link {
    margin: 10px 0; /* Adjust spacing between links */
}

.nav__links .link a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav__links .link a:hover {
    color: orange; /* Adjust hover color */
}

.settings-image {
    cursor: pointer;
    background: url('https://cdn0.iconfinder.com/data/icons/apple-apps/100/Apple_Settings-512.png') no-repeat center center; /* Use the settings button image */
    background-size: contain; /* Contain the image within the element */
    width: 80px; /* Set width */
    height: 80px; /* Set height */
    border: none; /* Remove default button border */
    outline: none; /* Remove default button outline */
}

.settings-image:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Slightly change background on hover */
}

.settings-text {
            font-family: 'Roboto', sans-serif; /* Roboto font */
            font-size: 20px;
            color: white;
            font-weight: bold;
            margin-left: 5px;
            vertical-align: middle;
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

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

table th, table td {
    border: 1px solid #333; /* Changed border color to black */
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #ff9800; /* Orange background */
    font-weight: bold;
    color: #fff; /* White text color */
}

table tbody tr:nth-child(even) {
    background-color: #222; /* Dark background for even rows */
    color: #fff; /* White text color */
}

table tbody tr:nth-child(odd) {
    background-color: #333; /* Darker background for odd rows */
    color: #fff; /* White text color */
}

        .header__container {
            background: gba(0, 0, 0, 0.5);
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
            font-size: 3.0rem;
            margin-bottom: 0.5rem;
        }

        .section__container {
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .section__container h2 {
            margin-bottom: 1.5rem;
            color: #ff9800;
            text-align: center;
        }

        .section__container p {
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .section__container hr {
            margin: 2rem 0;
            border: 1px solid #ff9800;
        }

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
                font-size: 2.5rem;
            }

            .user-lastname {
                font-size: 1rem;
            }
        }

        .chart-container {
            width: 80%;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        table th, table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #ff9800;
            font-weight: bold;
            color: #fff;
        }

        table tbody tr:nth-child(even) {
            background-color: #222;
            color: #fff;
        }

        table tbody tr:nth-child(odd) {
            background-color: #333;
            color: #fff;
        }
    </style>
    <title>Dashboard</title>
</head>
<body>

    <nav>
        <ul class="nav__links" id="navLinks">
        <li class="link"><a href="profile.php">CLOSE SETTINGS</a></li>
            <li class="link"><a href="goalsetting.php">ADD GOALS</a></li>
            <li class="link"><a href="progresstracking.php">ADD PROGRESS</a></li>
            <li class="link"><a href="display_goals.php">DELETE GOALS</a></li>
            <li class="link"><a href="delete_progress.php">DELETE PROGRESS</a></li>
            <li class="link"><a href="edit_goals.php">EDIT GOAL</a></li>
            <li class="link"><a href="edit_progress.php">EDIT PROGRESS</a></li>
            <li class="link"><a href="logout.php">LOG OUT</a></li>
            <li class="link"><a href="delete_account.php">DELETE ACCOUNT</a></li>
        </ul>
        <div class="settings-container" id="settingsButton">
        <div id="overlay"></div>
    <div class="settings-image"></div>
    <span class="settings-text">SETTINGS</span>
</div>


    </nav>
    <header class="section__container header" id="home">
         <?php
                echo "<h4 class='user-name'>Welcome, " . htmlspecialchars($name) . "!</h4>";
                ;
            ?>
    
    </header>
    
    <section class="section__container goals__container">
    <h2>GOALS</h2>
       
    <?php
    
    $sql_goals = "SELECT goal_type, goal_focus, start_date, end_date, selected_workout 
                  FROM goalsetting
                  INNER JOIN users ON goalsetting.username = users.username
                  WHERE users.id = ?";
    $stmt_goals = $conn->prepare($sql_goals);
    $stmt_goals->bind_param("i", $user_id);
    $stmt_goals->execute();
    $result_goals = $stmt_goals->get_result();

    if ($result_goals->num_rows > 0) {
        echo '<table>';
        echo '<thead><tr><th>Goal Type</th><th>Goal Focus</th><th>Start Date</th><th>End Date</th><th>Selected Workout</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result_goals->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['goal_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['goal_focus']) . '</td>';
            echo '<td>' . htmlspecialchars($row['start_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['selected_workout']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No goals set yet.";
    }
    $stmt_goals->close();
    ?>
</section>




<section class="section__container workouts__container">
    <h2>PROGRESS TRACKING</h2>
    <?php
    // Retrieve the user's workouts
    $sql_workouts = "SELECT date, workout, duration, calories_burned 
                     FROM workouts 
                     WHERE username = ?";
    $stmt_workouts = $conn->prepare($sql_workouts);
    $stmt_workouts->bind_param("s", $username);
    $stmt_workouts->execute();
    $result_workouts = $stmt_workouts->get_result();

    if ($result_workouts->num_rows > 0) {
        echo '<table>';
        echo '<thead><tr><th>Date</th><th>Workout</th><th>Duration (minutes)</th><th>Calories Burned (kcal)</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result_workouts->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['workout']) . '</td>';
            echo '<td>' . htmlspecialchars($row['duration']) . '</td>';
            echo '<td>' . htmlspecialchars($row['calories_burned']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No workouts logged yet.";
    }
    $stmt_workouts->close();
    ?>
</section>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Step 2: Include Chart.js -->
</head>
<body>
    <section class="section__container workouts__cont">
        <h2>Progress Chart</h2>
        <?php
        // Retrieve the user's workouts
        $sql = "SELECT date, workout, duration, calories_burned 
                FROM workouts 
                WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize an array to hold the workout data
        $workoutData = [];

        // Check if there are any workouts
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Store each row of data in the array
                $workoutData[] = [
                    'date' => $row['date'],
                    'workout' => $row['workout'],
                    'duration' => $row['duration'],
                    'calories_burned' => $row['calories_burned']
                ];
            }
        }

        // Close the statement
        $stmt->close();

        // Output the data as a JSON object
        echo "<script>var workoutData = " . json_encode($workoutData) . ";</script>";
        ?>
        <canvas id="workoutChart" width="400" height="200"></canvas> <!-- Step 3: Add canvas element -->
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Parse the workout data
        const labels = workoutData.map(item => item.date);
        const durations = workoutData.map(item => item.duration);
        const calories = workoutData.map(item => item.calories_burned);

        const ctx = document.getElementById('workoutChart').getContext('2d');
        const workoutChart = new Chart(ctx, {
            type: 'bar', // Choose the type of chart: line, bar, etc.
            data: {
                labels: labels,
                datasets: [{
               label: 'Duration (minutes)',
               data: durations,
               borderColor: 'rgba(39, 78, 166, 1)', // Dark blue border
               backgroundColor: 'rgba(39, 78, 166, 1)', // Dark blue background (opaque)
               fill: false,
               yAxisID: 'y-axis-1',
             }, {
             label: 'Calories Burned (kcal)',
             data: calories,
             borderColor: 'rgba(153, 0, 0, 1)', // Dark red border
             backgroundColor: 'rgba(153, 0, 0, 1)', // Dark red background (opaque)
             fill: false,
             yAxisID: 'y-axis-2',
             }]


            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        id: 'y-axis-1',
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Duration (minutes)'
                        }
                    }, {
                        id: 'y-axis-2',
                        type: 'linear',
                        position: 'right',
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Calories Burned (kcal)'
                        }
                         
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }]
                }
            }
        });
    });
    </script>

<script>
        // JavaScript to toggle the navigation links visibility
        $(document).ready(function() {
            $("#settingsButton").click(function() {
                $("#navLinks").toggleClass("open");
            });
        });
    </script>
</body>
</html>
