<?php
// ============================================================
// admin/add_package.php — Add / Edit Package
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$editing = false;
$pkg = [
    'id' => '', 'name' => '', 'destination' => '', 'duration' => '',
    'price' => '', 'category' => 'hill', 'badge' => '',
    'description' => '', 'image_url' => '', 'status' => 'active'
];

// Load existing package for edit
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $found = $stmt->fetch();
    if ($found) { $pkg = $found; $editing = true; }
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int)   ($_POST['id']          ?? 0);
    $name     = htmlspecialchars(trim($_POST['name']         ?? ''));
    $dest     = htmlspecialchars(trim($_POST['destination']  ?? ''));
    $dur      = htmlspecialchars(trim($_POST['duration']     ?? ''));
    $price    = floatval($_POST['price']        ?? 0);
    $cat      = $_POST['category']              ?? 'hill';
    $badge    = htmlspecialchars(trim($_POST['badge']        ?? ''));
    $desc     = htmlspecialchars(trim($_POST['description']  ?? ''));
    $img      = htmlspecialchars(trim($_POST['image_url']    ?? ''));
    $status   = $_POST['status']               ?? 'active';

    if (!$name) {
        $error = 'Package name is required.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare("
                UPDATE packages
                SET name=?, destination=?, duration=?, price=?, category=?, badge=?, description=?, image_url=?, status=?
                WHERE id=?
            ");
            $stmt->execute([$name, $dest, $dur, $price, $cat, $badge, $desc, $img, $status, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO packages (name, destination, duration, price, category, badge, description, image_url, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $dest, $dur, $price, $cat, $badge, $desc, $img, $status]);
        }
        header('Location: packages.php?msg=saved');
        exit;
    }
}

$pageTitle = $editing ? 'Edit Package' : 'Add Package';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?> – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="form-card">
    <h3><?= $editing ? '✏ Edit Package' : '➕ Add New Package' ?></h3>
    <form method="POST" action="">
      <input type="hidden" name="id" value="<?= htmlspecialchars($pkg['id']) ?>">
      <div class="form-grid">
        <div class="form-group full">
          <label>Package Name *</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($pkg['name']) ?>" placeholder="e.g. Kashmir 7 Days Deluxe" required>
        </div>
        <div class="form-group">
          <label>Destination</label>
          <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($pkg['destination']) ?>" placeholder="e.g. Kashmir">
        </div>
        <div class="form-group">
          <label>Duration</label>
          <input type="text" name="duration" class="form-control" value="<?= htmlspecialchars($pkg['duration']) ?>" placeholder="e.g. 7 Days / 6 Nights">
        </div>
        <div class="form-group">
          <label>Price (₹)</label>
          <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($pkg['price']) ?>" placeholder="e.g. 18999" min="0" step="0.01">
        </div>
        <div class="form-group">
          <label>Category</label>
          <select name="category" class="form-control">
            <?php foreach (['hill','beach','adventure','heritage','honeymoon','other'] as $cat): ?>
              <option value="<?= $cat ?>" <?= $pkg['category']===$cat?'selected':'' ?>><?= ucfirst($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Badge (optional)</label>
          <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($pkg['badge']) ?>" placeholder="e.g. Best Seller, New, Popular">
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="active"   <?= $pkg['status']==='active'  ?'selected':'' ?>>Active</option>
            <option value="inactive" <?= $pkg['status']==='inactive'?'selected':'' ?>>Inactive</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Image URL</label>
          <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($pkg['image_url']) ?>" placeholder="https://...">
          <?php if ($pkg['image_url']): ?>
            <img src="<?= htmlspecialchars($pkg['image_url']) ?>" alt="" style="margin-top:0.75rem;width:200px;height:120px;object-fit:cover;border-radius:8px;">
          <?php endif; ?>
        </div>
        <div class="form-group full">
          <label>Description</label>
          <textarea name="description" class="form-control" style="height:110px;" placeholder="Brief description of the package..."><?= htmlspecialchars($pkg['description']) ?></textarea>
        </div>
      </div>
      <div style="display:flex;gap:1rem;margin-top:1rem;">
        <button type="submit" class="submit-btn"><?= $editing ? 'Update Package' : 'Add Package' ?></button>
        <a href="packages.php" style="padding:0.8rem 2rem;border:1.5px solid #DDD;border-radius:10px;text-decoration:none;color:#6B7B8D;font-weight:600;font-size:0.95rem;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>