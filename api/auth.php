<?php
// Example only – should use a library for production
$token = bin2hex(random_bytes(16));
echo json_encode(["token" => $token]);
?>