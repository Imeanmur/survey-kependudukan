<?php
session_start();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /survey-kependudukan/login.html');
    exit;
}

// Read users
$users = require __DIR__ . '/../includes/admin_users.php';

$email = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? (string) ($_POST['password']) : '';

if ($email === '' || $password === '') {
    header('Location: /survey-kependudukan/login.html?error=Isi email dan password');
    exit;
}

// Normalize email key
$key = strtolower($email);
if (!isset($users[$key])) {
    // Try exact key if not normalized entry
    $found = null;
    foreach ($users as $k => $v) {
        if (strcasecmp($k, $email) === 0) {
            $found = [$k, $v];
            break;
        }
    }
    if ($found) {
        [$key, $info] = $found;
    } else {
        header('Location: /survey-kependudukan/login.html?error=Email atau password salah');
        exit;
    }
} else {
    $info = $users[$key];
}

if (!password_verify($password, $info['password_hash'])) {
    header('Location: /survey-kependudukan/login.html?error=Email atau password salah');
    exit;
}

// Success: set session
// SUCCESS: Generate OTP and temporary session
$otp = sprintf('%06d', mt_rand(0, 999999));
$_SESSION['otp_pending'] = [
    'email' => $email,
    'info' => $info,
    'otp' => $otp,
    'expires' => time() + 300 // 5 minutes
];

// Send email
$subject = "Kode OTP Login - Survey Kependudukan";
$message = "Kode OTP Anda adalah: " . $otp . "\n\nKode ini berlaku selama 5 menit.";
$headers = "From: no-reply@survey-kependudukan.com";

// Try sending mail, log if fails (or always log for dev environment)
$mailSent = @mail($email, $subject, $message, $headers);

// FOR DEVELOPMENT: Log OTP to file since local mail might not work
file_put_contents(__DIR__ . '/otp_log.txt', date('Y-m-d H:i:s') . " - Email: $email - OTP: $otp" . PHP_EOL, FILE_APPEND);

header('Location: /survey-kependudukan/otp_verify.html');
exit;
