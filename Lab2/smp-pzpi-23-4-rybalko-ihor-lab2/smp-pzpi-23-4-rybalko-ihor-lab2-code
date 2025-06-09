#!/usr/bin/php -f

<?php

function nameMagazine() {
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
}

function showMainMenu() {
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function showProducts() {
    echo "№  НАЗВА                 ЦІНА\n";
    echo "1  Молоко пастеризоване  12\n";
    echo "2  Хліб чорний           9\n";
    echo "3  Сир білий             21\n";
    echo "4  Сметана 20%           25\n";
    echo "5  Кефір 1%              19\n";
    echo "6  Вода газована         18\n";
    echo "7  Печиво \"Весна\"        14\n";
    echo "   -----------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
}

$products = [
    1 => ['name' => 'Молоко пастеризоване', 'price' => 12],
    2 => ['name' => 'Хліб чорний', 'price' => 9],
    3 => ['name' => 'Сир білий', 'price' => 21],
    4 => ['name' => 'Сметана 20%', 'price' => 25],
    5 => ['name' => 'Кефір 1%', 'price' => 19],
    6 => ['name' => 'Вода газована', 'price' => 18],
    7 => ['name' => 'Печиво "Весна"', 'price' => 14],
];

$cart = [];
$userName = "";
$userAge = 0;

nameMagazine();

while (true) {
    showMainMenu();
    $input = trim(fgets(STDIN));

    switch ($input) {
        case '1':
            while (true) {
                showProducts();
                echo "Виберіть товар: ";
                $choice = trim(fgets(STDIN));

                if ($choice === '0') {
                    break;
                }

                if (!array_key_exists($choice, $products)) {
                    echo "ПОМИЛКА! Вказано неправильний номер товару\n";
                    continue;
                }

                $product = $products[$choice];
                echo "Вибрано: {$product['name']}\n";
                echo "Введіть кількість, штук: ";

                $inputQty = trim(fgets(STDIN));

                if (!ctype_digit($inputQty)) {
                    echo "ПОМИЛКА! Введіть ціле число від 0 до 99\n\n";
                    continue;
                }

                $amount = intval($inputQty);

                if ($amount >= 100) {
                    echo "ПОМИЛКА! Кількість має бути від 0 до 99\n\n";
                    continue;
                }

                if ($amount === 0) {
                    echo "ВИДАЛЯЮ ТОВАР З КОШИКА\n";
                    unset($cart[$choice]);
                } else {
                    $cart[$choice] = $amount;
                }

                if (empty($cart)) {
                    echo "КОШИК ПОРОЖНІЙ\n\n";
                } else {
                    echo "У КОШИКУ:\n";
                    echo "НАЗВА                 КІЛЬКІСТЬ\n";
                    foreach ($cart as $id => $qty) {
                        $name = $products[$id]['name'];
                        $spaces = 22 - mb_strlen($name, 'UTF-8');
                        echo $name . str_repeat(' ', $spaces) . "$qty\n";
                    }
                    echo "\n";
                }
            }
            break;

        case '2':
            echo "№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
            $total = 0;
            $i = 1;

            foreach ($cart as $id => $qty) {
                $name = $products[$id]['name'];
                $price = $products[$id]['price'];
                $value = $price * $qty;
                $total += $value;

                $nameLength = mb_strlen($name, 'UTF-8');
                $spaces = 21 - $nameLength;
                $paddedName = $name . str_repeat(' ', $spaces >= 0 ? $spaces : 0);

                printf("%-2d %s %-5d %-10d %-11d\n", $i++, $paddedName, $price, $qty, $value);
            }
            echo "РАЗОМ ДО CПЛАТИ: $total\n\n";
            break;

        case '3':
            while (true) {
                echo "Ваше ім'я: ";
                $name = trim(fgets(STDIN));
                if (!preg_match("/^[\p{L}\s'-]+$/u", $name)) {
                    echo "ПОМИЛКА! Ім'я може містити лише літери, апостроф «'», дефіс «-», пробіл\n\n";
                    continue;
                }
                $userName = $name;
                break;
            }

            while (true) {
                echo "Ваш вік: ";
                $age = intval(trim(fgets(STDIN)));
                if ($age < 7 || $age > 150) {
                    echo "ПОМИЛКА! Користувач повинен мати вік від 7 та до 150 років\n\n";
                    continue;
                }
                $userAge = $age;
                break;
            }

            echo "\n";
            echo "Ваше ім’я: $userName\n";
            echo "Ваш вік: $userAge\n\n";
            break;

        case '0':
            exit;

        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
            break;
    }
}
?>
