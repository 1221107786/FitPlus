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

$progress_id = $_GET['id'];

// Retrieve the specific progress record
$sql = "SELECT date, workout, duration, calories_burned 
        FROM workouts
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $progress_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $progress = $result->fetch_assoc();
} else {
    echo "Progress record not found.";
    exit();
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $workout = $_POST['workout'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];

    // Update the progress
    $sql = "UPDATE workouts SET date = ?, workout = ?, duration = ?, calories_burned = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $date, $workout, $duration, $calories_burned, $progress_id);

    if ($stmt->execute()) {
        echo "Progress updated successfully!";
        echo "<script>window.location.href = 'edit_progress.php';</script>";
    } else {
        echo "Failed to update progress: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Progress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://img.pikbest.com/ai/illus_our/20230422/ca48cc2ea0c23c387add249759ba3a33.jpg!w700wp');
            background-size: cover;
            background-position: center;
            color: #FFF; /* White text color */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align items at the top */
            height: 100vh;
            padding-top: 20px; /* Add some padding at the top */
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            max-width: 400px; /* Reduced width */
            width: 100%; /* Ensure it takes full width up to max-width */
            padding: 20px;
            border-radius: 10px; /* Rounded corners for the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #FFA500; /* Orange */
            margin-bottom: 20px; /* Reduced space below the title */
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="date"], input[type="number"], select {
            width: 100%; /* Set width to 100% */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Add space above the table */
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

    </style>
</head>
<body>
<div class="container">
    <h1>Edit Progress</h1>
    <form action="edit_progress_form.php?id=<?php echo $progress_id; ?>" method="POST">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($progress['date']); ?>" required>
        <br>
     <select id="workout" name="workout" required>';
                echo '<option value="pushups"' . ($workout == 'pushups' ? ' selected' : '') . '>Pushups</option>';
                echo '<option value="pullups"' . ($workout == 'pullups' ? ' selected' : '') . '>Pullups</option>';
                echo '<option value="cardio"' . ($workout == 'cardio' ? ' selected' : '') . '>Cardio</option>';
                echo '<option value="squats"' . ($workout == 'squats' ? ' selected' : '') . '>Squats</option>';
        <br>
        <label for="duration">Duration (minutes):</label>
        <input type="number" id="duration" name="duration" value="<?php echo htmlspecialchars($progress['duration']); ?>" required>
        <br>
        <button type="submit">Update Progress</button>
    </form>
</body>
</html>
