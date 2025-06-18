<?php
require_once '../includes/db.php';

$userId = $_POST['user_id'];
$key = '12345678901234567890123456789012';

$stmt = $conn->prepare("SELECT message, iv FROM chat_logs WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $decrypted = openssl_decrypt($row['message'], 'AES-256-CBC', $key, 0, base64_decode($row['iv']));
    echo $decrypted . "<br>";
}
?>
