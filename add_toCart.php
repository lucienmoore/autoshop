<?php
require "pdo_connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Пожалуйста, авторизуйтесь для добавления товаров в корзину");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['qty']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $qty = $_GET['qty'];
    
    $stmt = $conn->prepare("SELECT cards.qty FROM cards WHERE cards.id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    
    if ($qty <= $res["qty"]){
        if ($qty > 0) {
            $stmt = $conn->prepare("UPDATE cart SET cart_qty = ? WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$qty, $id, $user_id]);
        }
        else {
            $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
        }
        header("Location: cart.php");
    }
    else {
        header("Location: errorPage.php?msg=Извините, на складе недостаточно выбранного товара");
    }
}
else if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT qty FROM cards WHERE id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    
    if ($res["qty"] > 0) {
        $stmt = $conn->prepare("SELECT cart_qty FROM cart WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        $current_qty = $stmt->fetchColumn();
        
        // Проверка доступности товара на складе перед добавлением еще одного в корзину
        if ($current_qty + 1 <= $res["qty"]) {
            $stmt = $conn->prepare("UPDATE cart SET cart_qty = cart_qty + 1 WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
        }
        else {
            header("Location: errorPage.php?msg=Извините, на складе недостаточно выбранного товара");
            exit();
        }
        
        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO cart (product_id, cart_qty, user_id) VALUES (?, 1, ?)");
            $stmt->execute([$id, $user_id]);
        }
        
        header("Location: cart.php");
    }
    else {
        header("Location: errorPage.php?msg=Извините, выбранный вами товар закончился на складе");
    }
}
?>
