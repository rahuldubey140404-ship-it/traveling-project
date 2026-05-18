<?php
// ============================================================
// admin/login.php — Admin Authentication Page
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in → go to dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $user['id'];
            $_SESSION['admin_name']      = $user['full_name'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login – Avipro Travels</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DM Sans',sans-serif; background:linear-gradient(135deg,#0A1628,#1E3A5F); min-height:100vh; display:flex; align-items:center; justify-content:center; }
.card { background:#fff; border-radius:24px; padding:3rem; width:100%; max-width:400px; box-shadow:0 24px 60px rgba(0,0,0,0.25); text-align:center; }
.icon { font-size:2.5rem; margin-bottom:0.5rem; }
h2 { font-family:'Playfair Display',serif; font-size:1.8rem; color:#0A1628; margin-bottom:0.3rem; }
.sub { color:#6B7B8D; font-size:0.88rem; margin-bottom:2rem; }
.group { text-align:left; margin-bottom:1.1rem; }
label { display:block; font-size:0.82rem; font-weight:600; color:#2C2C2C; margin-bottom:0.35rem; }
input { width:100%; padding:0.75rem 1rem; border:1.5px solid #E5E5E5; border-radius:10px; font-family:'DM Sans',sans-serif; font-size:0.92rem; outline:none; transition:border-color 0.2s; }
input:focus { border-color:#C9A84C; box-shadow:0 0 0 3px rgba(201,168,76,0.15); }
.hint { font-size:0.75rem; color:#6B7B8D; margin-top:0.3rem; }
.error { background:#FFF5F5; color:#E53E3E; border:1px solid #FED7D7; padding:0.75rem 1rem; border-radius:8px; font-size:0.88rem; margin-bottom:1rem; }
.btn { width:100%; background:#0A1628; color:#fff; padding:0.9rem; border:none; border-radius:12px; font-family:'DM Sans',sans-serif; font-size:1rem; font-weight:700; cursor:pointer; transition:all 0.2s; margin-top:0.5rem; }
.btn:hover { background:#C9A84C; color:#0A1628; }
</style>
</head>
<body>
<div class="card">
  <div class="icon">🔐</div>
  <h2>Admin Login</h2>
  <p class="sub">Avipro Travels CMS</p>
  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" action="">
    <div class="group">
      <label>Username</label>
      <input type="text" name="username" placeholder="admin" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
    </div>
    <div class="group">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <div class="hint">Demo: admin / admin123</div>
    </div>
    <button type="submit" class="btn">Login to Dashboard</button>
  </form>
</div>
</body>
</html>