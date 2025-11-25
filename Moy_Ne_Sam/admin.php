<?php
require_once "db/db.php";

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: index.php");
    exit();
}

$pageTitle = "Панель администратора";
$message = "";

// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $service_id = (int)$_POST['service_id'];
    $new_status = (int)$_POST['status_id'];
    
    $update_query = "UPDATE service SET status_id = '$new_status' WHERE id_service = '$service_id'";
    if (mysqli_query($db, $update_query)) {
        $message = "Статус заявки успешно изменен!";
    } else {
        $message = "Ошибка при изменении статуса: " . mysqli_error($db);
    }
}

// Получаем все заявки
$services_query = "SELECT s.*, u.surname, u.name, u.otchestvo, st.name_service, p.name_pay, ss.name_status 
                   FROM service s 
                   LEFT JOIN user u ON s.user_id = u.id_user 
                   LEFT JOIN service_type st ON s.service_type_id = st.id_service_type 
                   LEFT JOIN pay_type p ON s.pay_type_id = p.id_pay_type 
                   LEFT JOIN status ss ON s.status_id = ss.id_status 
                   ORDER BY s.data DESC, s.time DESC";
$services_result = mysqli_query($db, $services_query);

// Получаем все статусы
$statuses_query = mysqli_query($db, "SELECT * FROM status");
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_query)) {
    $statuses[$row['id_status']] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора - Мой не сам</title>
    <style>
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        select, button { padding: 5px; margin: 2px; }
    </style>
</head>
<body>
    <h1>Панель администратора</h1>
    
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h2>Все заявки</h2>
    
    <?php if ($services_result && mysqli_num_rows($services_result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Адрес</th>
                    <th>Услуга</th>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>Тип оплаты</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
                <tr>
                    <td><?php echo $service['id_service']; ?></td>
                    <td><?php echo htmlspecialchars($service['surname'] . ' ' . $service['name'] . ' ' . $service['otchestvo']); ?></td>
                    <td><?php echo htmlspecialchars($service['address']); ?></td>
                    <td><?php echo htmlspecialchars($service['name_service']); ?></td>
                    <td><?php echo htmlspecialchars($service['data']); ?></td>
                    <td><?php echo htmlspecialchars($service['time']); ?></td>
                    <td><?php echo htmlspecialchars($service['name_pay']); ?></td>
                    <td><?php echo htmlspecialchars($service['name_status']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="service_id" value="<?php echo $service['id_service']; ?>">
                            <select name="status_id" required>
                                <option value="">Выберите статус</option>
                                <?php foreach ($statuses as $id => $status): ?>
                                    <option value="<?php echo $id; ?>" <?php echo $id == $service['status_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($status['name_status']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="change_status">Изменить</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Заявок нет.</p>
    <?php endif; ?>

    <p><a href="zayavka.php">Вернуться к списку заявок</a></p>
</body>
</html>