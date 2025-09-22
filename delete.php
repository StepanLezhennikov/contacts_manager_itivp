<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID контакта не указан.");
}

$id = intval($_GET['id']);


$stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php"); 
    exit;
} else {
    die("Ошибка при удалении контакта: " . $stmt->error);
}
?>
