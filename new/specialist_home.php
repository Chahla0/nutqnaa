<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
$specialist_id = $_SESSION['user_id'];

// احصائيات
// 1. عدد الجلسات
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sessions WHERE specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_sessions = $result->fetch_assoc()['total'] ?? 0;

// 2. عدد الاستشارات
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM consultations WHERE specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_consultations = $result->fetch_assoc()['total'] ?? 0;

// 3. عدد الأطفال
$stmt = $conn->prepare("SELECT COUNT(DISTINCT c.id) AS total 
                        FROM consultations cons 
                        JOIN parents p ON cons.parent_id = p.user_id 
                        JOIN children c ON c.parent_id = p.user_id 
                        WHERE cons.specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_children = $result->fetch_assoc()['total'] ?? 0;
?>

<div style="background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
<div style="background:  linear-gradient(135deg, #5a8bc2, #9999c9);
            padding: 40px 30px; 
            border-radius: 12px; 
            margin-bottom: 30px;
            color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px;  margin: 0 auto; height:400px;">
        <div>
            <h2 style="color: white; font-size: 28px; margin-bottom: 10px;">مرحبًا بك في لوحة تحكم الأخصائي</h2>
            <p style="font-size: 16px; opacity: 0.9;">يمكنك من هنا متابعة الجلسات، الملفات، والاستشارات.</p>
        </div>
        <img src="../new/images/specialist.png" alt="Specialist" style="height: 350px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.2)); border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    </div>
</div>

    <div style="display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px; background-color: #f1f3f9; padding: 20px; border-radius: 10px;">
            <h4>الجلسات المحجوزة</h4>
            <p style="font-size: 24px; color: #4e73df;"><?php echo $total_sessions; ?></p>
        </div>

        <div style="flex: 1; min-width: 200px; background-color: #f1f3f9; padding: 20px; border-radius: 10px;">
            <h4>عدد الاستشارات</h4>
            <p style="font-size: 24px; color: #4e73df;"><?php echo $total_consultations; ?></p>
        </div>
    </div>
</div>
