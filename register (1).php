<?php
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

$error_message = ""; // Initialize error message variable


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    

    

    // Check if username already exists
    $check_username_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_username = $stmt->get_result();

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_email = $stmt->get_result();

    // If username exists, set error message
    
    if ($result_username->num_rows > 0) {
        $error_message = "Username already exists.";
    } elseif ($result_email->num_rows > 0) {
        // If email exists, set error message
        $error_message = "Email already exists.";
    } else {
        // Insert new user data
        $insert_query = "INSERT INTO users (username, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $username, $lastname, $phone, $email, $password);

        if ($stmt->execute()) {
            $error_message = "Registration successful!";
            // Redirect to login page after successful registration
            header('Location: login.html');
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
   
    $stmt->close();
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Register</title>
  <style>
    body {
      font-family: "Open Sans", Helvetica, Arial, sans-serif;
      line-height: 1.5;
      background-color: #111317;
      color: #FFF;
    }

    .container {
      max-width: 600px;
      width: 80%;
      margin: 50px auto;
      padding: 20px;
      border: 2px solid #c1cdcd;
      border-radius: 8px;
    }

    form {
      width: 100%;
    }

    label {
      font-weight: bold;
      margin-bottom: 10px;
      display: block;
    }

    input {
      width: calc(100% - 22px);
      border: 2px solid #c1cdcd;
      background: #FFF;
      margin: 0 0 10px;
      padding: 10px;
      border-radius: 4px;
    }

    button {
      cursor: pointer;
      width: 100%;
      border: none;
      background: rgb(208, 147, 62);
      color: #000;
      margin: 10px 0 0;
      padding: 10px;
      font-size: 15px;
      border-radius: 4px;
    }

    .error-message {
      color: red;
      margin-top: 10px;
    }

    .login-link {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: rgb(208, 147, 62);
      text-decoration: none;
    }

  </style>
</head>

<body>
  <div class="container">
    <h2>Registration Form</h2>
    <?php if (!empty($error_message)) : ?>
      <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="username">First Name:</label>
      <input type="text" id="username" name="username" placeholder="First Name" required>

      <label for="lastname">Second Name:</label>
      <input type="text" id="lastname" name="lastname" placeholder="Second Name" required>

      <label for="phone">Phone Number:</label>
      <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Password" required>

      <button type="submit" name="submit">Submit</button>
    </form>
    <a href="login.html" class="login-link">Already have an account? Login here</a>
  </div>
</body>

</html>
