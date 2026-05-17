<?php
// ============================================================
// admin/packages.php — Manage Packages (List + Delete)
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$pageTitle = 'Manage Packages';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM packages WHERE id = ?")->execute([$id]);
    header('Location: packages.php?msg=deleted');
    exit;
}

$packages = $pdo->query("SELECT * FROM packages ORDER BY created_at DESC")->fetchAll();
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Packages – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <?php if ($msg === 'saved'): ?>
    <div class="alert alert-success">✅ Package saved successfully!</div>
  <?php elseif ($msg === 'deleted'): ?>
    <div class="alert alert-success">🗑 Package deleted.</div>
  <?php endif; ?>

  <div class="data-table">
    <div class="data-table-header">
      <h3>All Packages (<?= count($packages) ?>)</h3>
      <a href="add_package.php" class="add-btn">+ Add Package</a>
    </div>
    <?php if (empty($packages)): ?>
      <p style="padding:2rem;text-align:center;color:#6B7B8D;">No packages found. <a href="add_package.php">Add one</a>.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th><th>Image</th><th>Package Name</th><th>Destination</th>
          <th>Duration</th><th>Price</th><th>Category</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($packages as $i => $p): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td>
            <?php if ($p['image_url']): ?>
              <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">
            <?php else: ?>—<?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
          <td><?= htmlspecialchars($p['destination']) ?></td>
          <td><?= htmlspecialchars($p['duration']) ?></td>
          <td>₹<?= number_format($p['price'],2) ?></td>
          <td style="text-transform:capitalize"><?= htmlspecialchars($p['category']) ?></td>
          <td>
            <span class="badge <?= $p['status']==='active' ? 'badge-green' : 'badge-red' ?>">
              <?= ucfirst($p['status']) ?>
            </span>
          </td>
          <td>
            <a href="add_package.php?id=<?= $p['id'] ?>" class="action-btn">Edit</a>
            <a href="packages.php?delete=<?= $p['id'] ?>" class="action-btn del"
               onclick="return confirm('Delete this package?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>