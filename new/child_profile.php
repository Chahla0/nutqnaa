<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");

$childData = [];
$stmt = $conn->prepare("SELECT child_name, child_age, autism_level FROM parents WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $childData = $result->fetch_assoc();
}

$child_name = $childData['child_name'] ?? 'غير مسجل';
$child_age = $childData['child_age'] ?? 'غير مسجل';
$autism_level = $childData['autism_level'] ?? 'غير محدد';
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بيانات الطفل</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
            padding: 30px 0;
            height: 100vh;
            position: sticky;
            top: 0;
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
        
        .child-profile {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .child-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-purple);
            margin: 0 auto 25px;
            display: block;
            box-shadow: 0 4px 12px rgba(153, 153, 201, 0.2);
        }
        
        .child-info {
            text-align: center;
        }
        
        .child-info h3 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .child-info h3 i {
            margin-left: 10px;
        }
        
        .child-info p {
            font-size: 1.1rem;
            margin-bottom: 15px;
            padding-right: 20px;
            position: relative;
            text-align: right;
        }
        
        .child-info p:before {
            content: "";
            position: absolute;
            right: 0;
            top: 10px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--primary-green);
        }
        
        .child-info p strong {
            color: var(--primary-blue);
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: var(--text-light);
        }
        
        .no-data i {
            font-size: 40px;
            color: var(--primary-purple);
            margin-bottom: 15px;
        }
        
        .no-data h3 {
            margin-bottom: 10px;
            color: var(--primary-blue);
        }
        
        .add-btn {
            display: inline-block;
            background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-blue) 100%);
            color: white;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(146, 202, 199, 0.3);
        }
        
        .add-btn i {
            margin-left: 8px;
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
                padding: 20px 0;
            }
            
            .main-content {
                padding: 30px 20px;
            }
            
            .child-profile {
                padding: 25px;
            }
        }
        
        @media (max-width: 576px) {
            .child-avatar {
                width: 120px;
                height: 120px;
            }
            
            .child-info p {
                font-size: 1rem;
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
        }
    </style>
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
                    <a href="child_profile.php" class="active">
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
        <div class="child-profile">
            <?php if (!empty($childData)): ?>
                <div class="child-info">
                    <h3><i class="fas fa-child"></i> بيانات الطفل</h3>
                    <p><strong>اسم الطفل:</strong> <?php echo htmlspecialchars($child_name); ?></p>
                    <p><strong>العمر:</strong> <?php echo htmlspecialchars($child_age); ?> سنوات</p>
                    <p><strong>مستوى التوحد:</strong> <?php echo htmlspecialchars($autism_level); ?></p>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>لا توجد بيانات للطفل مسجلة</h3>
                    <p>يجب عليك إضافة بيانات طفلك لتتمكن من استخدام الخدمات</p>
                    <a href="edit_child_profile.php" class="add-btn">
                        <i class="fas fa-plus"></i> إضافة بيانات الطفل
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
// your existing PHP content like child profile display
?>
</body>
</html>