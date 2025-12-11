<?php
$pageTitle = 'Авторизация';
require_once "db/db.php";

$loginError = '';
$login = '';

// Проверка авторизации и редирект
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['user_type_id'] == 2) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: zayavka.php");
        exit();
    }
}

// Обработка формы авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

    // Очистка входных данных
    $login = strip_tags($login);
    $password = strip_tags($password);

    // Сначала проверяем стандартный логин
    $user = find($login, $password);
    
    // Если не нашли, проверяем логин преподавателя
    if (!$user && $login === 'teacher' && $password === 'practice2025') {
        $user = checkTeacherLogin($login, $password);
    }

    if ($user) {
        // Успешная авторизация
        $_SESSION['user'] = $user;

        // Редирект в зависимости от типа пользователя
        if ($user['user_type_id'] == 2) {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: zayavka.php");
            exit();
        }
    } else {
        // Ошибка авторизации
        $loginError = "Неверный логин или пароль.";
    }
}

// Формируем контент страницы
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="text-center mb-4">Вход в систему</h4>
                <form method="post" action="index.php">
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" name="login" id="login" class="form-control" 
                               required value="<?php echo htmlspecialchars($login); ?>" 
                               autocomplete="username">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" name="password" id="password" 
                               class="form-control" required autocomplete="current-password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Вход</button>
                </form>

                <?php if (!empty($loginError)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo htmlspecialchars($loginError); ?>
                    </div>
                <?php endif; ?>

                <div class="mt-3 text-center">
                    <p class="mb-0">Нет аккаунта? <a href="registration.php">Зарегистрируйтесь здесь</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>