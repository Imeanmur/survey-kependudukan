<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: /survey-kependudukan/dashboard.php');
} else {
    header('Location: /survey-kependudukan/login.html');
}
exit;
