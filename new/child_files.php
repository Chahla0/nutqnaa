<?php
session_start();
if ($_SESSION['role'] !== 'specialist') {
    header("Location: login.php");
    exit();
}

$specialist_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "speech_therapy");

$query = "
    SELECT c.full_name AS child_name, c.age, c.autism_level, c.join_date, u.full_name AS parent_name
    FROM sessions s
    JOIN users u ON s.parent_id = u.id
    JOIN children c ON c.parent_id = u.id
    WHERE s.specialist_id = ? AND s.status = 'approved'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>ملفات الأطفال</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<p><strong>الاسم:</strong> " . htmlspecialchars($row['child_name']) . "</p>";
        echo "<p><strong>العمر:</strong> " . htmlspecialchars($row['age']) . " سنوات</p>";
        echo "<p><strong>مستوى التوحد:</strong> " . htmlspecialchars($row['autism_level']) . "</p>";
        echo "<p><strong>تاريخ الانضمام:</strong> " . htmlspecialchars($row['join_date']) . "</p>";
        echo "<p><strong>اسم الولي:</strong> " . htmlspecialchars($row['parent_name']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>لا توجد ملفات أطفال مرتبطة بك حاليًا.</p>";
}
