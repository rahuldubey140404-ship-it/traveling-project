<?php
// ============================================================
// admin/dashboard.php
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$totalPackages  = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$totalEnquiries = $pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
$pendingCount   = $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status='pending'")->fetchColumn();
$recentEnquiries = $pdo->query("SELECT * FROM enquiries ORDER BY submitted_at DESC LIMIT 10")->fetchAll();
$adminName = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <div class="stats-cards">
    <div class="stats-card">
      <div class="s-icon">📦</div>
      <div class="s-num"><?= $totalPackages ?></div>
      <div class="s-label">Total Packages</div>
    </div>
    <div class="stats-card">
      <div class="s-icon">📬</div>
      <div class="s-num"><?= $totalEnquiries ?></div>
      <div class="s-label">Total Enquiries</div>
    </div>
    <div class="stats-card">
      <div class="s-icon">⏳</div>
      <div class="s-num"><?= $pendingCount ?></div>
      <div class="s-label">Pending</div>
    </div>
    <div class="stats-card">
      <div class="s-icon">✅</div>
      <div class="s-num">12k+</div>
      <div class="s-label">Happy Clients</div>
    </div>
  </div>

  <div class="data-table">
    <div class="data-table-header">
      <h3>Recent Enquiries</h3>
      <a href="enquiries.php" class="add-btn">View All</a>
    </div>
    <?php if (empty($recentEnquiries)): ?>
      <p style="padding:2rem;text-align:center;color:#6B7B8D;">No enquiries yet.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr><th>#</th><th>Name</th><th>Email</th><th>Destination</th><th>Date</th><th>Persons</th><th>Status</th><th>Submitted</th></tr>
      </thead>
      <tbody>
        <?php foreach ($recentEnquiries as $i => $e): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($e['full_name']) ?></strong></td>
          <td><?= htmlspecialchars($e['email']) ?></td>
          <td><?= htmlspecialchars($e['destination']) ?></td>
          <td><?= htmlspecialchars($e['travel_date']) ?></td>
          <td><?= htmlspecialchars($e['num_persons']) ?></td>
          <td>
            <span class="badge <?= $e['status']==='confirmed'?'badge-green':($e['status']==='cancelled'?'badge-red':'badge-yellow') ?>">
              <?= ucfirst($e['status']) ?>
            </span>
          </td>
          <td style="font-size:0.78rem"><?= date('d M Y, h:i A', strtotime($e['submitted_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>