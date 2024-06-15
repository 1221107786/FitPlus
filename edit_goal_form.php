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

$goal_id = $_GET['id'];

// Retrieve the specific goal
$sql = "SELECT goal_type, goal_focus, start_date, end_date, selected_workout 
        FROM goalsetting
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $goal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $goal = $result->fetch_assoc();
} else {
    echo "Goal not found.";
    exit();
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goal_type = $_POST['goal_type'];
    $goal_focus = $_POST['goal_focus'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $selected_workout = $_POST['selected_workout'];

    // Update the goal
    $sql = "UPDATE goalsetting SET goal_type = ?, goal_focus = ?, start_date = ?, end_date = ?, selected_workout = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $goal_type, $goal_focus, $start_date, $end_date, $selected_workout, $goal_id);

    if ($stmt->execute()) {
        echo "Goal updated successfully!";
        echo "<script>window.location.href = 'edit_goals.php';</script>";
    } else {
        echo "Failed to update goal: " . $stmt->error;
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
    <title>Edit Goal</title>
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
<body>
    <h1>Edit Goal</h1>
    <form action="edit_goal_form.php?id=<?php echo $goal_id; ?>" method="POST">
            <label for="goal_type">Goal Type:</label>
            <select id="goal_type" name="goal_type" required>
                <option value="Weight loss" <?php if ($goal['goal_type'] == 'Weight loss') echo 'selected'; ?>>Weight loss</option>
                <option value="Weight gain" <?php if ($goal['goal_type'] == 'Weight gain') echo 'selected'; ?>>Weight gain</option>
            </select>
            <br>
            <label for="goal_focus">Goal Focus:</label>
            <select id="goal_focus" name="goal_focus" required>
                <option value="Muscle Gain" <?php if ($goal['goal_focus'] == 'Muscle Gain') echo 'selected'; ?>>Muscle Gain</option>
                <option value="Fat Loss" <?php if ($goal['goal_focus'] == 'Fat Loss') echo 'selected'; ?>>Fat Loss</option>
            </select>
            <br>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($goal['start_date']); ?>" required>
            <br>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($goal['end_date']); ?>" required>
            <br>
            <label for="selected_workout">Selected Workout:</label>
            <select id="selected_workout" name="selected_workout" required>
                <option value="pushups" <?php if ($goal['selected_workout'] == 'pushups') echo 'selected'; ?>>Pushups</option>
                <option value="pullups" <?php if ($goal['selected_workout'] == 'pullups') echo 'selected'; ?>>Pullups</option>
                <option value="cardio" <?php if ($goal['selected_workout'] == 'cardio') echo 'selected'; ?>>Cardio</option>
                <option value="squats" <?php if ($goal['selected_workout'] == 'squats') echo 'selected'; ?>>Squats</option>
            </select>
            <br>
            <button type="submit">Update Goal</button>
        </form>
</body>
</html>
