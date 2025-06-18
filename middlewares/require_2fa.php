<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Allow user to verify once per session
if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
    // Save current full URL so we can redirect after verification
    $_SESSION['next_url'] = $_SERVER['REQUEST_URI'];

    // Redirect to OTP verification
    header("Location: /NeuroHelp/public/2fa_verify.php");
    exit;
}