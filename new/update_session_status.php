<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// Check if session_id and action are provided
$session_id = $_POST['session_id'] ?? null;
$action = $_POST['action'] ?? null;

// Ensure session_id and action are not empty
if (empty($session_id) || empty($action)) {
    die("البيانات مفقودة");
}

// Get session data from POST
$session_date = $_POST['session_date'] ?? null;
$session_time = $_POST['session_time'] ?? null;
$meeting_link = $_POST['meeting_link'] ?? null;

// Fetch specialist's full name
$sql = "SELECT u.full_name 
        FROM sessions s
        JOIN specialists sp ON s.specialist_id = sp.user_id
        JOIN users u ON u.id = sp.user_id
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$specialist_name = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $specialist_name = $row['full_name'];
}

// Handle approved action
if ($action === 'approved') {
    // Validate that session_date and session_time are provided
    if (empty($session_date) || empty($session_time)) {
        die("التاريخ أو الوقت مفقود");
    }

    $sql = "UPDATE sessions SET status='approved', session_date=?, session_time=?, meeting_link=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $session_date, $session_time, $meeting_link, $session_id);
    
    if ($stmt->execute()) {
        
        $_SESSION['message'] = "تم قبول الجلسة بنجاح مع الأخصائي: " . $specialist_name;
    } else {
        $_SESSION['error'] = "فشل في قبول الجلسة: " . $stmt->error;
    }
}
// Handle rejected action
elseif ($action === 'rejected') {
    $sql = "UPDATE sessions SET status='rejected' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $session_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "تم رفض الجلسة مع الأخصائي: " . $specialist_name;
    } else {
        $_SESSION['error'] = "فشل في رفض الجلسة: " . $stmt->error;
    }
} else {
    die("Action غير معروفة");
}

header("Location: specialist_sessions.php");
exit();
?>
