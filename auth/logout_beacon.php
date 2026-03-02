<?php
// auth/logout_beacon.php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0);
    session_start();
}

if (isset($_SESSION['admin']['email'])) {
    require_once __DIR__ . '/../includes/config.php';
    $email = $_SESSION['admin']['email'];
    $currentSessionId = session_id();

    // Only delete from active_sessions if the session_id matches the one ending
    $stmt = $conn->prepare("DELETE FROM active_sessions WHERE user_email = ? AND session_id = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $email, $currentSessionId);
        $stmt->execute();
        $stmt->close();
    }

    // Destroy session
    session_unset();
    session_destroy();
}
// Respond with empty 200 OK
http_response_code(200);
?>