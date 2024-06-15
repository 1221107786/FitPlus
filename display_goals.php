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

// Handle goal deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['goal_id'])) {
    $goal_id = $_POST['goal_id'];
    // Prepare a DELETE statement
    $stmt = $conn->prepare("DELETE FROM goalsetting WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $goal_id, $_SESSION['username']);

    if ($stmt->execute()) {
        echo "<script>alert('Goal deleted successfully!');</script>";
        // Redirect to prevent form resubmission on page reload
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error deleting goal: " . $conn->error;
    }

    $stmt->close();
}

// Query to retrieve user's goals from goalsetting table
$sql = "SELECT id, goal_type, goal_focus, start_date, end_date, selected_workout FROM goalsetting WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Goals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://img.pikbest.com/ai/illus_our/20230422/ca48cc2ea0c23c387add249759ba3a33.jpg!w700wp');
            background-size: cover;
            background-position: center;
            color: #fff; /* White text color */
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7); /* Transparent black */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.5); /* Orange shadow */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffa500; /* Orange color */
        }

        .goal {
            background-color: rgba(0, 0, 0, 0.7); 
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        p {
            color: #fff; /* White text color */
            margin-bottom: 10px;
        }

        form {
            text-align: center;
        }

        input[type="submit"] {
            background-color: #ffa500; /* Orange background */
            color: #000; /* Black text color */
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #d2691e; /* Darker orange background on hover */
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: #ffa500; /* Orange color */
            font-style: italic;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='goal'>";
                echo "<h2>Goal ID: " . $row["id"] . "</h2>";
                echo "<p>Goal Type: " . $row["goal_type"] . "</p>";
                echo "<p>Goal Focus: " . $row["goal_focus"] . "</p>";
                echo "<p>Start Date: " . $row["start_date"] . "</p>";
                echo "<p>End Date: " . $row["end_date"] . "</p>";
                echo "<p>Selected Workout: " . $row["selected_workout"] . "</p>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<input type='hidden' name='goal_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='Delete'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p class='message'>No goals found</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
        <p><a href="profile.php">Back to Profile</a></p>
    </div>
</body>
</html>
