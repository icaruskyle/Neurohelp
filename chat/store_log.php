<?php
require_once '../includes/db.php';

$message = $_POST['message'];
$userId = $_POST['user_id'];
$key = '12345678901234567890123456789012'; // 32-char key
$iv = openssl_random_pseudo_bytes(16);
$encrypted = openssl_encrypt($message, 'AES-256-CBC', $key, 0, $iv);

$stmt = $conn->prepare("INSERT INTO chat_logs (user_id, message, iv) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $userId, $encrypted, base64_encode($iv));
$stmt->execute();
?>
