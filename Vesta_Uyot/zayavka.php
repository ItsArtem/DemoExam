<?php
$pageTitle = 'Мои отчеты по практике';
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

// Получаем отчеты пользователя с названием группы
$query = "
    SELECT s.id_service, s.start_data, s.end_data, 
           s.organization_name, s.address_org, s.ruk_org, s.position_ruk,
           s.work_done, s.spec, s.teacher_comment,
           g.group_name,
           stat.name_status, stat.id_status
    FROM service s
    JOIN groups g ON s.group_id = g.id_group
    JOIN status stat ON s.status_id = stat.id_status
    WHERE s.user_id = ?
    ORDER BY s.start_data DESC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Формируем контент страницы
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Мои отчеты по практике</h2>
    <a href="create_zayavka.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Новый отчет
    </a>
</div>

<?php if (empty($zayavki)): ?>
    <div class="alert alert-info">
        <h4 class="alert-heading">У вас пока нет отчетов!</h4>
        <p class="mb-3">Начните с создания первого отчета о прохождении практики.</p>
        <a href="create_zayavka.php" class="btn btn-primary">Создать первый отчет</a>
    </div>
<?php else: ?>
    <div class="row row-cols-1 g-4">
        <?php foreach ($zayavki as $z): ?>
            <?php 
            // Определяем цвет статуса и иконку
            $status_color = '';
            $status_icon = '';
            if ($z['id_status'] == 1) { // На проверка
                $status_color = 'warning';
                $status_icon = 'bi-clock-history';
            } elseif ($z['id_status'] == 2) { // Принято
                $status_color = 'success';
                $status_icon = 'bi-check-circle';
            } elseif ($z['id_status'] == 3) { // На доработку
                $status_color = 'danger';
                $status_icon = 'bi-exclamation-circle';
            }
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-<?php echo $status_color; ?>">
                    <div class="card-header bg-<?php echo $status_color; ?> text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi <?php echo $status_icon; ?> me-2"></i>
                                <strong>Отчет #<?= htmlspecialchars($z['id_service']) ?></strong>
                            </div>
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($z['name_status']) ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Организация:</small>
                                <p class="mb-0 fw-bold"><?= htmlspecialchars($z['organization_name']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Период:</small>
                                <p class="mb-0"><?= htmlspecialchars($z['start_data']) ?> - <?= htmlspecialchars($z['end_data']) ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <small class="text-muted">Группа:</small>
                                <p class="mb-0"><?= htmlspecialchars($z['group_name']) ?></p>
                            </div>
                            <div class="col-md-8">
                                <small class="text-muted">Специальность:</small>
                                <p class="mb-0"><?= htmlspecialchars($z['spec']) ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Руководитель от организации:</small>
                            <p class="mb-0"><?= htmlspecialchars($z['ruk_org']) ?>
                                <?php if (!empty($z['position_ruk'])): ?>
                                    <span class="text-muted">(<?= htmlspecialchars($z['position_ruk']) ?>)</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <?php if ($z['id_status'] == 3 && !empty($z['teacher_comment'])): ?>
                            <div class="alert alert-danger mt-3">
                                <h6 class="alert-heading"><i class="bi bi-chat-left-text"></i> Комментарий преподавателя:</h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($z['teacher_comment'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>