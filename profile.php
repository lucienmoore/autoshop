<?php
require 'pdo_connection.php';
require 'functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$query = "SELECT orders.id AS order_id, order_items.product_id, order_items.quantity, order_items.total_price, orders.order_date, cards.name, cards.price, cards.image
          FROM orders
          INNER JOIN order_items ON orders.id = order_items.order_id
          INNER JOIN cards ON order_items.product_id = cards.id
          WHERE orders.user_id = ?
          ORDER BY orders.order_date";

$stmt = $conn->prepare($query);
$stmt->execute([$userId]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedOrders = [];
foreach ($orders as $order) {
    $orderId = $order['order_id'];
    if (!isset($groupedOrders[$orderId])) {
        $groupedOrders[$orderId] = [
            'order_id' => $orderId,
            'order_date' => $order['order_date'],
            'total_order_price' => 0,
            'items' => [],
        ];
    }
    $groupedOrders[$orderId]['total_order_price'] += $order['total_price'];
    $groupedOrders[$orderId]['items'][] = $order;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>

<?php require "../autoshop/UI/navbar.php";?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex flex-column gap-3">
            <div class="profile-card">
                <div class="card-header">Ваш профиль</div>
                <div class="card-body">
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Имя: <?php echo $_SESSION['username']; ?></label>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Почта: <?php echo $_SESSION['email']; ?></label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="profile-card">
                <div class="card-header">Ваши заказы</div>
                <div class="card-body">
                    <?php if (!empty($groupedOrders)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Заказ</th>
                                <th>Изображение</th>
                                <th>Название товара</th>
                                <th>Количество</th>
                                <th>Цена</th>
                                <th>Сумма заказа</th>
                                <th>Дата заказа</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupedOrders as $groupedOrder) {
                                $rowspan = count($groupedOrder['items']);
                                foreach ($groupedOrder['items'] as $index => $item) {
                                    echo '<tr>';
                                    if ($index == 0) {
                                        echo '<td rowspan="'.$rowspan.'">' . $groupedOrder['order_id'] . '</td>';
                                    }
                                    echo '<td><img src="images/'.$item['image'].'" alt="'.$item['name'].'" width="100" class="img-thumbnail"></td>';
                                    echo '<td>' . $item['name'] . '</td>';
                                    echo '<td>' . $item['quantity'] . '</td>';
                                    echo '<td>' . number_format($item['total_price']) . ' руб.</td>';
                                    if ($index == 0) {
                                        echo '<td rowspan="'.$rowspan.'">' . number_format($groupedOrder['total_order_price']) . ' руб.</td>';
                                        echo '<td rowspan="'.$rowspan.'">' . date('d.m.Y', strtotime($groupedOrder['order_date'])) . '</td>';
                                        echo '<td rowspan="'.$rowspan.'">
                                        <form action="delete_order.php" method="post">
                                            <input type="hidden" name="order_id" value="'.$groupedOrder['order_id'].'">
                                            <button type="submit" class="btn btn-sm btn-danger">Отменить</button>
                                        </form>
                                    </td>';
                                    }
                                    echo '</tr>';
                                }
                            } ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>У вас нет заказов.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "../autoshop/UI/footer.php"; ?>

</body>
</html>
