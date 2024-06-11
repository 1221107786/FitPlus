<?php
session_start(); // Start the session

$servername = "localhost";
$username = "FitPlus";
$password = "-kLpc_0I_HFlm1G4";
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
if (isset($_POST['submitWorkout'])) {
    // Check if the username is set in the session
    if (isset($_SESSION['username'])) {
    $date = $_POST['date'];
    $workout = $_POST['workout'];
    $duration = $_POST['duration'];
    $username = $_SESSION['username']; // Get the username from the session


    // Calculate calories burned (replace with your own logic if needed)
    $caloriesBurned = $duration * 10; // Example: 10 calories burned per minute
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO workouts (date, workout, duration, calories_burned, username) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdds", $date, $workout, $duration, $caloriesBurned, $username);



    // Execute the statement
    if ($stmt->execute()){
        echo "<script>alert('Workout added successfully!');</script>";
    } else {
        echo "Error adding workout: " . $stmt->error;
    }

   $stmt->close();
    }
    else {
        echo "Username not found in session."; // Handle the case where username is not in session
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
</div>
        <ul class="nav__links">
          <li class="link"><a href="profile.php">BACK TO PROFILE</a></li>
        </ul>
      </nav>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - Progress Tracking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #111;
            color: #FFA500; /* Orange */
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #FFA500; /* Orange */
        }
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
    <div class="container">
        <h1>Progress Tracking</h1>
        <form id="workout-form" action="progresstracking.php" method="post">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="workout">Workout:</label>
            <select id="workout" name="workout" required>
                <option value="pushups">Pushups</option>
                <option value="pullups">Pullups</option>
                <option value="cardio">Cardio</option>
                <option value="squats">Squats</option>
            </select>
            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" min="1" required>
            <button type="submit" name="submitWorkout">Add Workout</button>
        </form>
        <canvas id="calories-chart"></canvas>
        <canvas id="weekly-calories-chart"></canvas>
    </div>

  