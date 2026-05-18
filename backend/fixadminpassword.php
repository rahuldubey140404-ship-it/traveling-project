<?php
// ============================================================
// fix_admin_password.php
// Run this ONCE in your browser after importing the database:
//   http://localhost/avipro-travels/fix_admin_password.php
// Then DELETE this file immediately after use.
// ============================================================
require_once 'config/db.php';

$plainPassword = 'admin123';
$hash = password_hash($plainPassword, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
$stmt->execute([$hash]);

echo "<h2 style='font-family:sans-serif;color:green'>✅ Admin password updated successfully!</h2>";
echo "<p style='font-family:sans-serif'>Username: <strong>admin</strong></p>";
echo "<p style='font-family:sans-serif'>Password: <strong>admin123</strong></p>";
echo "<p style='font-family:sans-serif;color:red'><strong>⚠ Delete this file now!</strong> Then go to <a href='admin/login.php'>admin/login.php</a></p>";