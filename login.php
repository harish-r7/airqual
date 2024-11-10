<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "air_index";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginUsername = trim($_POST['login-username']);
    $loginPassword = trim($_POST['login-password']);

    if (empty($loginUsername) || empty($loginPassword)) {
        die("Both fields are required.");
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $loginUsername);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        if (password_verify($loginPassword, $hashedPassword)) {
            echo "Login successful";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
    $stmt->close();
}

$conn->close();
?>
