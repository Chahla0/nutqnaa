<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parent_id = $_SESSION['user_id'];
$specialist_id = $_POST['specialist_id'];
$child_name = $_POST['child_name'];
$child_age = $_POST['child_age'];
$child_condition = $_POST['child_condition'];
$session_type = $_POST['session_type'];

$stmt = $conn->prepare("INSERT INTO sessions (specialist_id, parent_id, child_name, child_age, child_condition, session_type, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("iissss", $specialist_id, $parent_id, $child_name, $child_age, $child_condition, $session_type);

$success = false;
$message = "";

if ($stmt->execute()) {
    $success = true;
    $message = "تم إرسال الطلب بنجاح! سيتم مراجعة طلبك من قبل الأخصائي.";
} else {
    $message = "حدث خطأ أثناء إرسال الطلب: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلب الجلسة</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(to right, #9EC6F3, #A1D6CB);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-box {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        .message-box h2 {
            color: <?= $success ? '#4CAF50' : '#D32F2F' ?>;
            margin-bottom: 15px;
        }
        .back-btn {
            margin-top: 20px;
            background-color: #C8ACD6;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
        }
        .back-btn:hover {
            background-color: #b394c1;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2><?= $success ? 'نجاح' : 'خطأ' ?></h2>
        <p><?= $message ?></p>
        <a class="back-btn" href="parent_dashboard.php">العودة إلى لوحة التحكم</a>
    </div>
</body>
</html>