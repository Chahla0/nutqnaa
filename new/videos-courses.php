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
    <title>لوحة ولي الأمر - الدورات التعليمية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: var(--shadow-md);
            padding: 20px 0;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }
        
        .profile-section {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(133, 186, 226, 0.2);
            margin-bottom: 20px;
        }
        
        .profile-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }
        
        .profile-section h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .profile-section p {
            color: var(--text-light);
            font-size: 0.85rem;
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
            padding: 12px 15px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        nav a:hover, nav a.active {
            background: linear-gradient(90deg, rgba(133, 186, 226, 0.1) 0%, rgba(146, 202, 199, 0.1) 100%);
            color: var(--primary-blue);
            transform: translateX(-3px);
        }
        
        nav a i {
            margin-left: 10px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        /* Logout Button */
        .logout-btn {
            margin-top: auto;
            padding: 0 15px;
        }
        
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
            padding: 30px;
            overflow-y: auto;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            color: var(--primary-blue);
            font-size: 1.8rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-green);
        }
        
        
        
        /* Courses Section */
        .category-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .category-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            background-color: #e1e5eb;
            color: var(--text-dark);
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .category-btn:hover {
            background-color: var(--primary-blue);
            color: white;
        }
        
        .category-btn.active {
            background-color: var(--primary-purple);
            color: white;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .course-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .course-thumbnail {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        
        .course-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .course-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 10px;
            align-self: flex-start;
        }
        
        
        
        .course-badge {
            background-color: var(--primary-purple);
            color: white;
        }
        
        .course-title {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .course-description {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .course-action {
            margin-top: auto;
        }
        
        .watch-btn {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 6px;
            background-color: var(--primary-blue);
            color: white;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }
        
        .watch-btn:hover {
            background-color: var(--primary-purple);
        }
        
        .watch-btn i {
            font-size: 0.8rem;
        }
        
        /* Logout Modal Styles */
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .logout-modal-content {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logout-icon {
            font-size: 50px;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        .logout-message {
            font-size: 18px;
            margin-bottom: 25px;
            color: var(--text-dark);
        }

        .logout-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-btn {
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-family: 'Tajawal', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .modal-btn-cancel {
            background-color: var(--medium-gray);
            color: var(--text-dark);
        }

        .modal-btn-confirm {
            background-color: #f05050;
            color: white;
        }

        .modal-btn-confirm:hover {
            background-color: #e04141;
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
        
        @media (max-width: 768px) {
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .category-filter {
                justify-content: center;
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
                    <a href="parent_dashboard.php">
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
                <a href="#" onclick="confirmLogout(); return false;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </div>
        </nav>
    </div>
    
    <div class="main-content">
      
        <h1 class="page-title"> <i class="fas fa-video" style="margin-left: 15px;"></i>فيديوهات ودورا</h1>
        
        <div class="category-filter">
            <button class="category-btn active" data-category="all">الكل</button>
            <button class="category-btn" data-category="videos">فيديوهات تعليمية</button>
            <button class="category-btn" data-category="courses">دورات تدريبية</button>
            <button class="category-btn" data-category="autism">التوحد</button>
            <button class="category-btn" data-category="speech">النطق والتخاطب</button>
        </div>
        
        <div class="courses-grid" id="mediaContainer">
            <!-- فيديو 1 -->
            <div class="course-card" data-category="videos,autism">
                <img src="https://img.youtube.com/vi/DjcLdXkmp-A/0.jpg" alt="فيديو تعليمي" class="course-thumbnail">
                <div class="course-body">
                    <span class="course-badge video-badge">فيديو</span>
                    <h3 class="course-title">كيفية التعامل مع نوبات الغضب</h3>
                    <p class="course-description">تعلم أساليب فعالة للتعامل مع نوبات الغضب عند الأطفال</p>
                    <div class="course-action">
                        <button class="watch-btn" data-url="https://www.youtube.com/watch?v=DjcLdXkmp-A">
                            <i class="fas fa-play"></i> مشاهدة
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- دورة 1 -->
            <div class="course-card" data-category="courses,speech">
                <img src="https://img.youtube.com/vi/yWwugSoHo6E/0.jpg" alt="دورة تدريبية" class="course-thumbnail">
                <div class="course-body">
                    <span class="course-badge">دورة</span>
                    <h3 class="course-title">تطوير مهارات النطق</h3>
                    <p class="course-description">دورة متكاملة لتحسين مهارات النطق عند الأطفال</p>
                    <div class="course-action">
                        <button class="watch-btn" data-url="https://maharat-rt.com/courses/%D8%AF%D9%88%D8%B1%D8%A9-%D8%AA%D8%AD%D8%B3%D9%8A%D9%86-%D8%A7%D9%84%D8%B3%D9%84%D9%88%D9%83-%D8%A7%D9%84%D9%84%D9%81%D8%B8%D9%8A-%D8%B7%D9%81%D9%84%D9%8A-%D8%A7%D9%84%D9%86%D8%A7%D8%B7%D9%82/">
                            <i class="fas fa-play"></i> مشاهدة
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- فيديو 2 -->
            <div class="course-card" data-category="videos,speech">
                <img src="https://img.youtube.com/vi/eQpEahg2nCM/0.jpg" alt="فيديو تعليمي" class="course-thumbnail">
                <div class="course-body">
                    <span class="course-badge video-badge">فيديو</span>
                    <h3 class="course-title">تمارين تحسين النطق</h3>
                    <p class="course-description">مجموعة من التمارين اليومية لتحسين النطق</p>
                    <div class="course-action">
                        <button class="watch-btn" data-url="https://youtu.be/eQpEahg2nCM?si=6O7HFnQKqBWpw5tA">
                            <i class="fas fa-play"></i> مشاهدة
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- دورة 2 -->
            <div class="course-card" data-category="courses,autism">
                <img src="https://via.placeholder.com/400x225?text=دورة+التوحد" alt="دورة تدريبية" class="course-thumbnail">
                <div class="course-body">
                    <span class="course-badge">دورة</span>
                    <h3 class="course-title">فهم عالم التوحد</h3>
                    <p class="course-description">دورة شاملة لفهم خصائص الأطفال المصابين بالتوحد</p>
                    <div class="course-action">
                        <button class="watch-btn" data-url="https://www.edraak.org/programs/course/aut_101-vsp_2017/">
                            <i class="fas fa-play"></i> مشاهدة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logout Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <div class="logout-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="logout-message">
                هل أنت متأكد من تسجيل الخروج؟
            </div>
            <div class="logout-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <button class="modal-btn modal-btn-confirm" onclick="performLogout()">
                    <i class="fas fa-sign-out-alt"></i> نعم، سجل خروج
                </button>
            </div>
        </div>
    </div>
 
    <script>
        // Filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                const mediaItems = document.querySelectorAll('#mediaContainer .course-card');
                
                mediaItems.forEach(item => {
                    if (category === 'all' || item.dataset.category.includes(category)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Watch button functionality
        document.querySelectorAll('.watch-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (url.endsWith('.html') || url.includes('course-details')) {
                    window.location.href = url;
                } else {
                    window.open(url, '_blank');
                }
            });
        });

        // Logout modal functions
        function confirmLogout() {
            document.getElementById('logoutModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function performLogout() {
            window.location.href = 'logout.php';
        }

        window.onclick = function(event) {
            if (event.target.className === 'logout-modal') {
                closeLogoutModal();
            }
        }
    </script>
</body>
</html>