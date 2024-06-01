<?php

require 'pdo_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Пароли отличаются!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, status_id) VALUES (?, ?, ?, 2)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $message = "Успешная регистрация!";
                header('Location: login.php');
            } else {
                $message = "Что-то пошло не так!";
            }
        } else {
            $message = "Пользователь или указанная почта уже существует!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link rel="stylesheet" href="../autoshop/css/main.css">
</head>
<body>

<?php require "../autoshop/UI/navbar.php"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="register-card">
                <div class="card-header">Регистрация</div>
                <div class="card-body">
                    <form action="register.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Имя пользователя:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Повторите пароль:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-custom">Зарегистрироваться</button>
                    </form>
                    <div style="margin-top: 10px;"><?php echo $message; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "../autoshop/UI/footer.php"; ?>

</body>
</html>
