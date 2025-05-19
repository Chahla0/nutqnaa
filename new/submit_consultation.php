<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parent_id = $_SESSION['user_id'];
$specialist_id = $_POST['specialist_id'];
$child_state = $_POST['child_state'];

$stmt = $conn->prepare("INSERT INTO consultations (parent_id, specialist_id, child_state) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $parent_id, $specialist_id, $child_state);

if ($stmt->execute()) {
    $_SESSION['message'] = "تم إرسال الاستشارة بنجاح!";
} else {
    $_SESSION['error'] = "حدث خطأ أثناء الإرسال.";
}

header("Location: parent_dashboard.php");
exit();
