<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id'];

// جلب البيانات الحالية
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conn->prepare("SELECT users.full_name, users.email, specialists.phone, specialists.address, 
                                   specialists.qualification, specialists.experience, specialists.specialty 
                            FROM users 
                            JOIN specialists ON users.id = specialists.user_id 
                            WHERE users.id = ?");
    $stmt->bind_param("i", $specialist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
} else {
    // تحديث البيانات عند الإرسال
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $specialty = $_POST['specialty'];

    // تحديث جدول users
    $stmt1 = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
    $stmt1->bind_param("ssi", $full_name, $email, $specialist_id);
    $stmt1->execute();

    // تحديث جدول specialists
    $stmt2 = $conn->prepare("UPDATE specialists SET phone = ?, address = ?, qualification = ?, experience = ?, specialty = ? WHERE user_id = ?");
    $stmt2->bind_param("sssisi", $phone, $address, $qualification, $experience, $specialty, $specialist_id);
    $stmt2->execute();

    // إعادة التوجيه
    header("Location: specialist_dashboard.php?page=profile");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الملف الشخصي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            --error: #e74c3c;
            --success: #2ecc71;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            width: 90%;
            max-width: 700px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 40px;
            margin: 30px 0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
        }

        .form-header h2 {
            color: var(--dark-blue);
            font-size: 28px;
            margin: 0 0 10px;
            font-weight: 700;
        }

        .form-header p {
            color: var(--dark-gray);
            margin: 0;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .input-group {
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 600;
            font-size: 15px;
        }

        label.required::after {
            content: ' *';
            color: var(--error);
        }

        input, select, textarea {
            width:  95%;
            padding: 12px 15px;
            border: 3px solid var(--medium-gray);
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: var(--light-gray);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(133, 186, 226, 0.2);
        }

        .input-icon {
            position: absolute;
            left: 5px;
            top: 20px;
            color: var(--dark-green);
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-blue));
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--dark-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--dark-purple);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-user-edit"></i> تعديل الملف الشخصي</h2>
        <p>قم بتحديث معلوماتك الشخصية والمهنية</p>
    </div>

    <form method="post">
        <div class="form-row">
            <div class="form-group">
                <label class="required">الاسم الكامل</label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($data['full_name']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">البريد الإلكتروني</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>رقم الهاتف</label>
                <div class="input-group">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>العنوان</label>
                <div class="input-group">
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input type="text" name="address" value="<?= htmlspecialchars($data['address']) ?>">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="required">المؤهل العلمي</label>
                <div class="input-group">
                    <i class="fas fa-graduation-cap input-icon"></i>
                    <input type="text" name="qualification" value="<?= htmlspecialchars($data['qualification']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">سنوات الخبرة</label>
                <div class="input-group">
                    <i class="fas fa-briefcase input-icon"></i>
                    <input type="number" name="experience" value="<?= htmlspecialchars($data['experience']) ?>" min="0" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="required">التخصص</label>
            <div class="input-group">
                <i class="fas fa-stethoscope input-icon"></i>
                <input type="text" name="specialty" value="<?= htmlspecialchars($data['specialty']) ?>" required>
            </div>
        </div>

        <button type="submit">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>

        <a href="specialist_dashboard.php?page=profile" class="back-link">
            <i class="fas fa-arrow-right"></i> العودة إلى الملف الشخصي
        </a>
    </form>
</div>

</body>
</html>