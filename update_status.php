<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID контакта не указан.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT favorite FROM contacts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Контакт не найден.");
}

$row = $result->fetch_assoc();
$new_status = $row['favorite'] ? 0 : 1; 
$stmt->close();

$update_stmt = $conn->prepare("UPDATE contacts SET favorite=? WHERE id=?");
$update_stmt->bind_param("ii", $new_status, $id);

if ($update_stmt->execute()) {
    $update_stmt->close();
    $conn->close();
    header("Location: index.php"); 
    exit;
} else {
    die("Ошибка при обновлении статуса: " . $update_stmt->error);
}
?>
