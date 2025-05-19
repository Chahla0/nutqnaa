<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$parent_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT sp.user_id AS specialist_id, u.full_name, sp.specialty
        FROM sessions s
        JOIN specialists sp ON s.specialist_id = sp.user_id
        JOIN users u ON u.id = sp.user_id
        WHERE s.parent_id = ? AND s.status = 'approved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جلسات الأخصائيين</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        
        .page-header {
            color: var(--primary-blue);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-green);
            display: flex;
            align-items: center;
        }
        
        .page-header i {
            margin-left: 15px;
            font-size: 1.5rem;
        }
        
        /* Specialist Cards */
        .specialist-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-purple);
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
        
        /* Sessions List */
        .sessions-list {
            margin-top: 20px;
        }
        
        .session-item {
            padding: 15px;
            border-radius: var(--border-radius);
            background-color: rgba(133, 186, 226, 0.08);
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .session-item:hover {
            background-color: rgba(133, 186, 226, 0.15);
        }
        
        .session-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .session-detail {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .session-detail i {
            color: var(--primary-green);
            margin-left: 8px;
            width: 20px;
            text-align: center;
        }
        
        .meeting-link {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background-color: var(--primary-blue);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .meeting-link:hover {
            background-color: var(--primary-purple);
            transform: translateY(-2px);
        }
        
        .meeting-link i {
            margin-left: 8px;
        }
        
        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: var(--text-light);
        }
        
        .no-data i {
            font-size: 50px;
            color: var(--primary-purple);
            margin-bottom: 20px;
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
            
            .specialist-header {
                flex-direction: column;
                text-align: center;
            }
            
            .specialist-avatar {
                margin: 0 0 15px 0;
            }
            
            .session-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
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
                        <a href="chosen-specialists.php" class="active">
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
        <h2 class="page-header">
            <i class="fas fa-user-check"></i>
            الجلسات مع الأخصائيين
        </h2>
        
        <?php if ($result->num_rows === 0): ?>
            <div class="no-data">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>لا توجد جلسات موافق عليها</h3>
                <p>يمكنك حجز جلسات جديدة من صفحة "اختيار أخصائي"</p>
            </div>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): 
                $specialist_id = $row['specialist_id']; ?>
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
                    
                    <div class="sessions-list">
                        <?php
                        $session_sql = "SELECT * FROM sessions WHERE parent_id = ? AND specialist_id = ? AND status = 'approved'";
                        $session_stmt = $conn->prepare($session_sql);
                        $session_stmt->bind_param("ii", $parent_id, $specialist_id);
                        $session_stmt->execute();
                        $sessions = $session_stmt->get_result();

                        if ($sessions->num_rows > 0): ?>
                            <?php while ($session = $sessions->fetch_assoc()): ?>
                                <div class="session-item">
                                    <div class="session-info">
                                        <div class="session-detail">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>التاريخ: <?php echo htmlspecialchars($session['session_date']); ?></span>
                                        </div>
                                        
                                        <div class="session-detail">
                                            <i class="fas fa-clock"></i>
                                            <span>الوقت: <?php echo htmlspecialchars($session['session_time']); ?></span>
                                        </div>
                                        
                                        <div class="session-detail">
                                            <i class="fas fa-video"></i>
                                            <span>نوع الجلسة: <?php echo htmlspecialchars($session['session_type']); ?></span>
                                        </div>
                                        
                                        <?php if (!empty($session['meeting_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($session['meeting_link']); ?>" target="_blank" class="meeting-link">
                                                <i class="fas fa-external-link-alt"></i>
                                                <span>رابط الجلسة</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="no-data" style="padding: 20px;">
                                <i class="fas fa-calendar-times"></i>
                                <p>لا توجد جلسات حالياً مع هذا الأخصائي</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
