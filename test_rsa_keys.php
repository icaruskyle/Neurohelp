<?php
$privateKeyPath = __DIR__ . '/keys/private.key';
$publicKeyPath = __DIR__ . '/keys/public.key';

// Load keys
$privateKey = file_get_contents($privateKeyPath);
$publicKey = file_get_contents($publicKeyPath);

if (!$privateKey || !$publicKey) {
    die("<div style='color: red; font-family: Segoe UI;'>❌ Failed to load one or both keys.</div>");
}

// Sample message
$message = "This is a secret message!";

// Encrypt using public key
openssl_public_encrypt($message, $encrypted, $publicKey);
$encoded = base64_encode($encrypted);

// Decrypt using private key
openssl_private_decrypt(base64_decode($encoded), $decrypted, $privateKey);
?>

<!DOCTYPE html>
<html>
<head>
  <title>RSA Key Test</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7fb;
      padding: 40px;
      color: #333;
    }

    .rsa-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      max-width: 800px;
      margin: 0 auto;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    h2 {
      color: #4b2aad;
    }

    .section {
      margin-bottom: 25px;
    }

    .label {
      font-weight: bold;
      margin-bottom: 5px;
      display: block;
    }

    .message-box {
      background-color: #f0f0f0;
      border-radius: 8px;
      padding: 10px;
      font-family: monospace;
      word-break: break-all;
    }

    .status {
      font-size: 16px;
      font-weight: bold;
      padding: 10px;
      border-radius: 8px;
    }

    .success {
      color: green;
    }

    .fail {
      color: red;
    }

    .back-btn {
      margin-top: 20px;
    }

    .back-btn a {
      text-decoration: none;
    }

    .back-btn button {
      padding: 10px 20px;
      background-color: #ccc;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .back-btn button:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>
  <div class="rsa-container">
    <h2>🔐 RSA Key Test</h2>

    <div class="section">
      <span class="label">Original Message:</span>
      <div class="message-box"><?= htmlspecialchars($message) ?></div>
    </div>

    <div class="section">
      <span class="label">Encrypted (Base64):</span>
      <div class="message-box"><?= htmlspecialchars($encoded) ?></div>
    </div>

    <div class="section">
      <span class="label">Decrypted Message:</span>
      <div class="message-box"><?= htmlspecialchars($decrypted) ?></div>
    </div>

    <div class="status <?= $message === $decrypted ? 'success' : 'fail' ?>">
      <?= $message === $decrypted
        ? '✅ Success! RSA encryption & decryption are working.'
        : '❌ Failed! Keys are invalid or mismatched.' ?>
    </div>

    <div class="back-btn">
      <a href="../Neurohelp/public/dashboard1.php"><button>⬅ Back to Dashboard</button></a>
    </div>
  </div>
</body>
</html>
