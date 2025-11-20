<?php
$pageTitle = 'Список заявок';
require_once "struktura.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

// Получаем заявки пользователя с присоединением справочников для читаемости
$query = "
    SELECT s.id_service, s.address, s.data, s.time, st.name_service, pt.name_pay, stat.name_status
    FROM service s
    JOIN service_type st ON s.service_type_id = st.id_service_type
    JOIN pay_type pt ON s.pay_type_id = pt.id_pay_type
    JOIN status stat ON s.status_id = stat.id_status
    WHERE s.user_id = ?
    ORDER BY s.data DESC, s.time DESC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>
<main>
    <h1>Мои заявки</h1>

    <?php if (empty($zayavki)): ?>
        <p>У вас пока нет заявок.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Адрес</th>
                    <th>Услуга</th>
                    <th>Дата и время</th>
                    <th>Оплата</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($zayavki as $z): ?>
                    <tr>
                        <td><?= htmlspecialchars($z['id_service']) ?></td>
                        <td><?= htmlspecialchars($z['address']) ?></td>
                        <td><?= htmlspecialchars($z['name_service']) ?></td>
                        <td><?= htmlspecialchars($z['data'] . ' ' . $z['time']) ?></td>
                        <td><?= htmlspecialchars($z['name_pay']) ?></td>
                        <td><?= htmlspecialchars($z['name_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="create_zayavka.php">Создать новую заявку</a>
</main>