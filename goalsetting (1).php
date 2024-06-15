<?php
session_start(); // Start the session

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

// Assuming user authentication is performed and user_id is available in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Retrieve the username from the database based on the user_id
    $sql = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Store the username in the session
        $_SESSION['username'] = $row['username'];
    } else {
        echo "User not found in the database.";
    }
    $stmt->close();
}

// Check if the form is submitted
if (isset($_POST['goalSubmit'])) {
    // Check if the username is set in the session
    if (isset($_SESSION['username'])) {
        $goalType = $_POST['goal-type'];
        $goalFocus = $_POST['goal-focus'];
        $startDate = $_POST['start-date'];
        $endDate = $_POST['end-date'];
        $workout = $_POST['select-workout'];
        $username = $_SESSION['username']; // Get the username from the session

        // Validate dates client-side again to ensure security
        if (strtotime($startDate) > strtotime($endDate)) {
            echo "<script>alert('End date cannot be before the start date. Please correct and resubmit.');</script>";
        } else {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO goalsetting (goal_type, goal_focus, start_date, end_date, selected_workout, username) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $goalType, $goalFocus, $startDate, $endDate, $workout, $username);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Goal set successfully!');</script>";
            } else {
                echo "Error setting goal: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "Username not found in session."; // Handle the case where username is not in session
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitplus - Fitness Goal Setting</title>
<style>
        nav {
    background-color: transparent; /* Transparent background */
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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://img.pikbest.com/ai/illus_our/20230422/ca48cc2ea0c23c387add249759ba3a33.jpg!w700wp');
            background-size: cover;
            background-position: center;
            color: #FFA500;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #FFA500;
            font-family: 'Times New Roman', Times, serif;
            font-size: 36px;
            text-shadow: 2px 2px 4px #000000;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(0, 0, 0, 0.7); /* Transparent Black */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.5); /* Orange */
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #FFA500;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #FFA500;
            background-color: rgb(255, 255, 255); /* Transparent Black */
            color: #76746e;
        }

        button {
            background-color: #FFA500;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #D2691E;
        }

        p {
            color: #FFA500;
            font-style: italic;
        }

        #success-message {
            display: none;
            text-align: center;
            margin-top: 20px;
            color: #32CD32; /* Green */
        }

    </style>
</head>
</div>
<nav>
    <ul class="nav__links">
        <li class="link"><a href="profile.php">BACK TO PROFILE</a></li>
    </ul>
</nav>
<body>
    <h1>Set Your Fitness Goals</h1>

    <form id="goal-form" action="goalsetting.php" method="post" onsubmit="return validateDates()">
        <div>
            <label for="goal-type">Goal Type:</label>
            <select id="goal-type" name="goal-type" required>
                <option value="" hidden>Select a goal type</option>
                <option value="weight-loss">Weight Loss</option>
                <option value="weight-gain">Weight Gain</option>
            </select>
        </div>

        <div>
            <label for="goal-focus">Goal Focus:</label>
            <select id="goal-focus" name="goal-focus" required>
                <option value="" hidden>Select a goal focus</option>
                <option value="muscle-gain">Muscle Gain</option>
                <option value="fat-loss">Fat Loss</option>
            </select>
        </div>

        <div>
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start-date" required>
        </div>

        <div>
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end-date" required>
        </div>

        <div>
            <label for="select-workout">Select Workout:</label>
            <select id="select-workout" name="select-workout" required>
                <option value="" hidden>Workout to focus tracking</option>
                <option value="pushups">Pushups</option>
                <option value="pullups">Pullups</option>
                <option value="cardio">Cardio</option>
                <option value="squats">Squats</option>
            </select>
        </div>
        
        <div>
            <button type="submit" name="goalSubmit">Set Goal</button>
        </div>
    </form>

    <script>
        function validateDates() {
            var startDate = document.getElementById('start-date').value;
            var endDate = document.getElementById('end-date').value;

            if (startDate > endDate) {
                alert("End date cannot be before the start date.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
