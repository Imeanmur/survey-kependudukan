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
$password = isset($_POST['password']) ? (string)($_POST['password']) : '';

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
        if (strcasecmp($k, $email) === 0) { $found = [$k, $v]; break; }
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
$_SESSION['admin'] = [
    'email' => $email,
    'name' => $info['name'] ?? 'Admin',
    'role' => $info['role'] ?? 'Administrator',
    'login_time' => time(),
];

header('Location: /survey-kependudukan/dashboard.php');
exit;
