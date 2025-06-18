<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $birthday = $_POST['birthday'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, birthday, mobile, address, email, password, consent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $full_name, $birthday, $mobile, $address, $email, $password, $consent);
        
        if ($stmt->execute()) {
            header("Location: ../public/index.php?registered=1");
            exit;
        } else {
            echo "Registration failed.";
        }
    }
}
?>
