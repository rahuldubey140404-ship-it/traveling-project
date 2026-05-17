<div class="admin-topbar">
  <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
  <div style="display:flex;align-items:center;gap:1rem;">
    <span style="font-size:0.85rem;color:#6B7B8D;">👤 <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
    <a href="logout.php" class="admin-logout">Logout</a>
  </div>
</div>