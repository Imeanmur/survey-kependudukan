<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /survey-kependudukan/login.html');
    exit;
}

if (!isset($_SESSION['otp_pending'])) {
    header('Location: /survey-kependudukan/login.html?error=Sesi kadaluarsa, silakan login ulang');
    exit;
}

$inputOtp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
$pending = $_SESSION['otp_pending'];

// Check expiration
if (time() > $pending['expires']) {
    unset($_SESSION['otp_pending']);
    header('Location: /survey-kependudukan/login.html?error=Kode OTP kadaluarsa');
    exit;
}

// Verify OTP
if ($inputOtp === $pending['otp']) {
    // Correct OTP: Promote to full admin session
    $info = $pending['info'];
    $email = $pending['email'];

    $_SESSION['admin'] = [
        'email' => $email,
        'name' => $info['name'] ?? 'Admin',
        'role' => $info['role'] ?? 'Administrator',
        'login_time' => time(),
    ];

    // Set last activity for timeout
    $_SESSION['last_activity'] = time();

    // Audit Log
    require_once __DIR__ . '/../includes/config.php';

    date_default_timezone_set('Asia/Jakarta');
    $loginTime = date('Y-m-d H:i:s');
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $userName = $info['name'] ?? 'Admin';

    $stmt = $conn->prepare("INSERT INTO audit_logs (user_email, user_name, login_time, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $email, $userName, $loginTime, $ipAddress, $userAgent);
        $stmt->execute();
        $stmt->close();
    }

    // Clear pending
    unset($_SESSION['otp_pending']);

    header('Location: /survey-kependudukan/dashboard.php');
    exit;
} else {
    header('Location: /survey-kependudukan/otp_verify.html?error=Kode OTP salah');
    exit;
}
