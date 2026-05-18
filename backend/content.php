<?php
// ============================================================
// admin/content.php — Edit Site Content
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$pageTitle = 'Site Content';
$msg = '';

// Save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = ['hero_tagline','hero_subtext','about_text','contact_phone','contact_email','contact_address','office_hours'];
    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            $val = htmlspecialchars(trim($_POST[$key]));
            $stmt = $pdo->prepare("INSERT INTO site_content (`key`, value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=?");
            $stmt->execute([$key, $val, $val]);
        }
    }
    $msg = 'saved';
}

// Load current values
$rows = $pdo->query("SELECT `key`, value FROM site_content")->fetchAll(PDO::FETCH_KEY_PAIR);
$get = fn($k, $d='') => htmlspecialchars($rows[$k] ?? $d);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Site Content – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <?php if ($msg === 'saved'): ?>
    <div class="alert alert-success">✅ Site content saved successfully!</div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="form-card" style="margin-bottom:1.5rem;">
      <h3>🏠 Hero Section</h3>
      <div class="form-group">
        <label>Main Tagline</label>
        <input type="text" name="hero_tagline" class="form-control" value="<?= $get('hero_tagline','Discover the World\'s Hidden Wonders') ?>">
      </div>
      <div class="form-group">
        <label>Sub-text</label>
        <textarea name="hero_subtext" class="form-control"><?= $get('hero_subtext','Unforgettable journeys curated for the discerning traveller.') ?></textarea>
      </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
      <h3>🏢 About Section</h3>
      <div class="form-group">
        <label>About Us Text</label>
        <textarea name="about_text" class="form-control" style="height:130px;"><?= $get('about_text','Avipro Travels was founded with a simple belief: every person deserves to experience the world\'s beauty without stress or compromise.') ?></textarea>
      </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
      <h3>📞 Contact Details</h3>
      <div class="form-grid">
        <div class="form-group">
          <label>Phone</label>
          <input type="text" name="contact_phone" class="form-control" value="<?= $get('contact_phone','+91 98765 43210') ?>">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="text" name="contact_email" class="form-control" value="<?= $get('contact_email','info@avipro.com') ?>">
        </div>
        <div class="form-group full">
          <label>Office Address</label>
          <input type="text" name="contact_address" class="form-control" value="<?= $get('contact_address','42 Travel House, Connaught Place, New Delhi – 110001') ?>">
        </div>
        <div class="form-group">
          <label>Office Hours</label>
          <input type="text" name="office_hours" class="form-control" value="<?= $get('office_hours','Mon–Sat 9AM–7PM') ?>">
        </div>
      </div>
    </div>

    <button type="submit" class="submit-btn">💾 Save All Changes</button>
  </form>
</div>
</body>
</html>