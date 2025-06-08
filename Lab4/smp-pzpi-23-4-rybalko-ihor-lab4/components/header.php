<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'home';
$is_logged_in = isset($_SESSION['username']);

if ($page === 'page404') {
    $menu_items = [
        'home' => ['icon' => 'home.png', 'label' => 'Home'],
        'products' => ['icon' => 'menu.png', 'label' => 'Products'],
        'login' => ['icon' => 'login.png', 'label' => 'Login'],
    ];
} elseif ($is_logged_in) {
    $menu_items = [
        'home' => ['icon' => 'home.png', 'label' => 'Home'],
        'products' => ['icon' => 'menu.png', 'label' => 'Products'],
        'basket' => ['icon' => 'cart.png', 'label' => 'Cart'],
        'profile' => ['icon' => 'profile.png', 'label' => 'Profile'],
    ];
} else {
    $menu_items = [
        'home' => ['icon' => 'home.png', 'label' => 'Home'],
        'products' => ['icon' => 'menu.png', 'label' => 'Products'],
        'login' => ['icon' => 'login.png', 'label' => 'Login'],
    ];
}
?>

<div class="header">
    <?php foreach ($menu_items as $page_key => $item): ?>
        <div class="section">
            <a href="/index.php?page=<?= htmlspecialchars($page_key) ?>">
                <img src="/icons/<?= htmlspecialchars($item['icon']) ?>" alt="<?= htmlspecialchars($item['label']) ?> Icon" />
                <span><?= htmlspecialchars($item['label']) ?></span>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<style>
.header {
    width: 100%;
    border-top: 2px solid #000;
    border-bottom: 2px solid #000;
    padding: 25px 30px;
    font-family: Arial, sans-serif;
    font-size: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .section {
    flex: 1;
    text-align: center;
}

.header a {
    text-decoration: none;
    color: black;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.header a img {
    width: 30px;
    height: 30px;
}
</style>