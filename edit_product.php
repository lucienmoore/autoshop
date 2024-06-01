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

if ($status != 1) {
    header("Location: errorPage.php?msg=У вас нет прав доступа к этой странице!");
}

if (isset($_GET['id'])) {
    $productID = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->execute([$productID]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: errorPage.php?msg=Товар не найден!");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['product_name'];
        $desc = $_POST['product_desc'];
        $price = $_POST['product_price'];
        $qty = $_POST['product_qty'];
        $category = $_POST['category_id'];

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../autoshop/images/';
            $imageName = basename($_FILES['product_image']['name']);
            $uploadFilePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFilePath)) {
                $stmt = $conn->prepare("UPDATE cards SET image = ? WHERE id = ?");
                $stmt->execute([$imageName, $productID]);
            } else {
                header("Location: errorPage.php?msg=Ошибка при загрузке изображения.");
            }
        }

        $stmt = $conn->prepare("UPDATE cards SET name = ?, description = ?, price = ?, qty = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$name, $desc, $price, $qty, $category, $productID]);
        
        header("Location: admin.php");
        exit();
    }

} else {
    header("Location: errorPage.php?msg=ID товара не указан.");
}

$categories = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать товар</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>
    <?php require 'UI/navbar.php'?>

    <div class="container">
        <h2>Редактировать товар</h2>
        <form action="edit_product.php?id=<?= $productID ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="product_name" value="<?= $product['name'] ?>" required class="form-control mb-2">
            <textarea name="product_desc" required class="form-control mb-2"><?= $product['description'] ?></textarea>
            <input type="number" name="product_price" value="<?= $product['price'] ?>" required class="form-control mb-2">
            <input type="number" name="product_qty" value="<?= $product['qty'] ?>" required class="form-control mb-2">
            <select name="category_id" class="form-control mb-2">
                <?php
                foreach ($categories as $category) {
                    $selected = ($category["id"] == $product["category_id"]) ? "selected" : "";
                    echo '<option value="' . $category["id"] . '" ' . $selected . '>' . $category["title"] . '</option>';
                }
                ?>
            </select>

            <div class="d-flex flex-column gap-2">
                <label>Текущее изображение:</label>
                <img src="../autoshop/images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="150">
                <label>Заменить изображение:</label>
                <input type="file" id="imageUpload" name="product_image">
                <img id="imagePreview" src="<?= $product['image'] ?>" alt="" style="max-width: 150px; margin-bottom: 10px">
            </div>
            
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>

    <?php require 'UI/footer.php'?>
</body>
</html>

<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').src = '../autoshop/images/<?= $product['image'] ?>'; 
        }
    });
</script>
