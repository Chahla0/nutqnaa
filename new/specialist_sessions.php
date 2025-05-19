<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id'];

$sql = "SELECT * FROM sessions WHERE specialist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الجلسات المحجوزة</title>
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
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
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
        
        .table-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: var(--white);
        }
        
        th {
            padding: 15px;
            text-align: center;
            font-weight: 600;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--medium-gray);
            text-align: center;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: rgba(133, 186, 226, 0.1);
        }
        
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--warning);
        }
        
        .status-approved {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success);
        }
        
        .status-rejected {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .btn i {
            margin-left: 5px;
        }
        
        .btn-accept {
            background-color: var(--success);
            color: var(--white);
            margin-bottom: 20px;
        }
        
        .btn-accept:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .btn-reject {
            background-color: var(--danger);
            color: var(--white);
        }
        
        .btn-reject:hover {
            background-color: #c82333;
            transform: translateY(-2px);
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
            table {
                display: block;
                overflow-x: auto;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 12px;
                margin: 2px;
            }
            
            .btn i {
                margin-left: 3px;
            }
        }
    </style>
</head>
<body>

<div class="page-header">
    <h2><i class="fas fa-calendar-check"></i> الجلسات المحجوزة</h2>
</div>

<div class="table-container">
    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>اسم الطفل</th>
                <th>العمر</th>
                <th>الحالة</th>
                <th>نوع الجلسة</th>
                <th>الحالة</th>
                <th>تاريخ الحجز</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                <td><?php echo htmlspecialchars($row['child_age']); ?></td>
                <td><?php echo htmlspecialchars($row['child_condition']); ?></td>
                <td><?php echo htmlspecialchars($row['session_type']); ?></td>
                <td>
                <?php
    $status = htmlspecialchars($row['status']);
    $arabic_status = [
        'approved' => 'مقبولة',
        'rejected' => 'مرفوضة',
        'pending' => 'قيدالانتظار'
    ];
?>
<span class="status status-<?php echo $status; ?>">
    <?php echo $arabic_status[$status]; ?>
</span>
                </td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <button class="btn btn-accept" onclick="showSessionForm(<?php echo $row['id']; ?>, '<?php echo $row['session_type']; ?>')">
                        <i class="fas fa-check"></i> قبول
                    </button>
                    
                    <form method="POST" action="update_session_status.php" style="display:inline;">
                        <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="rejected">
                        <button type="submit" class="btn btn-reject">
                            <i class="fas fa-times"></i> رفض
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="no-data">
        <i class="fas fa-calendar-times"></i>
        <p>لا توجد جلسات محجوزة لعرضها</p>
    </div>
    <?php endif; ?>
</div>

<!-- Session Approval Modal -->
<div id="sessionForm" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-plus"></i> تأكيد الجلسة</h3>
        </div>
        <form method="POST" action="update_session_status.php">
            <input type="hidden" name="session_id" id="session_id">
            <input type="hidden" name="action" value="approved">
            
            <div class="form-group">
                <label>تاريخ الجلسة</label>
                <input type="date" name="session_date" required>
            </div>
            
            <div class="form-group">
                <label>وقت الجلسة</label>
                <input type="time" name="session_time" required>
            </div>
            
            <div id="online_fields" class="form-group" style="display:none;">
                <label>رابط الاجتماع (Zoom أو Google Meet)</label>
                <input type="url" name="meeting_link" placeholder="https://example.com">
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('sessionForm').style.display='none'">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <button type="submit" class="btn btn-accept">
                    <i class="fas fa-check"></i> تأكيد
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showSessionForm(id, type) {
    document.getElementById('sessionForm').style.display = 'flex';
    document.getElementById('session_id').value = id;
    document.body.style.overflow = 'hidden';

    if (type === 'عن بعد') {
        document.getElementById('online_fields').style.display = 'block';
    } else {
        document.getElementById('online_fields').style.display = 'none';
    }
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