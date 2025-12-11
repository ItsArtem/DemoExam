<?php
$pageTitle = "Панель преподавателя";
require_once "db/db.php";

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: index.php");
    exit();
}

// Обработка изменения статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $service_id = (int)$_POST['service_id'];
    $new_status = (int)$_POST['status_id'];
    $teacher_comment = mysqli_real_escape_string($db, $_POST['teacher_comment'] ?? '');
    
    $update_query = "UPDATE service SET status_id = '$new_status', teacher_comment = '$teacher_comment' WHERE id_service = '$service_id'";
    if (mysqli_query($db, $update_query)) {
        $message = "Статус отчета успешно изменен!";
    } else {
        $message = "Ошибка при изменении статуса: " . mysqli_error($db);
    }
}

// Получаем все отчеты с полной информацией
$services_query = "SELECT s.*,
                          u.surname, u.name, u.otchestvo, u.email, u.studbilet,
                          g.group_name,
                          st.name_status, st.id_status
                   FROM service s 
                   JOIN user u ON s.user_id = u.id_user 
                   JOIN groups g ON s.group_id = g.id_group
                   JOIN status st ON s.status_id = st.id_status 
                   ORDER BY s.start_data DESC";
$services_result = mysqli_query($db, $services_query);

// Получаем все статусы
$statuses_query = mysqli_query($db, "SELECT * FROM status");
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_query)) {
    $statuses[$row['id_status']] = $row;
}

ob_start();
?>

<?php if (isset($message)): ?>
    <div class="alert <?php echo strpos($message, 'успешно') !== false ? 'alert-success' : 'alert-danger'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Все отчеты студентов</h2>
    <div class="badge bg-info text-dark fs-6">
        <i class="bi bi-people-fill"></i> Преподавательская панель
    </div>
</div>

<?php if ($services_result && mysqli_num_rows($services_result) > 0): ?>
    <div class="row row-cols-1 row-cols-lg-2 g-4">
        <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
            <?php 
            // Определяем цвет статуса
            $status_color = '';
            $status_icon = '';
            if ($service['id_status'] == 1) { // На проверке
                $status_color = 'warning';
                $status_icon = 'bi-clock-history';
            } elseif ($service['id_status'] == 2) { // Принято
                $status_color = 'success';
                $status_icon = 'bi-check-circle';
            } elseif ($service['id_status'] == 3) { // На доработку
                $status_color = 'danger';
                $status_icon = 'bi-exclamation-circle';
            }
            ?>
            <div class="col">
                <div class="card h-100 shadow-lg border-<?php echo $status_color; ?>">
                    <div class="card-header bg-<?php echo $status_color; ?> text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi <?php echo $status_icon; ?> me-2"></i>
                                <strong>Отчет #<?= $service['id_service'] ?></strong>
                            </div>
                            <span class="badge bg-light text-dark fs-6">
                                <?= htmlspecialchars($service['name_status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Информация о студенте -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 text-primary">
                                <i class="bi bi-person-circle"></i> Данные студента
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <small class="text-muted">ФИО:</small>
                                        <p class="mb-0 fw-bold">
                                            <?= htmlspecialchars($service['surname'] . ' ' . $service['name'] . ' ' . $service['otchestvo']) ?>
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Группа:</small>
                                        <p class="mb-0">
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($service['group_name']) ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Email:</small>
                                        <p class="mb-0">
                                            <i class="bi bi-envelope"></i> 
                                            <?= htmlspecialchars($service['email']) ?>
                                        </p>
                                    </div>
                                    <?php if (!empty($service['studbilet'])): ?>
                                    <div class="mb-2">
                                        <small class="text-muted">Студ. билет:</small>
                                        <p class="mb-0">
                                            <i class="bi bi-card-text"></i> 
                                            <?= htmlspecialchars($service['studbilet']) ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Информация о практике -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 text-primary">
                                <i class="bi bi-building"></i> Место практики
                            </h5>
                            <div class="mb-2">
                                <small class="text-muted">Организация:</small>
                                <p class="mb-0 fw-bold"><?= htmlspecialchars($service['organization_name']) ?></p>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Адрес организации:</small>
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt"></i> 
                                    <?= htmlspecialchars($service['address_org']) ?>
                                </p>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Руководитель от организации:</small>
                                <p class="mb-0">
                                    <i class="bi bi-person-badge"></i> 
                                    <?= htmlspecialchars($service['ruk_org']) ?>
                                    <?php if (!empty($service['position_ruk'])): ?>
                                        <span class="text-muted">(<?= htmlspecialchars($service['position_ruk']) ?>)</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Детали практики -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 text-primary">
                                <i class="bi bi-calendar-event"></i> Детали практики
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Специальность:</small>
                                        <p class="mb-0"><?= htmlspecialchars($service['spec']) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Период:</small>
                                        <p class="mb-0">
                                            <i class="bi bi-calendar-range"></i> 
                                            <?= htmlspecialchars($service['start_data']) ?> - <?= htmlspecialchars($service['end_data']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Выполненные работы -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 text-primary">
                                <i class="bi bi-list-task"></i> Выполненные работы
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <?= nl2br(htmlspecialchars($service['work_done'])) ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($service['teacher_comment'])): ?>
                            <div class="mb-4 alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="bi bi-chat-left-text"></i> Ваш комментарий:
                                </h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($service['teacher_comment'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Форма изменения статуса -->
                        <form method="POST" class="border-top pt-3">
                            <input type="hidden" name="service_id" value="<?= $service['id_service'] ?>">
                            
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-gear"></i> Управление отчетом
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Изменить статус:</label>
                                    <select name="status_id" class="form-select" required>
                                        <?php foreach ($statuses as $id => $status): ?>
                                            <option value="<?= $id ?>" <?= $id == $service['id_status'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($status['name_status']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Комментарий для студента:</label>
                                <textarea name="teacher_comment" class="form-control" rows="3" 
                                          placeholder="Укажите, что нужно исправить или комментарий по отчету...">
                                    <?= htmlspecialchars($service['teacher_comment'] ?? '') ?>
                                </textarea>
                                <div class="form-text">
                                    Комментарий будет виден студенту при статусе "На доработку"
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" name="change_status" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer text-muted">
                        <small>
                            <i class="bi bi-clock"></i> Создан: <?= htmlspecialchars($service['start_data']) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="bi bi-info-circle fs-2"></i>
            </div>
            <div>
                <h4 class="alert-heading">Отчетов пока нет</h4>
                <p class="mb-0">Студенты еще не отправляли отчеты по практике. Как только студенты начнут отправлять отчеты, они появятся здесь.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Вернуться на главную
    </a>
</div>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>