<?php
$pageTitle = 'Регистрация';
require_once "struktura.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $otchestvo = trim($_POST['otchestvo'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $errors = [];
    if (empty($login)) $errors[] = "Логин обязателен для заполнения";
    if (empty($password)) $errors[] = "Пароль обязателен для заполнения";
    if (empty($surname)) $errors[] = "Фамилия обязательна для заполнения";
    if (empty($name)) $errors[] = "Имя обязательно для заполнения";
    if (empty($otchestvo)) $errors[] = "Отчество обязательно для заполнения";
    if (empty($phone)) $errors[] = "Телефон обязателен для заполнения";
    if (empty($email)) $errors[] = "Email обязателен для заполнения";
    if (!empty($login)) {
        $check_login = mysqli_query($db, "SELECT id_user FROM user WHERE username = '$login'");
        if (mysqli_num_rows($check_login) > 0) {
            $errors[] = "Пользователь с таким логином уже существует";
        }
    }
        
    // Если ошибок нет - регистрируем пользователя
    if (empty($errors)) {
        // user_type_id = 1 - обычный пользователь
        $sql = "INSERT INTO user (user_type_id, surname, name, otchestvo, phone, email, username, password) 
                VALUES ('1', '$surname', '$name', '$otchestvo', '$phone', '$email', '$login', MD5('$password'))";
        
        if (mysqli_query($db, $sql)) {
            $success = "Регистрация прошла успешно! Теперь вы можете войти в систему.";
            // Очищаем поля формы после успешной регистрации
            $login = $password = $surname = $name = $otchestvo = $phone = $email = '';
        } else {
            $errors[] = "Ошибка при регистрации: " . mysqli_error($db);
        }
    }

}
?>
<main>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="login">Логин *</label>
        <input type="text" name="login" id="login" value="<?php echo htmlspecialchars($login ?? ''); ?>" required><br>
        <label for="password">Пароль *</label>
        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($password ?? ''); ?>" required><br>
        <label for="surname">Фамилия *</label>
        <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required><br>
        <label for="name">Имя *</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required><br>
        <label for="otchestvo">Отчество *</label>
        <input type="text" name="otchestvo" id="otchestvo" value="<?php echo htmlspecialchars($otchestvo ?? ''); ?>" required><br>
        <label for="phone">Телефон *</label>
        <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required><br>
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="index.php">Войдите здесь</a></p>
</main>