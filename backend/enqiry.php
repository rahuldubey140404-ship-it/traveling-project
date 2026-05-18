<?php
// ============================================================
// admin/enquiries.php — View & Manage Enquiries
// ============================================================
require_once 'auth_check.php';
require_once '../config/db.php';

$pageTitle = 'Enquiries';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int) $_POST['enquiry_id'];
    $status = $_POST['status'];
    if (in_array($status, ['pending','confirmed','cancelled'])) {
        $pdo->prepare("UPDATE enquiries SET status=? WHERE id=?")->execute([$status, $id]);
    }
    header('Location: enquiries.php?msg=updated');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM enquiries WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: enquiries.php?msg=deleted');
    exit;
}

$enquiries = $pdo->query("SELECT * FROM enquiries ORDER BY submitted_at DESC")->fetchAll();
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enquiries – Avipro Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="admin-main">
  <?php include 'topbar.php'; ?>

  <?php if ($msg === 'updated'): ?>
    <div class="alert alert-success">✅ Enquiry status updated.</div>
  <?php elseif ($msg === 'deleted'): ?>
    <div class="alert alert-success">🗑 Enquiry deleted.</div>
  <?php endif; ?>

  <div class="data-table">
    <div class="data-table-header">
      <h3>All Enquiries (<?= count($enquiries) ?>)</h3>
    </div>
    <?php if (empty($enquiries)): ?>
      <p style="padding:2rem;text-align:center;color:#6B7B8D;">No enquiries yet.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th><th>Name</th><th>Email</th><th>Phone</th>
          <th>Destination</th><th>Travel Date</th><th>Persons</th>
          <th>Message</th><th>Status</th><th>Submitted</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($enquiries as $i => $e): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($e['full_name']) ?></strong></td>
          <td><?= htmlspecialchars($e['email']) ?></td>
          <td><?= htmlspecialchars($e['phone']) ?></td>
          <td><?= htmlspecialchars($e['destination']) ?></td>
          <td><?= htmlspecialchars($e['travel_date']) ?></td>
          <td><?= htmlspecialchars($e['num_persons']) ?></td>
          <td style="max-width:160px;font-size:0.8rem;color:#6B7B8D;">
            <?= htmlspecialchars(mb_substr($e['message'],0,60)) ?><?= strlen($e['message'])>60?'…':'' ?>
          </td>
          <td>
            <form method="POST" action="" style="display:inline;">
              <input type="hidden" name="enquiry_id" value="<?= $e['id'] ?>">
              <input type="hidden" name="update_status" value="1">
              <select name="status" onchange="this.form.submit()"
                style="font-size:0.78rem;padding:0.25rem 0.5rem;border:1px solid #DDD;border-radius:6px;cursor:pointer;">
                <?php foreach (['pending','confirmed','cancelled'] as $s): ?>
                  <option value="<?= $s ?>" <?= $e['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td style="font-size:0.78rem"><?= date('d M Y', strtotime($e['submitted_at'])) ?><br><?= date('h:i A', strtotime($e['submitted_at'])) ?></td>
          <td>
            <a href="enquiries.php?delete=<?= $e['id'] ?>" class="action-btn del"
               onclick="return confirm('Delete this enquiry?')">Delete</a>
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