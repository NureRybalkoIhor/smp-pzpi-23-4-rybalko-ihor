<?php

http_response_code(404);

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Доступ заборонено</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            font-size: 48px;
            color: #d9534f;
        }
        p {
            font-size: 24px;
        }
        a {
            color: #0275d8;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin: 10 0 10 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Доступ заборонено</h1>
        <p>Для перегляду цього контенту, будь ласка, <a href="index.php?page=login">авторизуйтеся</a>.</p>
    </div>
</body>
</html>