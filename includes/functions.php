<?php
function sendOTP($email, $otp) {
    // Example: Use PHPMailer or mail() here
    mail($email, "Your OTP Code", "Your OTP is: $otp");
}
?>