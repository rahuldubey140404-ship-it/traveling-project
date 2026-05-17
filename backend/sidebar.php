<div class="admin-sidebar">
  <div class="admin-logo">Avipro <span>Admin</span></div>
  <a class="admin-nav-item <?= basename($_SERVER['PHP_SELF'])==='dashboard.php'?'active':'' ?>" href="dashboard.php">
    <span>📊</span> Dashboard
  </a>
  <a class="admin-nav-item <?= basename($_SERVER['PHP_SELF'])==='packages.php'?'active':'' ?>" href="packages.php">
    <span>🗺</span> Manage Packages
  </a>
  <a class="admin-nav-item <?= basename($_SERVER['PHP_SELF'])==='enquiries.php'?'active':'' ?>" href="enquiries.php">
    <span>📋</span> Enquiries
  </a>
  <a class="admin-nav-item <?= basename($_SERVER['PHP_SELF'])==='content.php'?'active':'' ?>" href="content.php">
    <span>✏</span> Site Content
  </a>
  <a class="admin-nav-item <?= basename($_SERVER['PHP_SELF'])==='banners.php'?'active':'' ?>" href="banners.php">
    <span>🖼</span> Banners
  </a>
  <a class="admin-nav-item" href="logout.php" style="margin-top:2rem;">
    <span>🚪</span> Logout
  </a>
</div>