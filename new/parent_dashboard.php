<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: login.html");
    exit();
}


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة ولي الأمر</title>
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
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
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
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background-color: rgba(248, 215, 218, 0.3);
            color: #721c24;
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .logout-btn a:hover {
            background-color: rgba(248, 215, 218, 0.5);
        }
        
        .logout-btn a i {
            margin-left: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Main Content Styles */
        .content {
            flex-grow: 1;
            padding: 30px;
            background-color: var(--bg-light);
        }
        
        .welcome-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px; /* Increased padding */
            min-height: 550px; /* Set minimum height */
            height: 35vh; /* Or use viewport height units */
            max-height: 600px; /* Maximum height */
            position: relative;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-blue);
        }

        .welcome-content {
            flex: 1;
            padding-left: 40px; /* Increased space */
        }

        .welcome-content h1 {
            font-size: 2.2rem; /* Larger heading */
            margin-bottom: 20px; /* More spacing */
        }

        .welcome-content p {
            font-size: 1.1rem; /* Slightly larger text */
            line-height: 1.8; /* Better readability */
            margin-bottom: 15px;
        }

        .welcome-image {
            width: 350px; /* Larger image container */
            height: 90%; /* Take full height of parent */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .welcome-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Quick Stats */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.2rem;
            color: white;
        }
        
        .stat-icon.blue {
            background: var(--primary-blue);
        }
        
        .stat-icon.green {
            background: var(--primary-green);
        }
        
        .stat-icon.purple {
            background: var(--primary-purple);
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: var(--text-light);
            font-size: 0.9rem;
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
        
        /* Responsive design */
        /* Responsive design */
@media (max-width: 992px) {
    .welcome-card {
        min-height: 250px;
        height: auto;
    }
    
    .welcome-image {
        width: 250px;
    }
}

@media (max-width: 768px) {
    .welcome-card {
        flex-direction: column-reverse;
        min-height: auto;
        padding: 30px;
    }
    
    .welcome-content {
        padding-left: 0;
        margin-top: 25px;
        text-align: center;
    }
    
    .welcome-image {
        width: 100%;
        height: 200px;
    }
    
    .welcome-content h1 {
        font-size: 1.8rem;
    }
}
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                padding: 20px 0;
                position: relative;
            }
            
            .profile-section {
                padding: 0 15px 15px;
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
            
            .content {
                padding: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
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
    
    <div class="content">
    <div class="welcome-card">
        <div class="welcome-content">
            <h1>مرحبًا بك <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
            <p>نظام علاج النطق يساعدك على متابعة تطور طفلك والتواصل مع أفضل الأخصائيين.</p>
            <p>يمكنك من خلال هذه اللوحة إدارة ملف طفلك وحجز الجلسات وطلب الاستشارات اليومية.</p>
        </div>
        <div class="welcome-image">
            <img src="../new/images/parents.png" alt="علاج النطق">
        </div>
    </div>
    
    <!-- Rest of your content -->
</div>
</body>
</html>