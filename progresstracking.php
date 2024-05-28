<?php
$servername = "localhost";
$username = "FitPlus"; // Update with your database username
$password = "-kLpc_0I_HFlm1G4"; // Update with your database password
$dbname = "fitplus"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submitWorkout'])) {
    $date = $_POST['date'];
    $workout = $_POST['workout'];
    $duration = $_POST['duration'];

    // Calculate calories burned (replace with your own logic if needed)
    $caloriesBurned = $duration * 10; // Example: 10 calories burned per minute

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO workouts (date, workout, duration, calories_burned) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $date, $workout, $duration, $caloriesBurned);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Workout added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
