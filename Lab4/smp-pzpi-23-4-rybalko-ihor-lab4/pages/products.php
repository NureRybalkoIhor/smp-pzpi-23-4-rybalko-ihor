<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_config.php';

$errors = [];
$submitted = $_POST ?? [];

$products = getAllProducts();

$currentCart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validItems = [];

    foreach ($products as $product) {
        $code = $product['code'];
        $qty = isset($_POST[$code]) ? (int)$_POST[$code] : 0;

        if ($qty < 0) {
            $errors[] = "Кількість для «{$product['name']}» не може бути від’ємною.";
        } elseif ($qty > 0) {
            $validItems[$code] = $qty;
        }
    }

    if (empty($errors)) {
        $_SESSION['cart'] = $validItems;
        $_SESSION['last_form_data'] = $_POST;
        header('Location: /index.php?page=basket');
        exit;
    } else {
        $_SESSION['last_form_data'] = $_POST;
    }
}

$formData = [];
if (!empty($_SESSION['last_form_data'])) {
    $formData = $_SESSION['last_form_data'];
} elseif (!empty($currentCart)) {
    $formData = $currentCart;
}

?>

<div style="max-width: 800px; margin: 20px auto; padding: 20px; font-family: Arial, sans-serif;">
    <h2>Наші продукти</h2>

    <?php if (!empty($errors)): ?>
        <div class="error" style="background-color: #ffebee; border: 1px solid #f44336; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Перевірте будь ласка введені дані:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/index.php?page=products" style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd; background-color: #f2f2f2;">
                    <th style="padding: 10px; text-align: center;">№</th>
                    <th style="padding: 10px; text-align: left;">НАЗВА</th>
                    <th style="padding: 10px; text-align: right;">ЦІНА</th>
                    <th style="padding: 10px; text-align: right;">КІЛЬКІСТЬ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($products as $product): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; text-align: center;"><?= $i ?></td>
                        <td style="padding: 10px;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 50px; height: 50px; margin-right: 10px; background-color: #eee; display: flex; justify-content: center; align-items: center; border-radius: 4px;">
                                    <img src="../icons/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 40px; max-height: 40px;">
                                </div>
                                <span style="font-weight: bold;"><?= htmlspecialchars($product['name']) ?></span>
                            </div>
                        </td>
                        <td style="padding: 10px; text-align: right;"><?= number_format($product['price'], 2, ',', ' ') ?> грн</td>
                        <td style="padding: 10px; text-align: right;">
                            <input 
                                type="number" 
                                name="<?= htmlspecialchars($product['code']) ?>" 
                                min="0" 
                                value="<?= isset($formData[$product['code']]) ? (int)$formData[$product['code']] : 0 ?>" 
                                style="padding: 8px; width: 60px; border: 1px solid #ddd; border-radius: 4px;"
                            >
                        </td>
                    </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px; text-align: right;">
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Додати до кошика
            </button>
        </div>
    </form>
</div>