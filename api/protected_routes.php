<?php
$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

if ($token !== 'Bearer your_token_here') {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Proceed with secured API logic
?>
