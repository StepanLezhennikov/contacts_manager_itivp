<?php
require_once 'config.php';

$error = '';
$success = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID контакта не указан.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Контакт не найден.");
}

$contact = $result->fetch_assoc();
$name = $contact['name'];
$phone = $contact['phone'];
$address = $contact['address'];
$favorite = $contact['favorite'];

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $favorite = isset($_POST['favorite']) ? 1 : 0;

    if (empty($name)) {
        $error = "Поле 'Имя' обязательно для заполнения.";
    } 
    elseif (strlen($name) > 255) {
        $error = "Имя не может быть длиннее 255 символов.";
    } elseif (strlen($phone) > 50) {
        $error = "Телефон не может быть длиннее 50 символов.";
    } elseif (strlen($address) > 1000) {
        $error = "Адрес не может быть длиннее 1000 символов.";
    } else {
        $update_stmt = $conn->prepare("UPDATE contacts SET name=?, phone=?, address=?, favorite=? WHERE id=?");
        $update_stmt->bind_param("sssii", $name, $phone, $address, $favorite, $id);

        if ($update_stmt->execute()) {
            $success = "Контакт успешно обновлён!";
        } else {
            $error = "Ошибка при обновлении контакта: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать контакт</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4 text-center">Редактировать контакт</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Адрес</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="favorite" name="favorite" <?= $favorite ? 'checked' : '' ?>>
            <label class="form-check-label" for="favorite">
                Избранный
            </label>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Сохранить изменения</button>
            <a href="index.php" class="btn btn-secondary">Назад к списку</a>
        </div>
    </form>
</div>
</body>
</html>
