<?php
require_once "db/db.php";
require_once "header-nav.php";
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

    <main>    
        <h1>Авторизация</h1> 
        <form>
        <label>Логин
            <input type="text" name="login"> 
        </label> 
        <label>Пароль
            <input type="text" name="password"> 
        </label> 
        <button>Вход</button>
        </form> 
        <p class="error">
            <?php
            $password=strip_tags($_GET["password"] ?? "");
            $login=strip_tags($_GET["login"] ?? "");
            if ($login && $password){                
                //echo find($login,$password);
                if (find($login, $password)) {
                    echo "Успешная авторизация: " . $login . ", " . $password;
                } else {
                    echo "Ошибка авторизации: " . $login . ", " . $password . " - error";
                }
            }
            //echo "find($login,$password)";
            
            ?>
        </p>
        <?php
        require_once "footer.php";
        ?>
    </main>

    <script src="js/script.js"></script>
</body>
</html>