<?php
require_once __DIR__ . '/../db_config.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (isset($_GET['remove'])) {
    $removeCode = $_GET['remove'];
    if (isset($cart[$removeCode])) {
        unset($cart[$removeCode]);
        $_SESSION['cart'] = $cart;
        if (isset($_SESSION['last_form_data'][$removeCode])) {
            unset($_SESSION['last_form_data'][$removeCode]);
        }
        header('Location: index.php?page=basket');
        exit;
    }
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    unset($_SESSION['last_form_data']);
    header('Location: index.php?page=basket');
    exit;
}

if (isset($_GET['checkout'])) {
    $_SESSION['cart'] = [];
    unset($_SESSION['last_form_data']);
    header('Location: index.php?page=basket&checkout_success=1');
    exit;
}
?>

<div style="max-width: 800px; margin: 20px auto; padding: 20px; font-family: Arial, sans-serif;">
    <h2>Ваш кошик</h2>

    <?php if (isset($_GET['checkout_success'])): ?>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 400px; text-align: center;">
            <p style="font-size: 24px; color: #333; margin-bottom: 20px;">Ваше замовлення успішно оформлено!</p>
            <a href="index.php?page=products" style="background-color: #2196F3; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-size: 18px; display: inline-block;">Перейти до покупок</a>
        </div>
    <?php elseif (empty($cart)): ?>
        <p style="background-color: #f8f8f8; padding: 15px; border-radius: 5px;">Кошик порожній.</p>
        <div style="margin-top: 20px;">
            <a href="index.php?page=products" style="background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block;">Перейти до покупок</a>
        </div>
    <?php else: ?>
        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #e0e0e0;">
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">№</th>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Назва</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Ціна</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Кількість</th>
                        <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Сума</th>
                        <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($cart as $code => $qty): ?>
                        <?php
                        $product = getProductByCode($code);
                        if (!$product) continue;
                        $price = $product['price'];
                        $itemTotal = $price * $qty;
                        $total += $itemTotal;
                        ?>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;"><?= $i ?></td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 40px; height: 40px; margin-right: 10px; background-color: #eee; display: flex; justify-content: center; align-items: center; border-radius: 4px;">
                                        <img src="../icons/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 30px; max-height: 30px;">
                                    </div>
                                    <?= htmlspecialchars($product['name']) ?>
                                </div>
                            </td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;"><?= $price ?> грн</td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;"><?= $qty ?></td>
                            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee; color: #4CAF50; font-weight: bold;"><?= $itemTotal ?> грн</td>
                            <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
                                <a href="index.php?page=basket&remove=<?= urlencode($code) ?>" style="display: inline-block;" onclick="return confirm('Видалити товар з кошика?')">
                                    <img src="../icons/delete.png" alt="Видалити" style="width: 20px; height: 20px;">
                                </a>
                            </td>
                        </tr>
                    <?php $i++; endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="padding: 10px; text-align: right; font-weight: bold;">Загальна вартість:</td>
                        <td style="padding: 10px; text-align: right; color: #4CAF50; font-weight: bold;"><?= $total ?> грн</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: space-between;">
            <div>
                <a href="index.php?page=products" style="background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block;">Повернутися до продуктів</a>
            </div>
            <div>
                <a href="index.php?page=basket&clear=1" style="background-color: #f44336; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-right: 10px;" onclick="return confirm('Очистити весь кошик?')">Очистити кошик</a>
                <a href="index.php?page=basket&checkout=1" style="background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block;" onclick="return confirm('Ви впевнені, що хочете оформити замовлення?')">Оформити замовлення</a>
            </div>
        </div>
    <?php endif; ?>
</div>