<?php
require_once "db/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой не сам <?php echo $pagetitle ?></title>
    <link rel='icon' href='images/logo.jpeg'>
    <link rel='stylesheet' href='css/style.css'>
</head>

<body>
<header> 
        <img src='images/logo.jpeg' alt='логотип'>
        <h1>Мой не сам</h1>
    </header>

    <nav>
        <a href="/DemoExam/Moy_Ne_Sam">Авторизация</a>
        <a href="/DemoExam/Moy_Ne_Sam/zayavka.php">Заявки</a>
        <a href="/DemoExam/Moy_Ne_Sam/admin.php">Админ-панель</a>
        <a href="/DemoExam/Moy_Ne_Sam/registration.php">Регистрация</a>
        <a href="/DemoExam/Moy_Ne_Sam/create-zayavka.php">Создание заявки</a>
    </nav>

    <main>
        <h1>
            <?php
            echo $pagetitle;
            ?>
        </h1>
        <div class="content">
            <?php
            echo $pagecontent ?? "";
            ?>
        </div>
        <footer>
            <h3>2025</h3>
        <footer>
    </main>

<script src="js/script.js"></script>
</body>
</html>