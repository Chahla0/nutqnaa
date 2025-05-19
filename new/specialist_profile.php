<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id']; // معرف الأخصائي

$sql = "SELECT users.full_name, users.email, specialists.phone, specialists.address, 
            specialists.qualification, specialists.experience, specialists.specialty
        FROM users 
        JOIN specialists ON users.id = specialists.user_id 
        WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$specialist = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي للأخصائي</title>
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
            padding: 0;
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            width: 90%;
            max-width: 700px;
            background-color: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 30px 0;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: var(--white);
            padding: 30px;
            text-align: center;
            position: relative;
        }

       

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            margin: 0 auto 15px;
            background-color: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-header h2 {
            font-size: 28px;
            margin: 10px 0 5px;
            font-weight: 700;
        }

        .profile-header p {
            font-size: 18px;
            margin: 0;
            opacity: 0.9;
        }

        .profile-body {
            padding: 40px;
            position: relative;
            z-index: 2;
        }

        .profile-section {
            margin-bottom: 30px;
        }

        .profile-section h3 {
            color: var(--dark-blue);
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--medium-gray);
            display: flex;
            align-items: center;
        }

        .profile-section h3 i {
            margin-left: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            background-color: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .info-label {
            color: var(--dark-green);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-left: 8px;
            font-size: 16px;
        }

        .info-value {
            font-size: 16px;
            color: var(--dark-gray);
            font-weight: 500;
        }

        .edit-btn {
            display: block;
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-blue));
            color: var(--white);
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 30px;
            transition: all 0.3s ease;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .profile-container {
                width: 95%;
            }
            
            .profile-body {
                padding: 30px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-md"></i>
            </div>
            <h2>د. <?php echo htmlspecialchars($specialist['full_name']); ?></h2>
            <p>أخصائي <?php echo htmlspecialchars($specialist['specialty']); ?></p>
        </div>

        <div class="profile-body">
            <div class="profile-section">
                <h3><i class="fas fa-id-card"></i> المعلومات الأساسية</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</div>
                        <div class="info-value"><?php echo htmlspecialchars($specialist['email']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> رقم الهاتف</div>
                        <div class="info-value"><?php echo htmlspecialchars($specialist['phone']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-map-marker-alt"></i> العنوان</div>
                        <div class="info-value"><?php echo htmlspecialchars($specialist['address']); ?></div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <h3><i class="fas fa-graduation-cap"></i> المؤهلات والخبرة</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-certificate"></i> المؤهل العلمي</div>
                        <div class="info-value"><?php echo htmlspecialchars($specialist['qualification']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-briefcase"></i> سنوات الخبرة</div>
                        <div class="info-value"><?php echo htmlspecialchars($specialist['experience']); ?> سنوات</div>
                    </div>
                </div>
            </div>

            <a href="edit_specialist_profile.php" class="edit-btn">
                <i class="fas fa-edit"></i> تعديل الملف الشخصي
            </a>
        </div>
    </div>
</body>
</html>