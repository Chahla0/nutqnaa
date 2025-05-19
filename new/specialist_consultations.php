<?php
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$specialist_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT c.*, u.full_name FROM consultations c JOIN users u ON u.id = c.parent_id WHERE c.specialist_id = ? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الرد على الاستشارات</title>
    <style>
        :root {
            --primary-blue: #85bae2;
            --primary-green: #92cac7;
            --primary-purple: #9999c9;
            --dark-blue: #5a8bc2;
            --dark-green: #6ba8a5;
            --dark-purple: #7a7aa7;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #495057;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            padding: 30px;
            color: var(--dark-gray);
        }
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--medium-gray);
        }
        
        .page-header h2 {
            color: var(--dark-blue);
            font-size: 28px;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .page-header h2 i {
            margin-left: 10px;
        }
        
        .consultation-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .consultation-card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-green);
        }
        
        .consultation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .consultation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .parent-name {
            font-weight: 600;
            color: var(--dark-blue);
            display: flex;
            align-items: center;
        }
        
        .parent-name i {
            margin-left: 8px;
        }
        
        .consultation-date {
            font-size: 14px;
            color: var(--dark-gray);
            opacity: 0.8;
        }
        
        .consultation-content {
            margin-bottom: 20px;
        }
        
        .consultation-content p {
            margin: 0;
            line-height: 1.6;
        }
        
        .consultation-reply {
            background-color: rgba(133, 186, 226, 0.1);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-right: 3px solid var(--primary-blue);
        }
        
        .reply-header {
            font-weight: 600;
            color: var(--dark-blue);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .reply-header i {
            margin-left: 8px;
        }
        
        .reply-form {
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-blue));
            color: var(--white);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            grid-column: 1 / -1;
        }
        
        .no-data i {
            font-size: 50px;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }
        
        .no-data p {
            font-size: 18px;
            color: var(--dark-gray);
        }
        
        @media (max-width: 768px) {
            .consultation-container {
                grid-template-columns: 1fr;
            }
            
            .consultation-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="page-header">
    <h2><i class="fas fa-comments"></i> الرد على الاستشارات</h2>
</div>

<div class="consultation-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="consultation-card">
            <div class="consultation-header">
                <span class="parent-name">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($row['full_name']); ?>
                </span>
                <span class="consultation-date">
                    <?php echo date('Y/m/d', strtotime($row['created_at'])); ?>
                </span>
            </div>
            
            <div class="consultation-content">
                <p><?php echo nl2br(htmlspecialchars($row['child_state'])); ?></p>
            </div>
            
            <?php if ($row['reply']): ?>
                <div class="consultation-reply">
                    <div class="reply-header">
                        <i class="fas fa-reply"></i> ردك
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($row['reply'])); ?></p>
                </div>
            <?php else: ?>
                <form method="POST" action="reply_consultation.php" class="reply-form">
                    <input type="hidden" name="consultation_id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label>اكتب ردك:</label>
                        <textarea name="reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> إرسال الرد
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-comment-slash"></i>
            <p>لا توجد استشارات تحتاج إلى رد</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>