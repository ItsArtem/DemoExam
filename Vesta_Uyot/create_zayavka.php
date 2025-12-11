<?php
$pageTitle = "Подача отчета по практике";
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$error = "";
$success = "";

// Получаем список групп
$groups = getAllGroups($db);

// Получаем данные пользователя
$user_query = mysqli_query($db, "SELECT surname, name, otchestvo, group_id FROM user WHERE id_user = '{$user['id_user']}'");
$user_data = mysqli_fetch_assoc($user_query);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $surname = mysqli_real_escape_string($db, $_POST['surname'] ?? $user_data['surname']);
    $name = mysqli_real_escape_string($db, $_POST['name'] ?? $user_data['name']);
    $otchestvo = mysqli_real_escape_string($db, $_POST['otchestvo'] ?? $user_data['otchestvo']);
    $group_id = mysqli_real_escape_string($db, $_POST['group_id'] ?? $user_data['group_id']);
    $spec = mysqli_real_escape_string($db, $_POST['spec'] ?? '');
    $start_data = mysqli_real_escape_string($db, $_POST['start_data'] ?? '');
    $end_data = mysqli_real_escape_string($db, $_POST['end_data'] ?? '');
    $organization_name = mysqli_real_escape_string($db, $_POST['organization_name'] ?? '');
    $address_org = mysqli_real_escape_string($db, $_POST['address_org'] ?? '');
    $ruk_org = mysqli_real_escape_string($db, $_POST['ruk_org'] ?? '');
    $position_ruk = mysqli_real_escape_string($db, $_POST['position_ruk'] ?? '');
    $work_done = mysqli_real_escape_string($db, $_POST['work_done'] ?? '');
    
    // Валидация обязательных полей
    $required_fields = [
        'surname', 'name', 'otchestvo', 'group_id', 'spec',
        'start_data', 'end_data', 'organization_name',
        'address_org', 'ruk_org', 'work_done'
    ];
    
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (empty($$field)) {
            $field_names = [
                'surname' => 'Фамилия',
                'name' => 'Имя',
                'otchestvo' => 'Отчество',
                'group_id' => 'Группа',
                'spec' => 'Специальность',
                'start_data' => 'Дата начала',
                'end_data' => 'Дата окончания',
                'organization_name' => 'Название организации',
                'address_org' => 'Адрес организации',
                'ruk_org' => 'ФИО руководителя',
                'work_done' => 'Описание работ'
            ];
            $missing_fields[] = $field_names[$field];
        }
    }
    
    if (!empty($missing_fields)) {
        $error = "Заполните обязательные поля: " . implode(', ', $missing_fields);
    } else {
        $query = "INSERT INTO service (
            user_id, group_id, spec, start_data, end_data, 
            organization_name, address_org, ruk_org, position_ruk, 
            work_done, status_id
        ) VALUES (
            '{$user['id_user']}', 
            '$group_id', 
            '$spec', 
            '$start_data', 
            '$end_data', 
            '$organization_name', 
            '$address_org', 
            '$ruk_org', 
            " . (!empty($position_ruk) ? "'$position_ruk'" : "NULL") . ",
            '$work_done', 
            1
        )";
        
        if (mysqli_query($db, $query)) {
            $success = "Отчет успешно отправлен на проверку!";
            // Очищаем поля после успешного сохранения
            $_POST = [];
        } else {
            $error = "Ошибка при отправке отчета: " . mysqli_error($db);
        }
    }
}

ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
                <div class="mt-2">
                    <a href="zayavka.php" class="btn btn-sm btn-primary">Посмотреть все отчеты</a>
                    <a href="create_zayavka.php" class="btn btn-sm btn-outline-primary">Создать новый отчет</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Форма отчета по практике</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <h5 class="mb-3">1. Данные студента</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="surname" class="form-label">Фамилия *</label>
                            <input type="text" id="surname" name="surname" class="form-control" 
                                   required value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : htmlspecialchars($user_data['surname'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Имя *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($user_data['name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="otchestvo" class="form-label">Отчество *</label>
                            <input type="text" id="otchestvo" name="otchestvo" class="form-control" 
                                   required value="<?php echo isset($_POST['otchestvo']) ? htmlspecialchars($_POST['otchestvo']) : htmlspecialchars($user_data['otchestvo'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="group_id" class="form-label">Группа *</label>
                            <select id="group_id" name="group_id" class="form-select" required>
                                <option value="">-- Выберите группу --</option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?= $group['id_group'] ?>" 
                                        <?= (isset($_POST['group_id']) && $_POST['group_id'] == $group['id_group']) || (!isset($_POST['group_id']) && isset($user_data['group_id']) && $user_data['group_id'] == $group['id_group']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($group['group_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="spec" class="form-label">Специальность *</label>
                            <input type="text" id="spec" name="spec" class="form-control" 
                                   required value="<?php echo isset($_POST['spec']) ? htmlspecialchars($_POST['spec']) : ''; ?>" 
                                   placeholder="Например: Информационные системы и программирование">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_data" class="form-label">Дата начала практики *</label>
                            <input type="date" id="start_data" name="start_data" class="form-control" 
                                   required value="<?php echo isset($_POST['start_data']) ? htmlspecialchars($_POST['start_data']) : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="end_data" class="form-label">Дата окончания практики *</label>
                            <input type="date" id="end_data" name="end_data" class="form-control" 
                                   required value="<?php echo isset($_POST['end_data']) ? htmlspecialchars($_POST['end_data']) : ''; ?>">
                        </div>
                    </div>
                    
                    <h5 class="mb-3">2. Данные о месте прохождения практики</h5>
                    <div class="mb-3">
                        <label for="organization_name" class="form-label">Название организации *</label>
                        <input type="text" id="organization_name" name="organization_name" class="form-control" 
                               required value="<?php echo isset($_POST['organization_name']) ? htmlspecialchars($_POST['organization_name']) : ''; ?>" 
                               placeholder="Например: ООО 'Веста-Уют'">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_org" class="form-label">Адрес организации *</label>
                        <input type="text" id="address_org" name="address_org" class="form-control" 
                               required value="<?php echo isset($_POST['address_org']) ? htmlspecialchars($_POST['address_org']) : ''; ?>" 
                               placeholder="Например: г. Дзержинский, ул. Ленина, д. 1">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="ruk_org" class="form-label">ФИО руководителя от организации *</label>
                            <input type="text" id="ruk_org" name="ruk_org" class="form-control" 
                                   required value="<?php echo isset($_POST['ruk_org']) ? htmlspecialchars($_POST['ruk_org']) : ''; ?>" 
                                   placeholder="Например: Иванов Иван Иванович">
                        </div>
                        <div class="col-md-4">
                            <label for="position_ruk" class="form-label">Должность (необязательно)</label>
                            <input type="text" id="position_ruk" name="position_ruk" class="form-control" 
                                   value="<?php echo isset($_POST['position_ruk']) ? htmlspecialchars($_POST['position_ruk']) : ''; ?>" 
                                   placeholder="Например: Директор">
                        </div>
                    </div>
                    
                    <h5 class="mb-3">3. Содержание работы</h5>
                    <div class="mb-4">
                        <label for="work_done" class="form-label">Краткое описание выполненных работ *</label>
                        <textarea id="work_done" name="work_done" class="form-control" rows="5" 
                                  required placeholder="Опишите работы, которые вы выполняли во время практики..."><?php echo isset($_POST['work_done']) ? htmlspecialchars($_POST['work_done']) : ''; ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send-check"></i> Отправить отчет на проверку
                        </button>
                        <a href="zayavka.php" class="btn btn-outline-secondary">Вернуться к списку отчетов</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>