<?php
require "pdo_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["product_name"];
    $productDesc = $_POST["product_desc"];
    $productPrice = $_POST["product_price"];
    $productQty = $_POST["product_qty"];
    $categoryId = $_POST["category_id"];

    $targetDir = "images/";
    $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Файл не является изображением.";
        $uploadOk = 0;
    }

    if (file_exists($targetFile)) {
        echo "Извините, файл уже существует.";
        $uploadOk = 0;
    }

    if ($_FILES["product_image"]["size"] > 5000000) {
        echo "Извините, ваш файл слишком большой.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Извините, разрешены только JPG, JPEG, PNG & GIF файлы.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Извините, ваш файл не был загружен.";
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO cards (name, description, image, category_id, price, qty) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$productName, $productDesc, basename($_FILES["product_image"]["name"]), $categoryId, $productPrice, $productQty]);
            header("Location: admin.php");
        } else {
            echo "Извините, произошла ошибка при загрузке вашего файла.";
        }
    }
} else {
    echo "Ошибка доступа.";
}
?>
