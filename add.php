<?php
require_once 'config.php';

$name = $phone = $address = '';
$favorite = 0;
$success = '';
$error = '';

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
        $stmt = $conn->prepare("INSERT INTO contacts (name, phone, address, favorite) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $phone, $address, $favorite);

        if ($stmt->execute()) {
            $success = "Контакт успешно добавлен!";
            $name = $phone = $address = '';
            $favorite = 0;
        } else {
            $error = "Ошибка при добавлении контакта: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить контакт</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4 text-center">Добавить контакт</h1>

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
            <button type="submit" class="btn btn-success">Добавить контакт</button>
            <a href="index.php" class="btn btn-secondary">Назад к списку</a>
        </div>
    </form>
</div>
</body>
</html>
