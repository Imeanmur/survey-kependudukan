<?php
session_start();
session_unset();
session_destroy();
header('Location: /survey-kependudukan/login.html');
exit;
