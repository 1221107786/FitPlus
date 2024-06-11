<?php
$servername = "localhost";
$username = "FitPlus"; 
$password = "-kLpc_0I_HFlm1G4"; 
$dbname = "fitplus";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

   
    $stmt = $conn->prepare("INSERT INTO users (username, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $lastname, $phone, $email, $password);

  
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    
    $stmt->close();
}


$conn->close();


header('Location: login.html');
exit();
?>
