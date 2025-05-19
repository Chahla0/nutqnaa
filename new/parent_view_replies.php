<?php
session_start();
include 'db.php';
$parent_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT dc.*, c.name AS child_name
                        FROM daily_consultations dc
                        JOIN children c ON c.id = dc.child_id
                        WHERE dc.parent_id = ?
                        ORDER BY dc.created_at DESC");
$stmt->execute([$parent_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>الردود على الاستشارات</h2>
<?php foreach ($rows as $row): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <strong>الطفل:</strong> <?= $row['child_name'] ?><br>
        <strong>حالة اليوم:</strong><br> <?= nl2br($row['message_from_parent']) ?><br>
        <strong>رد الأخصائي:</strong><br>
        <?= $row['reply_from_specialist'] ? nl2br($row['reply_from_specialist']) : "لم يتم الرد بعد." ?>
    </div>
<?php endforeach; ?>
