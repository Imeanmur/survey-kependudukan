<?php
session_start();

if (!isset($_SESSION['otp_pending'])) {
    header('Location: /survey-kependudukan/login.html?error=Sesi tidak ditemukan');
    exit;
}

$pending = $_SESSION['otp_pending'];
$email = $pending['email'];

// Generate new OTP
$otp = sprintf('%06d', mt_rand(0, 999999));

// Update session
$_SESSION['otp_pending']['otp'] = $otp;
$_SESSION['otp_pending']['expires'] = time() + 300;

// Send email
$subject = "Kirim Ulang Kode OTP - Survey Kependudukan";
$message = "Kode OTP baru Anda adalah: " . $otp . "\n\nKode ini berlaku selama 5 menit.";
$headers = "From: no-reply@survey-kependudukan.com";

@mail($email, $subject, $message, $headers);

// FOR DEVELOPMENT: Log OTP
file_put_contents(__DIR__ . '/otp_log.txt', date('Y-m-d H:i:s') . " - [RESEND] Email: $email - OTP: $otp" . PHP_EOL, FILE_APPEND);

header('Location: /survey-kependudukan/otp_verify.html?msg=Kode OTP baru telah dikirim');
exit;
