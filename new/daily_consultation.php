<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$parent_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الاستشارات اليومية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        /* Sidebar Navigation */
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
        
        .section-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-blue);
            margin-top: 0;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-green);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-left: 15px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e6ed;
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 1rem;
            background-color: #f9fbfd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(133, 186, 226, 0.2);
            outline: none;
            background-color: white;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%235e5e5e'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 15px center;
            background-size: 15px;
            padding-right: 15px;
        }
        
        .submit-btn {
            background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-blue) 100%);
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
        }
        
        .submit-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(146, 202, 199, 0.3);
        }
        
        .submit-btn i {
            margin-left: 10px;
        }
        
        /* Consultation Replies */
        .consultation-item {
            border: 1px solid #e0e6ed;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            transition: all 0.3s;
        }
        
        .consultation-item:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 5px 15px rgba(133, 186, 226, 0.1);
        }
        
        .consultation-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed rgba(146, 202, 199, 0.5);
        }
        
        .consultation-specialist {
            color: var(--primary-purple);
            font-weight: 500;
        }
        
        .consultation-date {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .consultation-content {
            margin-bottom: 15px;
        }
        
        .consultation-reply {
            padding: 15px;
            background-color: rgba(146, 202, 199, 0.1);
            border-radius: var(--border-radius);
            border-right: 3px solid var(--primary-green);
        }
        
        .no-reply {
            color: var(--text-light);
            font-style: italic;
        }
        
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }
        
        .no-data i {
            font-size: 50px;
            color: var(--primary-purple);
            margin-bottom: 15px;
        }
        
        .no-data h3 {
            color: var(--primary-blue);
            margin-bottom: 10px;
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
            
            .section-card {
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
                    <a href="choose-specialist.php">
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
                    <a href="daily_consultation.php" class="active">
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
        <!-- New Consultation Form -->
        <div class="section-card">
            <h2 class="section-title">
                <i class="fas fa-comment-medical"></i>
                استشارة يومية جديدة
            </h2>
            
            <form method="POST" action="submit_consultation.php">
                <div class="form-group">
                    <label for="child_state">اكتب حالة طفلك اليوم:</label>
                    <textarea name="child_state" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="specialist_id">اختر الأخصائي:</label>
                    <select name="specialist_id" class="form-control" required>
                        <?php
                        $stmt = $conn->prepare("SELECT sp.user_id, u.full_name 
                            FROM sessions s 
                            JOIN specialists sp ON s.specialist_id = sp.user_id 
                            JOIN users u ON sp.user_id = u.id 
                            WHERE s.parent_id = ? AND s.status = 'approved' 
                            GROUP BY sp.user_id");
                        $stmt->bind_param("i", $parent_id);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo "<option value='{$row['user_id']}'>د. {$row['full_name']}</option>";
                            }
                        } else {
                            echo "<option value='' disabled selected>لا يوجد أخصائيين متاحين</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> إرسال الاستشارة
                </button>
            </form>
        </div>
        
        <!-- Consultation Replies -->
        <div class="section-card">
            <h2 class="section-title">
                <i class="fas fa-reply"></i>
                الردود على الاستشارات
            </h2>
            
            <?php
            $stmt = $conn->prepare("SELECT c.*, u.full_name 
                FROM consultations c 
                JOIN users u ON c.specialist_id = u.id 
                WHERE c.parent_id = ? 
                ORDER BY c.created_at DESC");
            $stmt->bind_param("i", $parent_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="consultation-item">
                        <div class="consultation-header">
                            <span class="consultation-specialist">
                                <i class="fas fa-user-md"></i>
                                <?php echo htmlspecialchars($row['full_name']); ?>
                            </span>
                            <span class="consultation-date">
                                <?php echo date('Y-m-d', strtotime($row['created_at'])); ?>
                            </span>
                        </div>
                        
                        <div class="consultation-content">
                            <p><strong>حالة الطفل:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($row['child_state'])); ?></p>
                        </div>
                        
                        <div class="consultation-reply">
                            <p><strong>الرد:</strong></p>
                            <?php if ($row['reply']): ?>
                                <p><?php echo nl2br(htmlspecialchars($row['reply'])); ?></p>
                            <?php else: ?>
                                <p class="no-reply">لم يتم الرد بعد</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-comment-slash"></i>
                    <h3>لا توجد استشارات سابقة</h3>
                    <p>يمكنك إرسال استشارة جديدة باستخدام النموذج أعلاه</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
