<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profileFile = __DIR__ . '/profile_data.php';

$profile = file_exists($profileFile) ? include $profileFile : [
    'firstName' => '',
    'lastName' => '',
    'birthDate' => '',
    'info' => '',
    'photo' => ''
];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $birthDate = $_POST['birthDate'] ?? '';
    $info = trim($_POST['info'] ?? '');

    if (mb_strlen($firstName) < 2) {
        $errors[] = "Ім'я повинно містити принаймні 2 символи.";
    }

    if (mb_strlen($lastName) < 2) {
        $errors[] = "Прізвище повинно містити принаймні 2 символи.";
    }

    if (!$birthDate || strtotime($birthDate) === false) {
        $errors[] = "Вкажіть коректну дату народження.";
    } else {
        $age = (int) ((time() - strtotime($birthDate)) / (365.25 * 24 * 3600));
        if ($age < 16) {
            $errors[] = "Вік повинен бути не менше 16 років.";
        }
    }

    if (mb_strlen($info) < 50) {
        $errors[] = "Стислий опис повинен містити не менше 50 символів.";
    }

    $photoPath = $profile['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 МБ

        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $errors[] = "Фото повинно бути у форматі jpg, png або gif.";
        } elseif ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Помилка завантаження файлу.";
        } elseif ($_FILES['photo']['size'] > $maxFileSize) {
            $errors[] = "Розмір файлу не повинен перевищувати 2 МБ.";
        } else {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $newFilename = uniqid('photo_', true) . '.' . $ext;
            $targetFile = $uploadDir . $newFilename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photoPath = 'Uploads/' . $newFilename;
            } else {
                $errors[] = "Не вдалося зберегти файл.";
            }
        }
    }

    if (empty($errors)) {
        $profile = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'birthDate' => $birthDate,
            'info' => $info,
            'photo' => $photoPath
        ];

        $content = '<?php return ' . var_export($profile, true) . ';';
        file_put_contents($profileFile, $content);

        $success = "Дані профілю успішно збережено.";
    }
}

$photoFullPath = $profile['photo'] ? __DIR__ . '/../' . $profile['photo'] : '';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Профіль користувача</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2em;
            background-color: #f8f9fa;
        }

        h2 {
            text-align: center;
        }

        .profile-form {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 2em;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 30px;
            margin-bottom: 15px;
        }

        .left-col {
            width: 30%;
            text-align: center;
        }

        .left-col img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .left-col input[type="file"] {
            display: none;
        }

        .right-col {
            width: 70%;
        }

        .form-group {
            display: flex;
            gap: 15px;
        }

        .form-group > div {
            flex: 1;
        }

        label {
            display: block;
            margin: 12px 0 4px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .messages {
            max-width: 900px;
            margin: 1em auto;
        }

        .messages p {
            padding: 10px;
            border-radius: 6px;
        }

        .messages .success {
            background-color: #d4edda;
            color: #155724;
        }

        .messages .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .profile-photo {
            width: 100%;
            max-width: 200px;
            border-radius: 12px;
            cursor: pointer;
        }

        .photo-placeholder {
            width: 100%;
            padding-bottom: 100%;
            background: #eee;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }

        .submit-btn:disabled,
        .logout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h2>Профіль користувача</h2>

    <div class="messages">
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if ($errors): ?>
            <?php foreach ($errors as $error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data" class="profile-form" id="profileForm">
        <div class="left-col">
            <label for="photoInput">
                <?php if ($profile['photo'] && file_exists($photoFullPath)): ?>
                    <img src="/Uploads/<?= htmlspecialchars(basename($profile['photo'])) ?>" alt="Фото профілю"
                        title="Натисніть, щоб змінити фото" class="profile-photo" />
                <?php else: ?>
                    <div class="photo-placeholder">
                        <span>Завантажте фото</span>
                    </div>
                <?php endif; ?>
            </label>
            <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/gif" />
        </div>

        <div class="right-col">
            <div class="form-group">
                <div>
                    <label for="firstName">Ім’я</label>
                    <input id="firstName" type="text" name="firstName"
                        value="<?= htmlspecialchars($profile['firstName']) ?>" />
                </div>
                <div>
                    <label for="lastName">Прізвище</label>
                    <input id="lastName" type="text" name="lastName"
                        value="<?= htmlspecialchars($profile['lastName']) ?>" />
                </div>
                <div>
                    <label for="birthDate">Дата народження</label>
                    <input id="birthDate" type="date" name="birthDate"
                        value="<?= htmlspecialchars($profile['birthDate']) ?>" />
                </div>
            </div>

            <label for="info">Стислий опис</label>
            <textarea id="info" name="info"><?= htmlspecialchars($profile['info']) ?></textarea>

            <div class="button-group">
                <button type="button" class="logout-btn" onclick="window.location.href='/pages/logout.php'">Logout</button>
                <button type="submit" class="submit-btn">Зберегти</button>
            </div>
        </div>
    </form>

    <script>
        document.querySelector('label[for="photoInput"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('photoInput').click();
        });

        document.getElementById('photoInput').addEventListener('change', function () {
            if (this.files.length > 0) {
                if (confirm('Завантажити нове фото?')) {
                    const form = document.getElementById('profileForm');
                    const formData = new FormData(form);
                    const submitButton = form.querySelector('.submit-btn');
                    submitButton.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Помилка завантаження фото.');
                            submitButton.disabled = false;
                        }
                    }).catch(() => {
                        alert('Помилка сервера.');
                        submitButton.disabled = false;
                    });
                } else {
                    this.value = '';
                }
            }
        });

        document.getElementById('profileForm').addEventListener('submit', function (e) {
            const submitButton = this.querySelector('.submit-btn');
            if (submitButton.disabled) {
                e.preventDefault();
            } else {
                submitButton.disabled = true;
            }
        });
    </script>
</body>
</html>