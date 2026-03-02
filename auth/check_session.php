<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0);
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
    // Clear from DB
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
    header('Location: /survey-kependudukan/login.html?error=Sesi telah berakhir. Silakan login kembali.');
    exit;
}

// Update last activity time stamp
$_SESSION['last_activity'] = time();

// Update DB last_activity to keep session alive there too
if (isset($_SESSION['admin']['email'])) {
    require_once __DIR__ . '/../includes/config.php';
    $updateEmail = $_SESSION['admin']['email'];
    $currentSessionId = session_id();

    // Check if session ID matches
    $stmtCheck = $conn->prepare("SELECT session_id FROM active_sessions WHERE user_email = ?");
    if ($stmtCheck) {
        $stmtCheck->bind_param("s", $updateEmail);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows > 0) {
            $rowCheck = $resCheck->fetch_assoc();
            if ($rowCheck['session_id'] !== $currentSessionId) {
                // Session overridden
                session_unset();
                session_destroy();
                header('Location: /survey-kependudukan/login.html?error=Sesi ini telah digantikan oleh login dari perangkat lain.');
                exit;
            }
        } else {
            // Session was completely removed from DB (e.g. by tab close beacon)
            session_unset();
            session_destroy();
            header('Location: /survey-kependudukan/login.html?error=Sesi telah berakhir.');
            exit;
        }
        $stmtCheck->close();
    }

    $stmtUpd = $conn->prepare("UPDATE active_sessions SET last_activity = NOW() WHERE user_email = ?");
    if ($stmtUpd) {
        $stmtUpd->bind_param("s", $updateEmail);
        $stmtUpd->execute();
        $stmtUpd->close();
    }
}
?>