<?php
session_start();

// Remove from active_sessions DB
if (isset($_SESSION['admin']['email'])) {
    require_once __DIR__ . '/../includes/config.php';
    $email = $_SESSION['admin']['email'];
    $stmt = $conn->prepare("DELETE FROM active_sessions WHERE user_email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
    }
}

session_unset();
session_destroy();
$reason = isset($_GET['reason']) ? $_GET['reason'] : '';
$redirectParams = '';

if ($reason === 'timeout') {
    $redirectParams = '?error=Sesi berakhir karena tidak ada aktifitas.';
} elseif ($reason === 'timechange') {
    $redirectParams = '?error=Waktu berganti, silakan login kembali untuk memperbarui tema.';
}

header('Location: /survey-kependudukan/login.html' . $redirectParams);
exit;
