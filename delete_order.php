<?php
require 'pdo_connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    
    // Удалите элементы заказа
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);
    
    // Удалите заказ
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    
    header("Location: profile.php?order_deleted=true");
    exit();
}
?>
