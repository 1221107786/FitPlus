<?php
// Start session to check user authentication
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: profile.php"); // Redirect to profile if not authenticated
    exit();
}

// Include database connection details
$servername = "localhost";
$username = "FitPlus";
$password = "T24icnPT46C*54Od";
$dbname = "fitplus";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted to delete account
if (isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user_id'];

    // Delete related records in goalsetting table
    $sql_delete_goals = "DELETE FROM goalsetting WHERE username IN (SELECT username FROM users WHERE id = ?)";
    $stmt_goals = $conn->prepare($sql_delete_goals);
    $stmt_goals->bind_param("i", $user_id);

    // Execute deletion of goals
    if ($stmt_goals->execute()) {
        $stmt_goals->close(); // Close prepared statement for goals

        // Delete user's account from users table
        $sql_delete_user = "DELETE FROM users WHERE id = ?";
        $stmt_user = $conn->prepare($sql_delete_user);
        $stmt_user->bind_param("i", $user_id);

        // Execute deletion of user
        if ($stmt_user->execute()) {
            // Account deleted successfully, log out user and redirect to profile
            session_destroy(); // Destroy session
            header("Location: dashboard.html"); // Redirect to profile page
            exit();
        } else {
            // Error deleting user account
            echo "Error deleting user account: " . $stmt_user->error;
        }

        $stmt_user->close(); // Close prepared statement for user
    } else {
        // Error deleting goals
        echo "Error deleting goals: " . $stmt_goals->error;
    }

    $stmt_goals->close(); // Close prepared statement for goals
}

// Close database connection
$conn->close();
?>
