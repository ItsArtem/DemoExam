<?php
require_once "db/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой не сам</title>
    <link rel='icon' href='images/logo.jpeg'>
    <link rel='stylesheet' href='css/style.css'>
</head>
<body>
    <header> <img src='images/logo.jpeg' alt='логотип'>
        <h1>Мой не Сам</h1>
    </header>

    <nav>
        <a href="/DemoExam/Moy_Ne_Sam">Главная</a>
        <a href="/DemoExam/Moy_Ne_Sam/admin">Админ-панель</a>
    </nav>

    <main>    
        <h1>Авторизация</h1>
        <label for="">Логин</label>
        <input type="text" name="login">

        <label for="">Пароль</label>
        <input type="text" name="password">
        <button>Вход</button>
        <p class="error">
        <?php
            echo find();
        ?>
        </p>
        <footer>
            <h3>2025</h3>
        <footer>
    </main>

    <script src="js/script.js"></script>
</body>
</html>