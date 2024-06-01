<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";
require "pdo_connection.php";
require "functions.php"; 

$user_id = $_SESSION['user_id'];

do {
    $uniqueOrderId = generateUniqueOrderId($conn, $length = 6);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE id = ?");
    $stmt->execute([$uniqueOrderId]);
    $isUnique = ($stmt->fetchColumn() == 0);
} while (!$isUnique);

$stmt = $conn->prepare("INSERT INTO orders (id, user_id, total_price, order_date) VALUES (?, ?, ?, NOW())");
$stmt->execute([$uniqueOrderId, $user_id, $_POST['total_price']]);

foreach ($_POST['products'] as $product) {
    $stmt = $conn->prepare("UPDATE cards SET qty = qty - ? WHERE id = ?");
    $stmt->execute([$product['qty'], $product['id']]);
    
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$uniqueOrderId, $product['id'], $product['qty'], $product['total_price']]);
}

$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_email = $stmt->fetchColumn();

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;                                     
    $mail->isSMTP();                
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;   
    $mail->Username   = 'vladislavh616@gmail.com';
    $mail->Password   = 'yqjc byex knsi jhtq';
    $mail->SMTPSecure = 'tls'; 
    $mail->Port       = 587;

    $mail->setFrom('autoshop@example.com', 'AutoShop');
    $mail->addAddress($user_email);      
    $mail->addAttachment('images/mail.jpg', 'congratulations.jpg');                      

    $mail->isHTML(true);          
    $mail->CharSet = 'UTF-8';                        
    $mail->Subject = 'Ваш заказ оформлен!';

    $mail->Body = '
    <html>
        <body>
            <p>Уважаемый клиент,</p>
            <p>Ваш заказ с номером ' . $uniqueOrderId . ' успешно оформлен. Спасибо за покупку!</p>
            <p><a href="http://localhost/autoshop/profile.php">Просмотреть заказ</a></p>
        </body>
    </html>
    ';
    $mail->AltBody = 'Уважаемый клиент, Ваш заказ с номером ' . $uniqueOrderId . ' успешно оформлен. Спасибо за покупку!';

    $mail->send();
} catch (Exception $e) {
    echo "Сообщение не может быть отправлено. Ошибка отправителя: {$mail->ErrorInfo}";
}

header("Location: order_success.php");
exit();
?>
