<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}
$parent_id = $_SESSION['user_id'];

// Fetch children and their assigned specialists
$query = "SELECT c.id AS child_id, c.name AS child_name, s.id AS specialist_id, s.name AS specialist_name
          FROM children c
          JOIN parent_specialist ps ON ps.parent_id = c.parent_id
          JOIN users s ON ps.specialist_id = s.id
          WHERE c.parent_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$parent_id]);
$children = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>استشارات يومية</h2>
<form action="submit_consultation.php" method="post">
    <label>اختر الطفل:</label>
    <select name="child_id" required>
        <?php foreach ($children as $child): ?>
            <option value="<?= $child['child_id'] ?>"><?= $child['child_name'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>حالة الطفل اليوم:</label><br>
    <textarea name="message_from_parent" rows="5" cols="50" required></textarea><br><br>

    <button type="submit">إرسال</button>
</form>
