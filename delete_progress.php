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

// Check if form is submitted for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteProgress'])) {
    $progress_id = $_POST['progress_id'];

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM workouts WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $progress_id, $_SESSION['username']);

    if ($stmt->execute()) {
        // Success message if deletion is successful
        echo "<script>alert('Progress deleted successfully!');</script>";
    } else {
        // Error message if deletion fails
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Query to retrieve progress records for the logged-in user
$sql = "SELECT id, date, workout, duration FROM workouts WHERE username = ?";
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
    <title>Delete Progress</title>
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
            color: #ff9800;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            

        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            color: #fff;
        }

        th {
            background-color: black; /* Transparent black */
        }

        td {
            background-color: rgba(0, 0, 0, 0.7); /* Transparent black */
        }

        form {
            display: inline-block;
            background-color: rgba(0, 0, 0, 0.7); /* Transparent black */
            border-radius: 8px;
        }

        button[type="submit"] {
            background-color: #ff9800;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #f57c00;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Progress</h2>
        <?php
        // Check if there are progress records to display
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Date</th><th>Workout</th><th>Duration (minutes)</th><th>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['workout']) . "</td>";
                echo "<td>" . htmlspecialchars($row['duration']) . "</td>";
                echo "<td>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<input type='hidden' name='progress_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='submit' name='deleteProgress'>Delete</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No progress records found.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
        <p><a href="profile.php">Back to Profile</a></p>
    </div>
</body>
</html>
