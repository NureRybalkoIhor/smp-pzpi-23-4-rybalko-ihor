<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getDBConnection() {
    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/shop.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Помилка підключення до бази даних: ' . $e->getMessage());
    }
}

function createProductsTable() {
    $pdo = getDBConnection();
    $sql = "
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT UNIQUE NOT NULL,
            name TEXT NOT NULL,
            price REAL NOT NULL,
            image TEXT NOT NULL
        )
    ";
    $pdo->exec($sql);
}

function initializeProducts() {
    $pdo = getDBConnection();

    $stmt = $pdo->query('SELECT COUNT(*) FROM products');
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        $products = [
            ['code' => 'milk', 'name' => 'Молоко пастеризоване', 'price' => 12, 'image' => 'milk.png'],
            ['code' => 'bread', 'name' => 'Хліб чорний', 'price' => 9, 'image' => 'bread.png'],
            ['code' => 'cheese', 'name' => 'Сир білий', 'price' => 21, 'image' => 'cheese.png'],
            ['code' => 'sour_cream', 'name' => 'Сметана 20%', 'price' => 25, 'image' => 'whip-cream.png'],
            ['code' => 'kefir', 'name' => 'Кефір 1%', 'price' => 19, 'image' => 'kefir.png'],
            ['code' => 'water', 'name' => 'Вода газована', 'price' => 18, 'image' => 'sparkling-water.png'],
            ['code' => 'cookies', 'name' => 'Печиво \"Весна\"', 'price' => 14, 'image' => 'cookie.png']
        ];
        
        $stmt = $pdo->prepare('INSERT INTO products (code, name, price, image) VALUES (?, ?, ?, ?)');
        foreach ($products as $product) {
            $stmt->execute([$product['code'], $product['name'], $product['price'], $product['image']]);
        }
    }
}

function getAllProducts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query('SELECT * FROM products ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductByCode($code) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT * FROM products WHERE code = ?');
    $stmt->execute([$code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

createProductsTable();
initializeProducts();
?>