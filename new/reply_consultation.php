<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");

$consultation_id = $_POST['consultation_id'];
$reply = $_POST['reply'];

$stmt = $conn->prepare("UPDATE consultations SET reply = ? WHERE id = ?");
$stmt->bind_param("si", $reply, $consultation_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "تم إرسال الرد بنجاح!";
} else {
    $_SESSION['error'] = "حدث خطأ أثناء إرسال الرد.";
}

header("Location: specialist_dashboard.php?page=consultations");
exit();
