<?php
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

// Check if the form is submitted
if (isset($_POST['goalSubmit'])) {
    $goalType = $_POST['goal-type'];
    $goalFocus = $_POST['goal-focus'];
    $startDate = $_POST['start-date'];
    $endDate = $_POST['end-date'];
    $workout = $_POST['select-workout'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO goals (goal_type, goal_focus, start_date, end_date, selected_workout) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $goalType, $goalFocus, $startDate, $endDate, $workout);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Goal set successfully!";
    } else {
        echo "Error setting goal: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
