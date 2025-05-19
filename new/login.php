<?php
session_start();

$host = "localhost";
$db = "speech_therapy";
$user = "root";
$pass = "";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get credentials
$email = $_POST['email'];
$password = $_POST['password'];

// Check user
$stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['role'] = $row['role'];

        // Redirect by role
        if ($row['role'] === 'parent') {
            header("Location: parent_dashboard.php");
        } elseif ($row['role'] === 'specialist') {
            header("Location: specialist_dashboard.php?page=home");
        } 
        exit();
    } else {
        header("Location: login.html?error=wrong-password");
        exit();

    }
} else {
    header("Location: login.html?error=no-user");
    exit();
}

$conn->close();
?>
