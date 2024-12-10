<?php
// Function to validate and sanitize text input
function validateText($input) {
    // Remove leading and trailing whitespace
    $input = trim($input);
    
    // Check if input is empty after trimming
    if (empty($input)) {
        return null;
    }
    
    // Sanitize input to prevent XSS
    return htmlspecialchars($input);
}

// Process form submission
$errors = [];

// Validate name
$usr = validateText($_POST['user'] ?? '');
if ($usr === null) {
    $errors[] = "姓名不可為空白";
}

// Validate email
$emal = validateText($_POST['email'] ?? '');
if ($emal === null) {
    $errors[] = "電子郵件不可為空白 ";
}

// Process gender
$sex = isset($_POST['sex']) ? ($_POST['sex'] == 1 ? "男" : "女") : "未選擇";

// Process skills
$wts = "";
if (isset($_POST['wt']) && is_array($_POST['wt'])) {
    $wts = implode(", ", $_POST['wt']);
}

// Process age
$age = $_POST['age'] ?? "未選擇";

// Process file upload
$profile_pic_path = "";
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
    $file_type = $_FILES['profile_pic']['type'];
    
    if (in_array($file_type, $allowed_types)) {
        $file_name = uniqid() . '_' . $_FILES['profile_pic']['name'];
        $upload_path = __DIR__ . '/' . $file_name;
        
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
            $profile_pic_path = $file_name;
        } else {
            $errors[] = "檔案上傳失敗";
        }
    } else {
        $errors[] = "檔案類型錯誤。僅允許 JPEG, PNG, GIF, BMP 格式 ";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>表單結果 (Form Results)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .result-container {
            background-color: rgb(92, 144, 43);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .profile-pic {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <?php if (!empty($errors)): ?>
            <div class="error">
                <h2>錯誤訊息 (Error Messages):</h2>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h2>表單結果 (Form Results)</h2>
        <table>
            <tr>
                <th>欄位 (Field)</th>
                <th>資料 (Data)</th>
            </tr>
            <tr>
                <td>姓名 (Name)</td>
                <td><?php echo $usr; ?></td>
            </tr>
            <tr>
                <td>電子郵件 (Email)</td>
                <td><?php echo $emal; ?></td>
            </tr>
            <tr>
                <td>性別 (Gender)</td>
                <td><?php echo $sex; ?></td>
            </tr>
            <tr>
                <td>專長 (Skills)</td>
                <td><?php echo $wts; ?></td>
            </tr>
            <tr>
                <td>年齡 (Age)</td>
                <td><?php echo $age; ?></td>
            </tr>
        </table>

        <?php if (!empty($profile_pic_path)): ?>
            <h3>個人照片 (Profile Picture)</h3>
            <img src="<?php echo $profile_pic_path; ?>" alt="Profile Picture" class="profile-pic">
        <?php endif; ?>
    </div>
</body>
</html>
