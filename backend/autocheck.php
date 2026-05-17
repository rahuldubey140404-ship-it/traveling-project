<?php
// ============================================================
// admin/auth_check.php — Session Guard
// Include this at the very top of every admin page
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}