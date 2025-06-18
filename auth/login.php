<?php

require_once '../includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result && password_verify($password, $result['password'])) {
    session_start();
    $_SESSION['user_id'] = $result['id'];
    echo "Login successful.";
} else {
    echo "Invalid credentials.";
}
header("Location: ../public/dashboard.php");
exit;

?>
