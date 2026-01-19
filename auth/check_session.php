<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header('Location: /survey-kependudukan/login.html');
    exit;
}

// 5 minutes timeout (300 seconds)
$timeout_duration = 300;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Last request was more than 5 minutes ago
    session_unset();
    session_destroy();
    header('Location: /survey-kependudukan/login.html?error=Sesi telah berakhir. Silakan login kembali.');
    exit;
}

// Update last activity time stamp
$_SESSION['last_activity'] = time();
?>