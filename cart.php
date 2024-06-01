<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require "pdo_connection.php";
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title, cart.cart_qty FROM cards INNER JOIN category on cards.category_id = category.id INNER JOIN cart on cards.id = cart.product_id WHERE cart.user_id = ?");
$stmt->execute([$user_id]);

$productsInCart = $stmt->fetchAll();
$totalPrice = 0;
$amountProducts = 0;
$amountPositions = 0;

foreach ($productsInCart as $row) {
    $totalPrice += ($row["price"] * $row["cart_qty"]);
    $amountPositions += $row["cart_qty"];
    $amountProducts += 1;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Корзина</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link href="main.css" rel="stylesheet">
	<link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>
    <?php require "UI/navbar.php";?>
    <div class="container features">
        <div class="row g-5">
            <div class="col-3 custom__category">
                <h3>Корзина</h3>
                Позиций в корзине: <?=$amountProducts?> </br>
                Количество товара в корзине: <?=$amountPositions?> </br>
                Итого: <?=number_format($totalPrice)?> руб.
                <form action="place_order.php" method="post">
                    <?php if ($totalPrice != 0):?>
                        <input type="hidden" name="total_price" value="<?=$totalPrice?>">
                        <?php foreach ($productsInCart as $row): ?>
                            <input type="hidden" name="products[<?=$row["id"]?>][id]" value="<?=$row["id"]?>">
                            <input type="hidden" name="products[<?=$row["id"]?>][qty]" value="<?=$row["cart_qty"]?>">
                            <input type="hidden" name="products[<?=$row["id"]?>][total_price]" value="<?=$row["price"] * $row["cart_qty"]?>">
                        <?php endforeach; ?>
                        <button class="btn btn-custom" type="submit">Оформить заказ</button>
                    <?php endif;?>
                </form>
            </div>
            <div class="col">
                <div class="row d-flex gap-3 justify-content-start">
                <?php 
                foreach ($productsInCart as $row) {
                    echo '
                    <div class="card p-3" style="width: 18rem;">
                        <img src="images/'.$row["image"].'" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">'.$row["name"].'</h5>
                            <p class="card-text">Цена: '.number_format($row["price"]).' руб.</p>
                            <p class="card-text">Категория: '.$row["title"].'</p>
                            <p class="card-text">В наличии: '.$row["qty"].'</p>
                            <div class="d-flex mb-4" w-100>
                                <a class="btn btn-custom px-3 me-2" id="minusProduct" href="add_toCart.php?qty='.max($row["cart_qty"] - 1, 0).'&id='.$row["id"].'">
                                    -
                                </a>
                                <div class="form-outline w-100">
                                    <input id="labelamount" min="1" max="'.$row["qty"].'" name="quantity" value="'.$row["cart_qty"].'" type="number" class="form-control text-center" />
                                </div>
                                <a class="btn btn-custom px-3 ms-2" id="plusProduct" href="add_toCart.php?qty='.min($row["cart_qty"] + 1, $row["qty"]).'&id='.$row["id"].'">
                                    +
                                </a>
                            </div>
                            <a href="more.php?id='.$row["id"].'" class="btn btn-custom w-100">Подробнее</a>
                        </div>
                    </div>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <?php require "UI/footer.php";?>
    <script type="text/javascript" src="js/app.js"></script>
</body>
</html>
