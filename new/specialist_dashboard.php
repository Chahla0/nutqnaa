<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get specialist data
$specialist_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT specialty FROM specialists WHERE user_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$specialist_data = $result->fetch_assoc();
$specialty = $specialist_data['specialty'] ?? 'أخصائي علاج نطق'; // Default if not found
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الأخصائي</title>
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
            margin: 0;
            padding: 0;
            display: flex;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: var(--white);
            padding: 30px 20px;
            height: 100vh;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: var(--white);
            margin: 12px 0;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateX(-5px);
        }

        .sidebar a.active {
            background-color: var(--dark-blue);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .sidebar a i {
            margin-left: 10px;
            font-size: 18px;
        }

        .content {
            margin-right: 280px;
            padding: 40px;
            flex-grow: 1;
            background-color: var(--white);
            min-height: 100vh;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .content-header h1 {
            color: var(--dark-blue);
            font-size: 28px;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-green);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h2 {
            color: var(--dark-green);
            font-size: 20px;
            margin: 0;
        }
        .profile-header {
    text-align: center;
    padding: 20px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.profile-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border: 2px solid var(--primary-green);
    color: white;
    font-size: 30px;
}
        @media (max-width: 992px) {
            .sidebar {
                width: 240px;
                padding: 20px 15px;
            }
            
            .content {
                margin-right: 240px;
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }

            .content {
                margin-right: 0;
                padding: 20px;
            }
            
            .sidebar a {
                padding: 10px 12px;
                font-size: 14px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="sidebar">
    <div class="profile-header">
        <div class="profile-icon">
            <i class="fas fa-user-md"></i>
        </div>
        <h4 class="specialist-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
        <p class="specialist-role"><?php echo htmlspecialchars($specialty); ?></p>
    </div>
    
    <a href="specialist_dashboard.php?page=home" class="<?php echo ($_GET['page'] ?? 'home') === 'home' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        الرئيسية
    </a>
    <a href="specialist_dashboard.php?page=profile" class="<?php echo ($_GET['page'] ?? '') === 'profile' ? 'active' : ''; ?>">
        <i class="fas fa-user"></i>
        الملف الشخصي
    </a>
    <a href="specialist_dashboard.php?page=child_files" class="<?php echo ($_GET['page'] ?? '') === 'child_files' ? 'active' : ''; ?>">
        <i class="fas fa-folder-open"></i>
        ملفات الطفل
    </a> 
    <a href="specialist_dashboard.php?page=sessions" class="<?php echo ($_GET['page'] ?? '') === 'sessions' ? 'active' : ''; ?>">
        <i class="fas fa-calendar-alt"></i>
        الجلسات المحجوزة
    </a>
    <a href="specialist_dashboard.php?page=consultations" class="<?php echo ($_GET['page'] ?? '') === 'consultations' ? 'active' : ''; ?>">
        <i class="fas fa-comments"></i>
        الرد على الاستشارات
    </a>
    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        تسجيل الخروج
    </a>
</div>

<div class="content">
    <?php
    $page = $_GET['page'] ?? 'home';
    if ($page === 'profile') {
        include 'specialist_profile.php';
    } elseif ($page === 'sessions') {
        include 'specialist_sessions.php';
    }elseif ($page === 'consultations') {
        include 'specialist_consultations.php';
    }elseif ($page === 'child_files') {
        include 'specialist_child_files.php';
    }elseif ($page === 'home') {
        include 'specialist_home.php';
    }
    else {
        echo "الصفحة غير موجودة.";
    }
    ?>
</div>

</body>
</html>