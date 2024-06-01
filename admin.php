<?php 
require "pdo_connection.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: errorPage.php?msg=Вам необходимо войти в систему.");
}

$itemsPerPage = 6;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;
$totalItems = $conn->query("SELECT COUNT(*) FROM cards")->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

$userID = $_SESSION['user_id'];
$statusCheck = $conn->prepare("SELECT status_id FROM users WHERE id = ?");
$statusCheck->execute([$userID]);
$status = $statusCheck->fetchColumn();

if ($status != 1) { // 1 - admin
    header("Location: errorPage.php?msg=У вас нет прав доступа к этой странице!");
}

$categories = $conn->query("SELECT * FROM category");
$products = $conn->query("SELECT cards.id, cards.name, cards.description, cards.price, cards.qty, category.title FROM cards INNER JOIN category on cards.category_id = category.id LIMIT $itemsPerPage OFFSET $offset");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ Панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>

    <?php require "../autoshop/UI/navbar.php";?>

    <div class="container">
        <h2>Добавить новый товар</h2>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Название товара" required class="form-control mb-2">
            <textarea name="product_desc" placeholder="Описание товара" required class="form-control mb-2"></textarea>
            <input type="number" name="product_price" placeholder="Цена товара" required class="form-control mb-2">
            <input type="number" name="product_qty" placeholder="Количество товара" required class="form-control mb-2">
            <input type="file" name="product_image" required class="form-control mb-2">
            <select name="category_id" class="form-control mb-2">
                <?php
                foreach ($categories as $category) {
                    echo '<option value="' . $category["id"] . '">' . $category["title"] . '</option>';
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Добавить товар</button>
        </form>

        <h2 class="mt-5">Список товаров</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($products as $product) {
                    echo '<tr>';
                    echo '<td>' . $product["name"] . '</td>';
                    echo '<td>' . $product["title"] . '</td>';
                    echo '<td class="truncate">' . $product["description"] . '</td>';
                    echo '<td>' . $product["price"] . '</td>';
                    echo '<td>' . $product["qty"] . '</td>';
                    echo '<td>
                            <a href="edit_product.php?id=' . $product["id"] . '" class="btn btn-warning">Редактировать</a>
                            <a href="delete_product.php?id=' . $product["id"] . '" class="btn btn-danger">Удалить</a>
                          </td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php 
            echo '<div class="pagination justify-content-center"><ul class="pagination">';
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link btn-pagination" href="?category=' . '&page=' . ($page - 1) . '">Предыдущая</a></li>';
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . $activeClass . '"><a class="page-link btn-pagination" href="?category=' . '&page=' . $i . '">' . $i . '</a></li>';
            }
            if ($page < $totalPages) {
                echo '<li class="page-item"><a class="page-link btn-pagination" href="?category=' . '&page=' . ($page + 1) . '">Следующая</a></li>';
            }
            echo '</ul></div>';
        ?>
    </div>

    <?php require "../autoshop/UI/footer.php"; ?>

</body>
</html>
