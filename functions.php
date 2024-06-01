<?php 
function generateRandomString($length) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '#';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateUniqueOrderId($conn, $length) {
    $uniqueId = generateRandomString($length);
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ?");
    $stmt->execute([$uniqueId]);
    
    while ($stmt->fetch()) {
        $uniqueId = generateRandomString($length);
        $stmt->execute([$uniqueId]);
    }

    return $uniqueId;
}