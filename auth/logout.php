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
header('Location: /survey-kependudukan/login.html');
exit;
