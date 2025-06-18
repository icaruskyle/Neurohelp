<?php
session_start();
$user_otp = $_POST['otp'];

if ($_SESSION['otp'] == $user_otp) {
    echo "2FA success.";
} else {
    echo "Invalid OTP.";
}
?>
