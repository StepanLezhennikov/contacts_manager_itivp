<?php
require_once 'config.php';

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список контактов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-action {
            background-color: #28a745; 
            border-color: #28a745;
            color: #fff;
        }
        .btn-action:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: #fff;
        }
        .btn-action + .btn-action {
            margin-left: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Список контактов</h1>
    <a href="add.php" class="btn btn-success mb-3">Добавить контакт</a>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Избранный</th>
            <th>Дата создания</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= $row['favorite'] ? 'Да' : 'Нет' ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-action btn-sm">Редактировать</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-action btn-sm"
                           onclick="return confirm('Вы уверены, что хотите удалить контакт?')">Удалить</a>
                           <a href="update_status.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                             <?= $row['favorite'] ? 'Снять с избранного' : 'Отметить избранным' ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">Контактов нет</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
