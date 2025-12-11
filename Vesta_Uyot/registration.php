<?php
$pageTitle = 'Регистрация';
require_once "db/db.php";

$errors = [];
$success = '';
$fields = ['surname','name','otchestvo','group_id','studbilet','email','login','password'];
$labels = ['Фамилия','Имя','Отчество','Группа','Номер студенческого билета','Email','Логин','Пароль'];

foreach($fields as $field) $$field = '';

// Получаем список групп
$groups = getAllGroups($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach($fields as $field) $$field = trim($_POST[$field] ?? '');
    
    // Проверка обязательных полей (кроме studbilet)
    $required_fields = ['surname','name','otchestvo','group_id','email','login','password'];
    foreach($required_fields as $field) {
        if (empty($$field)) $errors[] = "Заполните " . $labels[array_search($field, $fields)];
    }
    
    // Валидация email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email адрес";
    }
    
    if (!empty($login)) {
        $check = mysqli_query($db, "SELECT id_user FROM user WHERE username = '$login'");
        if (mysqli_num_rows($check) > 0) $errors[] = "Логин уже занят";
    }
        
    if (empty($errors)) {
        // studbilet может быть NULL если пустой
        $studbilet_value = empty($studbilet) ? 'NULL' : "'" . mysqli_real_escape_string($db, $studbilet) . "'";
        
        $sql = "INSERT INTO user (user_type_id, surname, name, otchestvo, group_id, studbilet, email, username, password) 
                VALUES (1, 
                        '" . mysqli_real_escape_string($db, $surname) . "', 
                        '" . mysqli_real_escape_string($db, $name) . "', 
                        '" . mysqli_real_escape_string($db, $otchestvo) . "', 
                        '$group_id', 
                        $studbilet_value, 
                        '" . mysqli_real_escape_string($db, $email) . "', 
                        '" . mysqli_real_escape_string($db, $login) . "', 
                        MD5('" . mysqli_real_escape_string($db, $password) . "'))";
        
        if (mysqli_query($db, $sql)) {
            $success = "Регистрация успешна! <a href='index.php'>Войдите в систему</a>";
            foreach($fields as $field) $$field = '';
        } else {
            $errors[] = "Ошибка: " . mysqli_error($db);
        }
    }
}

ob_start();
?>
<div class="col-md-8 col-lg-6 mx-auto">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success mb-3"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <?php foreach($fields as $i => $field): ?>
        <div class="mb-3">
            <label class="form-label">
                <?= $labels[$i] ?> 
                <?php if($field != 'studbilet'): ?>*<?php endif; ?>
            </label>
            <?php if($field == 'password'): ?>
                <input type="password" name="<?= $field ?>" class="form-control" 
                       value="<?= htmlspecialchars($$field) ?>" <?php if($field != 'studbilet') echo 'required'; ?>>
            <?php elseif($field == 'email'): ?>
                <input type="email" name="<?= $field ?>" class="form-control" 
                       value="<?= htmlspecialchars($$field) ?>" <?php if($field != 'studbilet') echo 'required'; ?>>
            <?php elseif($field == 'group_id'): ?>
                <select name="group_id" class="form-select" required>
                    <option value="">-- Выберите группу --</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id_group'] ?>" 
                            <?= (isset($_POST['group_id']) && $_POST['group_id'] == $group['id_group']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group['group_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" name="<?= $field ?>" class="form-control" 
                       value="<?= htmlspecialchars($$field) ?>" <?php if($field != 'studbilet') echo 'required'; ?>>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary w-100 py-2">Зарегистрироваться</button>
    </form>
    
    <p class="text-center mt-3">
        <small>Есть аккаунт? <a href="index.php">Войдите</a></small>
    </p>
</div>
<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>