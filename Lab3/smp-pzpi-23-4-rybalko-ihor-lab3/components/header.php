<!DOCTYPE html>
<html>
<head>
    <style>
        .header {
            width: 100%;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 25px 0;
            font-family: Arial, sans-serif;
            font-size: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .left,
        .header .center,
        .header .right {
            flex: 1;
        }

        .header .center {
            text-align: center;
        }

        .header .right {
            text-align: right;
        }

        .header a {
            text-decoration: none;
            color: black;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }

        .header img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .header .left a {
            margin-left: 30px;
        }

        .header .right a {
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="left">
            <a href="/index.php">
                <img src="/icons/home.png" alt="Home Icon">
                <span>Home</span>
            </a>
        </div>
        <div class="center">
            <a href="/pages/products.php">
                <img src="/icons/menu.png" alt="Products Icon">
                <span>Products</span>
            </a>
        </div>
        <div class="right">
            <a href="/pages/basket.php">
                <img src="/icons/cart.png" alt="Cart Icon">
                <span>Cart</span>
            </a>
        </div>
    </div>
</body>
</html>
