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


// Check for active session in other devices
require_once __DIR__ . '/../includes/config.php';
$checkStmt = $conn->prepare("SELECT last_activity FROM active_sessions WHERE user_email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $row = $checkResult->fetch_assoc();
    $lastActivity = strtotime($row['last_activity']);
    // If active within last 30 minutes, block
    if (time() - $lastActivity < 300) {
        header('Location: /survey-kependudukan/login.html?error=Akun sedang digunakan di perangkat lain');
        exit;
    } else {
        // Old stale session, maybe clean it up or valid to override
        // For now, let's allow it but we'll overwrite it in verify_otp
    }
}
$checkStmt->close();

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
