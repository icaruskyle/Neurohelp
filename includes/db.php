<?php
$config = include(__DIR__ . '/../config/config.php');
$conn = new mysqli(
    $config['db']['host'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['database']
);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>