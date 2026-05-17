<?php
// ============================================================
// admin/banners.php — Manage Hero Banners
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$pageTitle = 'Banners';

// Add banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_banner'])) {
    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $url   = htmlspecialchars(trim($_POST['image_url'] ?? ''));
    $order = (int)($_POST['sort_order'] ?? 0);
    if ($url) {
        $pdo->prepare("INSERT INTO banners (title, image_url, sort_order, is_active) VALUES (?,?,?,1)")
            ->execute([$title, $url, $order]);
    }
    header('Location: banners.php?msg=added');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $pdo->prepare("UPDATE banners SET is_active = 1 - is_active WHERE id=?")->execute([$id]);
    header('Location: banners.php?msg=toggled');
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM banners WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: banners.php?msg=deleted');
    exit;
}

$banners = $pdo->query("SELECT * FROM banners ORDER BY sort_order ASC")->fetchAll();
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Banners – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <?php if ($msg): ?>
    <div class="alert alert-success">✅ Banner <?= htmlspecialchars($msg) ?>.</div>
  <?php endif; ?>

  <!-- Add Banner Form -->
  <div class="form-card" style="margin-bottom:2rem;">
    <h3>➕ Add New Banner</h3>
    <form method="POST" action="">
      <div class="form-grid">
        <div class="form-group">
          <label>Banner Title</label>
          <input type="text" name="title" class="form-control" placeholder="e.g. Kashmir Adventure">
        </div>
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" class="form-control" value="<?= count($banners)+1 ?>" min="1">
        </div>
        <div class="form-group full">
          <label>Image URL *</label>
          <input type="url" name="image_url" class="form-control" placeholder="https://...">
        </div>
      </div>
      <button type="submit" name="add_banner" class="submit-btn" style="margin-top:1rem;">Add Banner</button>
    </form>
  </div>

  <!-- Current Banners -->
  <div class="data-table">
    <div class="data-table-header">
      <h3>Current Banners (<?= count($banners) ?>)</h3>
    </div>
    <?php if (empty($banners)): ?>
      <p style="padding:2rem;text-align:center;color:#6B7B8D;">No banners yet.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr><th>#</th><th>Preview</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($banners as $i => $b): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><img src="<?= htmlspecialchars($b['image_url']) ?>" alt="" style="width:100px;height:55px;object-fit:cover;border-radius:6px;"></td>
          <td><?= htmlspecialchars($b['title'] ?: '—') ?></td>
          <td><?= $b['sort_order'] ?></td>
          <td>
            <span class="badge <?= $b['is_active'] ? 'badge-green' : 'badge-red' ?>">
              <?= $b['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td>
            <a href="banners.php?toggle=<?= $b['id'] ?>" class="action-btn">
              <?= $b['is_active'] ? 'Deactivate' : 'Activate' ?>
            </a>
            <a href="banners.php?delete=<?= $b['id'] ?>" class="action-btn del"
               onclick="return confirm('Delete this banner?')">Delete</a>
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