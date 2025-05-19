<?php
session_start();

$host = "localhost";
$db = "speech_therapy";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $conn->real_escape_string($_POST['role']);

// Check if email exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $_SESSION['error'] = "البريد الإلكتروني مسجل بالفعل.";
    header("Location: register.html");
    exit();
}
$check->close();

// Insert into users table
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $email, $password, $role);
if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['full_name'] = $full_name;
    $_SESSION['role'] = $role;

    if ($role === 'parent') {
        $child_name = $conn->real_escape_string($_POST['child_name']);
        $child_age = $_POST['child_age'];
        $autism_level = $conn->real_escape_string($_POST['autism_level']);

        $stmt2 = $conn->prepare("INSERT INTO parents (user_id, child_name, child_age, autism_level) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("isis", $user_id, $child_name, $child_age, $autism_level);
        $stmt2->execute();
        header("Location: parent_dashboard.php");
        exit();

    } elseif ($role === 'specialist') {
        $specialty = $conn->real_escape_string($_POST['specialty']);
        $license = $conn->real_escape_string($_POST['license']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        $qualification = $conn->real_escape_string($_POST['qualification']);
        $experience = $_POST['experience'];

        $stmt3 = $conn->prepare("INSERT INTO specialists (user_id, specialty, license, phone, address, qualification, experience) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("issssss", $user_id, $specialty, $license, $phone, $address, $qualification, $experience);
        $stmt3->execute();
        header("Location: specialist_dashboard.php?page=home");
        exit();

    } 
} else {
    $_SESSION['error'] = "حدث خطأ أثناء التسجيل.";
    header("Location: register.html");
    exit();
}
?>
