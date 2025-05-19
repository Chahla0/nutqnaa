<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$session_id = $_POST['session_id'];

$date = $_POST['session_date'];
$time = $_POST['session_time'];
$link = $_POST['meeting_link'] ?? '';
$specialist_id = $_SESSION['user_id'];

// تحديث الجلسة في جدول sessions
$stmt = $conn->prepare("UPDATE sessions SET session_date = ?, session_time = ?, meeting_link = ?, status = 'approved' WHERE id = ? AND specialist_id = ?");
$stmt->bind_param("sssii", $date, $time, $link, $session_id, $specialist_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "تمت إضافة الجلسة بنجاح.";
} else {
    $_SESSION['error'] = "حدث خطأ أثناء إضافة الجلسة.";
}

header("Location: specialist_dashboard.php");
exit();
?>
