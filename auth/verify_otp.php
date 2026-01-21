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

    // Register Active Session in DB
    require_once __DIR__ . '/../includes/config.php'; // Ensure config is loaded

    // Clean up old session for this user first (or update)
    $sessionId = session_id();
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $ipVal = $_SERVER['REMOTE_ADDR'];
    $now = date('Y-m-d H:i:s');

    // Insert or Update (ON DUPLICATE KEY UPDATE)
    $stmtSess = $conn->prepare("INSERT INTO active_sessions (user_email, session_id, ip_address, user_agent, last_activity) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE session_id = VALUES(session_id), ip_address = VALUES(ip_address), user_agent = VALUES(user_agent), last_activity = VALUES(last_activity)");

    if ($stmtSess) {
        $stmtSess->bind_param("sssss", $email, $sessionId, $ipVal, $userAgent, $now);
        $stmtSess->execute();
        $stmtSess->close();
    }

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
