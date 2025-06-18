<?php
$keyFile = __DIR__ . '/.encryption_key';

if (!file_exists($keyFile)) {
    $key = bin2hex(random_bytes(16)); // 32 hex characters = 128-bit
    file_put_contents($keyFile, $key);

    echo "<script>alert('✅ A 32-byte encryption key has been generated and saved.');</script>";
    echo "<p>Key generated successfully: <strong>$key</strong></p>";
    exit;
} else {
    $key = trim(file_get_contents($keyFile));
}

return [
    'encryption_key' => $key,
    'cipher' => 'aes-256-cbc'
];