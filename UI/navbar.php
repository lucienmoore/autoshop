<?php 
require "pdo_connection.php";

$userStatus = null;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT status_id FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $userStatus = $stmt->fetchColumn();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">AutoShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Корзина</a>
                </li>
                <?php 
                if ($userStatus == 1) {
                    echo '<li class="nav-item"><a class="nav-link" href="admin.php">Админ-панель</a></li>';
                }
                ?>
            </ul>
            <div>
            </div>
            <?php 
            if (!isset($_SESSION['user_id'])) {
                echo '<a href="login.php" class="nav-link" style="color:#9FA0A2">Вход</a>
                <a href="register.php" class="nav-link" style="color:#9FA0A2">Регистрация</a>';}
            else {
                echo '<a href="profile.php" class="nav-link" style="color:#9FA0A2">' . $_SESSION["username"] . '</a>
                <a href="exit.php" class="nav-link" style="color:#9FA0A2">Выйти</a>';
                }
            ?>
        </div>
    </div>
</nav>
