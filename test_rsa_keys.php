<?php
$privateKeyPath = __DIR__ . '/keys/private.key';
$publicKeyPath = __DIR__ . '/keys/public.key';

// Load keys
$privateKey = file_get_contents($privateKeyPath);
$publicKey = file_get_contents($publicKeyPath);

if (!$privateKey || !$publicKey) {
    die("❌ Failed to load one or both keys.");
}

// Sample message
$message = "This is a secret message!";

// Encrypt using public key
openssl_public_encrypt($message, $encrypted, $publicKey);
$encoded = base64_encode($encrypted);

// Decrypt using private key
openssl_private_decrypt(base64_decode($encoded), $decrypted, $privateKey);

// Output results
echo "<h2>RSA Key Test</h2>";
echo "<strong>Original Message:</strong> $message<br><br>";
echo "<strong>Encrypted (Base64):</strong> $encoded<br><br>";
echo "<strong>Decrypted Message:</strong> $decrypted<br><br>";

// Check match
if ($message === $decrypted) {
    echo "<span style='color:green;'>✅ Success! RSA encryption & decryption are working.</span>";
} else {
    echo "<span style='color:red;'>❌ Failed! Keys are invalid or mismatched.</span>";
}
?>
