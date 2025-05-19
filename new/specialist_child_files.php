<?php
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id'];

$sql = "SELECT id, child_name, child_age, child_condition, session_type, session_date 
        FROM sessions 
        WHERE specialist_id = ? 
        AND status = 'approved' ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ملفات الطفل</title>
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
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        
        .card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-green);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 15px 20px;
        }
        
        .card-header h4 {
            margin: 0;
            font-size: 18px;
            display: flex;
            align-items: center;
        }
        
        .card-header h4 i {
            margin-left: 10px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-blue);
            min-width: 100px;
            display: flex;
            align-items: center;
        }
        
        .info-label i {
            margin-left: 8px;
        }
        
        .info-value {
            flex: 1;
        }
        
        .session-type {
            color: var(--dark-green);
            font-weight: 600;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-blue));
            color: var(--white);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-block {
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .modal-header h3 {
            margin: 0;
            color: var(--dark-blue);
            display: flex;
            align-items: center;
        }
        
        .modal-header h3 i {
            margin-left: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .form-group input {
            width: 90%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn-secondary {
            background: var(--medium-gray);
            color: var(--dark-gray);
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
            .card-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="page-header">
    <h2><i class="fas fa-folder-open"></i> ملفات الطفل</h2>
</div>

<div class="card-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-child"></i> <?php echo htmlspecialchars($row['child_name']); ?></h4>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-birthday-cake"></i> العمر:</span>
                    <span class="info-value"><?php echo htmlspecialchars($row['child_age']); ?> سنة</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-heartbeat"></i> الحالة:</span>
                    <span class="info-value"><?php echo htmlspecialchars($row['child_condition']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar-check"></i> نوع الجلسة: </span>
                    <span class="info-value session-type"><?php echo htmlspecialchars($row['session_type']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar-day"></i> تاريخ الجلسة: </span>
                    <span class="info-value"><?php echo htmlspecialchars($row['session_date']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-hashtag"></i> رقم الجلسة: </span>
                    <span class="info-value"><?php echo htmlspecialchars($row['id']); ?></span>
                </div>
                
                <button class="btn btn-primary btn-block" onclick="openSessionForm(<?php echo $row['id']; ?>)">
                    <i class="fas fa-plus-circle"></i> إضافة جلسة
                </button>
            </div>
        </div>

        <!-- Session Modal -->
        <div id="sessionModal-<?php echo $row['id']; ?>" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-calendar-plus"></i> إضافة جلسة جديدة</h3>
                </div>
                <form method="POST" action="add_session.php">
                    <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                    
                    <div class="form-group">
                        <label>التاريخ</label>
                        <input type="date" name="session_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label>الوقت</label>
                        <input type="time" name="session_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label>رابط الاجتماع (إن وُجد)</label>
                        <input type="url" name="meeting_link" placeholder="https://example.com">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeSessionForm(<?php echo $row['id']; ?>)">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ الجلسة
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-folder-open"></i>
            <p>لا توجد بيانات لعرضها</p>
        </div>
    <?php endif; ?>
</div>

<script>
function openSessionForm(id) {
    document.getElementById('sessionModal-' + id).style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
}

function closeSessionForm(id) {
    document.getElementById('sessionModal-' + id).style.display = 'none';
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>