<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT users.id as user_id, users.full_name, users.email, 
               specialists.specialty, specialists.phone, 
               specialists.address, specialists.qualification, 
               specialists.experience
        FROM users 
        JOIN specialists ON users.id = specialists.user_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اختر أخصائي</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #85bae2;
            --primary-green: #92cac7;
            --primary-purple: #9999c9;
            --text-dark: #3a3a3a;
            --text-light: #5e5e5e;
            --bg-light: #f8fafc;
            --border-radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
            padding: 30px 0;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .profile-section {
            text-align: center;
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(133, 186, 226, 0.2);
            margin-bottom: 25px;
        }
        
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-green);
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(146, 202, 199, 0.2);
        }
        
        .profile-section h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .profile-section p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Navigation Styles */
        nav ul {
            list-style: none;
            padding: 0 15px;
            flex-grow: 1;
        }
        
        nav li {
            margin-bottom: 5px;
        }
        
        nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        nav a:hover, nav a.active {
            background: linear-gradient(90deg, rgba(133, 186, 226, 0.1) 0%, rgba(146, 202, 199, 0.1) 100%);
            color: var(--primary-blue);
            transform: translateX(-3px);
        }
        
        nav a i {
            margin-left: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Logout Button */
        .logout-btn {
            margin-top: auto;
            padding: 0 15px;
        }
        
        .logout-btn a {
            background-color: rgba(248, 215, 218, 0.3);
            color: #721c24;
        }
        
        .logout-btn a:hover {
            background-color: rgba(248, 215, 218, 0.5);
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 40px;
        }
        
        .page-header {
            color: var(--primary-blue);
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.8rem;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-green);
        }
        
        .specialists-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .specialist-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-purple);
        }
        
        .specialist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(133, 186, 226, 0.15);
        }
        
        .specialist-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed rgba(146, 202, 199, 0.5);
        }
        
        .specialist-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-left: 15px;
        }
        
        .specialist-name {
            flex: 1;
        }
        
        .specialist-name h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
        }
        
        .specialist-name p {
            color: var(--primary-purple);
            font-weight: 500;
        }
        
        .specialist-info {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .specialist-info i {
            color: var(--primary-green);
            width: 25px;
            text-align: center;
            margin-left: 10px;
            font-size: 1.1rem;
        }
        
        .specialist-info span {
            flex: 1;
        }
        
        .view-btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .view-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(153, 153, 201, 0.3);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            width: 100%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 5px 30px rgba(0,0,0,0.2);
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            background: none;
            border: none;
        }
        
        .modal-title {
            color: var(--primary-blue);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e6ed;
            border-radius: var(--border-radius);
            font-family: inherit;
            background-color: #f9fbfd;
        }
        
        textarea.form-control {
            min-height: 100px;
        }
        
        .submit-btn {
            background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-blue) 100%);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .profile-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid var(--primary-green);
            box-shadow: 0 4px 8px rgba(146, 202, 199, 0.2);
            color: white;
            font-size: 50px;
        }
        /* Responsive Design */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                padding: 30px 20px;
            }
            
            .specialists-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .profile-img {
                width: 80px;
                height: 80px;
            }
            
            nav ul {
                display: flex;
                flex-wrap: wrap;
                padding: 0 10px;
            }
            
            nav li {
                flex: 1 0 50%;
                margin-bottom: 5px;
            }
            
            nav a {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
            
            .logout-btn {
                margin-top: 10px;
            }
            
            .modal-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
 <!-- Sidebar Navigation -->
 <div class="sidebar">
 <div class="profile-section">
        <div class="profile-icon">
            <i class="fas fa-user-circle"></i>
        </div>
            <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
            <p>ولي أمر</p>
        </div>

        <nav>
            <ul>
                <li>
                    <a href="parent_dashboard.php" class="active">
                        <i class="fas fa-home"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                <li>
                    <a href="child_profile.php">
                        <i class="fas fa-child"></i>
                        <span>ملف الطفل</span>
                    </a>
                </li>
                <li>
                    <a href="choose-specialist.php" class="active">
                        <i class="fas fa-user-md"></i>
                        <span>اختيار أخصائي</span>
                    </a>
                </li>
                <li>
                    <a href="chosen-specialists.php">
                        <i class="fas fa-user-check"></i>
                        <span>الجلسات مع الأخصائيين</span>
                    </a>
                </li>
                <li>
                    <a href="daily_consultation.php">
                        <i class="fas fa-comments"></i>
                        <span>استشارة يومية</span>
                    </a>
                </li>
                <li>
                    <a href="videos-courses.php" class="active">
                        <i class="fas fa-video"></i>
                        <span>فيديوهات ودورات</span>
                    </a>
                </li>
            </ul>
            
            <div class="logout-btn">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </div>
        </nav>
    </div>

 <!-- Main Content -->
 <div class="main-content">
        <h2 class="page-header"><i class="fas fa-user-md"></i> اختر أخصائي من القائمة</h2>
        
        <div class="specialists-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="specialist-card">
                    <div class="specialist-header">
                        <div class="specialist-avatar">
                            <?php echo mb_substr($row['full_name'], 0, 1); ?>
                        </div>
                        <div class="specialist-name">
                            <h3><?php echo htmlspecialchars($row['full_name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['specialty']); ?></p>
                        </div>
                    </div>
                    
                    <div class="specialist-info">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo htmlspecialchars($row['email']); ?></span>
                    </div>
                    
                    <div class="specialist-info">
                        <i class="fas fa-phone"></i>
                        <span><?php echo htmlspecialchars($row['phone']); ?></span>
                    </div>
                    
                    <div class="specialist-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($row['address']); ?></span>
                    </div>
                    
                    <div class="specialist-info">
                        <i class="fas fa-graduation-cap"></i>
                        <span><?php echo htmlspecialchars($row['qualification']); ?></span>
                    </div>
                    
                    <div class="specialist-info">
                        <i class="fas fa-briefcase"></i>
                        <span><?php echo htmlspecialchars($row['experience']); ?> سنوات خبرة</span>
                    </div>
                    
                    <button class="view-btn" onclick="openModal(<?php echo $row['user_id']; ?>)">
                        <i class="fas fa-calendar-check"></i> حجز جلسة
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
     <!-- Booking Modal -->
     <div id="bookingModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal()">×</button>
            <h3 class="modal-title"><i class="fas fa-calendar-plus"></i> حجز جلسة جديدة</h3>
            
            <form action="submit_booking.php" method="POST">
                <input type="hidden" name="specialist_id" id="specialist_id">
                
                <div class="form-group">
                    <label for="child_name"><i class="fas fa-child"></i> اسم الطفل:</label>
                    <input type="text" name="child_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="child_age"><i class="fas fa-birthday-cake"></i> العمر:</label>
                    <input type="number" name="child_age" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="child_condition"><i class="fas fa-notes-medical"></i> الحالة:</label>
                    <textarea name="child_condition" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="session_type"><i class="fas fa-laptop-house"></i> نوع الجلسة:</label>
                    <select name="session_type" class="form-control" required>
                        <option value="">-- اختر نوع الجلسة --</option>
                        <option value="حضوري">حضوري</option>
                        <option value="عن بعد">عن بعد</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-check-circle"></i> تأكيد الحجز
                </button>
            </form>
        </div>
    </div>
    


<script>
     function openModal(specialistId) {
            document.getElementById('specialist_id').value = specialistId;
            document.getElementById('bookingModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeModal();
            }
        }
</script>

</body>
</html>
