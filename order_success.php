<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Заказ успешно оформлен</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link href="main.css" rel="stylesheet">
	<link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>
    <?php require "UI/navbar.php";?>
    <div class="container">
        <h1>Спасибо за ваш заказ!</h1>
        <p>Ваш заказ был успешно оформлен. Мы свяжемся с вами в ближайшее время.</p>
    </div>
    <?php require "UI/footer.php";?>
</body>
</html>
