<?php
session_start();

$auth_required_pages = ['products', 'profile', 'basket'];
$public_pages = ['login'];
$all_pages = array_merge($auth_required_pages, $public_pages, ['home', 'logout', 'page404']);

$page = $_GET['page'] ?? 'home';

if (!in_array($page, $all_pages)) {
    http_response_code(404);
    include 'components/header.php';
    echo '<div style="text-align:center; margin: 40px; font-family: Arial, sans-serif;">
            <h1>Сторінка не знайдена</h1>
          </div>';
    include 'components/footer.php';
    exit;
}

if (isset($_SESSION['username']) && $page === 'login') {
    header('Location: index.php');
    exit;
}

if ($page === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (in_array($page, $auth_required_pages) && !isset($_SESSION['username'])) {
    header('Location: index.php?page=page404');
    exit;
}

if ($page !== 'login') {
    include 'components/header.php';
}


if (isset($_SESSION['auth_error'])) {
    echo '<p style="text-align:center; color:red; font-weight:bold; font-family: Arial, sans-serif;">' .
        htmlspecialchars($_SESSION['auth_error']) .
        '</p>';
    unset($_SESSION['auth_error']);
}

switch ($page) {
    case 'home':
        echo '<div style="text-align: center; margin: 40px; font-family: Arial, sans-serif;">
                <h1>Продовольчий магазин "Весна"</h1>
                <p>Все буде УКРАЇНА!</p>
              </div>';
        break;
    case 'login':
        include 'pages/login.php';
        break;
    case 'products':
        include 'pages/products.php';
        break;
    case 'basket':
        include 'pages/basket.php';
        break;
    case 'profile':
        include 'pages/profile.php';
        break;
    case 'page404':
        include 'pages/page404.php';
        break;
    default:
        http_response_code(404);
        echo '<div style="text-align:center; margin: 40px; font-family: Arial, sans-serif;">
                <h1>Сторінка не знайдена</h1>
              </div>';
        break;
}

if ($page !== 'login') {
    include 'components/footer.php';
}