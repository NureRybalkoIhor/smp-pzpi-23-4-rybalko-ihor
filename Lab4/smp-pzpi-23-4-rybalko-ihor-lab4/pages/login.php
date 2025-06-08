<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$credentials = require_once __DIR__ . '/credential.php';

$error = '';

if (isset($_SESSION['username'])) {
    header('Location: index.php?page=products');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Будь ласка, заповніть всі поля.";
    } elseif ($username === $credentials['userName'] && $password === $credentials['password']) {
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        header('Location: index.php?page=products');
        exit;
    } else {
        $error = "Невірне ім'я користувача або пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        form {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        form h2 {
            font-weight: 900;
            font-size: 28px;
            margin-bottom: 30px;
            color: #000;
        }
        label {
            display: block;
            text-align: left;
            font-weight: 600;
            margin-bottom: 8px;
            color: #222;
            font-size: 16px;
        }
        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-bottom: 25px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #000;
            outline: none;
        }
        input[type="submit"] {
            background-color: #000;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 95%;
            letter-spacing: 1px;
        }
        input[type="submit"]:hover {
            background-color: #333;
        }
        .error {
            color: #b00020;
            background-color: #fdd;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <form method="POST" action="index.php?page=login" novalidate>
        <h2>Вхід на сайт</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <label for="username">Ім'я користувача:</label>
        <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" />

        <input type="submit" value="Login" />
    </form>
</body>
</html>