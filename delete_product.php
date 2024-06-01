<?php
require "pdo_connection.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: errorPage.php?msg=Вам необходимо войти в систему.");
}

$userID = $_SESSION['user_id'];
$statusCheck = $conn->prepare("SELECT status_id FROM users WHERE id = ?");
$statusCheck->execute([$userID]);
$status = $statusCheck->fetchColumn();

if ($status != 1) { // 1 - admin
    header("Location: errorPage.php?msg=У вас нет прав доступа к этой странице!");
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productID = $_GET['id'];
    
    $deleteProduct = $conn->prepare("DELETE FROM cards WHERE id = ?");
    if ($deleteProduct->execute([$productID])) {
        header("Location: admin.php"); // Перенаправляем обратно на страницу администратора после успешного удаления
        exit();
    } else {
        echo "Произошла ошибка при удалении товара.";
    }
} else {
    echo "Некорректный идентификатор товара.";
}
?>
