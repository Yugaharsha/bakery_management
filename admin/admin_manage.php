<?php
include '../db.php';
include '../auth_check.php';

// Read session
$actor_id   = $_SESSION['admin_id'];
$actor_name = $_SESSION['admin_username'];
$actor_role = $_SESSION['admin_role'] ?? 'employee';

// Flash helper
function flash($type, $msg) {
    $_SESSION['FLASH'][$type][] = $msg;
}

// Handle Add Admin (main only)
if (isset($_POST['add_admin'])) {
    if ($actor_role !== 'main') {
        flash('error', 'Unauthorized: only main admin can add admins.');
        header("Location: admin_manage.php"); exit;
    }
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'employee';

    if ($username === '' || $password === '' || !in_array($role, ['main','employee'], true)) {
        flash('error', 'Invalid input.');
    } else {
        // unique username check
        $check = $conn->prepare("SELECT id FROM admin WHERE username=? LIMIT 1");
        $check->bind_param("s", $username);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        if ($exists) {
            flash('error', 'Username already exists.');
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO admin (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hash, $role);
            if ($stmt->execute()) {
                flash('success', "Added admin: $username ($role)");
            } else {
                flash('error', 'DB error while adding admin.');
            }
        }
    }
    header("Location: admin_manage.php"); exit;
}

// Handle Change Password
if (isset($_POST['change_password'])) {
    $target_id = (int)($_POST['target_id'] ?? 0);
    $new_pass  = $_POST['new_password'] ?? '';

    if ($new_pass === '') {
        flash('error','Password cannot be empty.');
        header("Location: admin_manage.php"); exit;
    }

    // If employee, can change only own password
    if ($actor_role !== 'main') {
        $target_id = $actor_id;
    }

    $hash = password_hash($new_pass, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE admin SET password=? WHERE id=?");
    $stmt->bind_param("si", $hash, $target_id);
    if ($stmt->execute()) {
        $label = ($target_id === $actor_id) ? 'your own password' : "password of admin ID $target_id";
        flash('success', "Changed $label.");
    } else {
        flash('error','DB error while changing password.');
    }
    header("Location: admin_manage.php"); exit;
}

// Handle Delete Admin (main only) — only employees can be deleted
if (isset($_GET['delete'])) {
    if ($actor_role !== 'main') {
        flash('error', 'Unauthorized: only main admin can delete admins.');
        header("Location: admin_manage.php"); exit;
    }
    $del_id = (int)$_GET['delete'];
    if ($del_id === $actor_id) {
        flash('error', 'You cannot delete yourself.');
        header("Location: admin_manage.php"); exit;
    }

    $q = $conn->prepare("SELECT id, username, role FROM admin WHERE id=? LIMIT 1");
    $q->bind_param("i", $del_id);
    $q->execute();
    $target = $q->get_result()->fetch_assoc();

    if (!$target) {
        flash('error','Admin not found.');
    } elseif ($target['role'] !== 'employee') {
        flash('error','You can only delete employee admins.');
    } else {
        $stmt = $conn->prepare("DELETE FROM admin WHERE id=?");
        $stmt->bind_param("i", $del_id);
        if ($stmt->execute()) {
            flash('success', "Deleted admin: {$target['username']}");
        } else {
            flash('error','DB error while deleting admin.');
        }
    }
    header("Location: admin_manage.php"); exit;
}

// Fetch admins for display
$all_admin = $conn->query("SELECT id, username, role FROM admin ORDER BY role DESC, username ASC");

// For password change dropdown
if ($actor_role === 'main') {
    $pw_admin = $conn->query("SELECT id, username, role FROM admin ORDER BY role DESC, username ASC");
} else {
    $pw_admin = $conn->query("SELECT id, username, role FROM admin WHERE id={$actor_id}");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Admins</title>
  <link rel="stylesheet" href="admin_style.css">
  <style>
    .admin-card, .form-card { background:#fff; border-radius:12px; padding:16px; margin-bottom:16px; box-shadow:0 6px 16px rgba(0,0,0,.06); }
    .grid { display:grid; gap:16px; }
    .grid-2 { grid-template-columns: 1fr 1fr; }
    .muted { color:#666; font-size:14px; }
    .badge { padding:3px 8px; border-radius:10px; font-size:12px; }
    .badge-main { background:#ffe3b3; }
    .badge-emp  { background:#e6f4ff; }
    .actions a { text-decoration:none; }
    .flash { margin-bottom:10px; padding:10px; border-radius:8px; }
    .flash-success { background:#e7f7ea; color:#256b2b; }
    .flash-error { background:#ffeaea; color:#a12424; }
    .btn { padding:8px 12px; border:none; border-radius:8px; cursor:pointer; }
    .btn-primary { background:#6f42c1; color:#fff; }
    .btn-danger { background:#dc3545; color:#fff; }
    input, select { padding:8px; border-radius:8px; border:1px solid #ddd; width:100%; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:10px; border-bottom:1px solid #eee; text-align:left; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>🍰 Bakery Admin</h2>
    <ul>
      <li><a href="admin_dashboard.php">📊 Dashboard</a></li>
      <li><a href="manage_products.php">🧁 Manage Products</a></li>
      <li><a href="orders.php">🛒 Orders</a></li>
      <li><a href="report.php">📈 Reports</a></li>
      <li><a href="customer.php">👥 Customers</a></li>
      <li><a href="admin_manage.php">🧑‍🍳 Manage Admins</a></li>
      <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
  </div>

  <div class="main">
    <h1>Manage Admins</h1>
    <p class="muted">Logged in as: <strong><?= htmlspecialchars($actor_name) ?></strong> (<?= htmlspecialchars($actor_role) ?>)</p>

    <?php if (!empty($_SESSION['FLASH'])): ?>
      <?php foreach ($_SESSION['FLASH'] as $type => $msgs): ?>
        <?php foreach ($msgs as $m): ?>
          <div class="flash flash-<?= $type === 'error' ? 'error' : 'success' ?>">
            <?= htmlspecialchars($m) ?>
          </div>
        <?php endforeach; ?>
      <?php endforeach; unset($_SESSION['FLASH']); ?>
    <?php endif; ?>

    <div class="grid grid-2">
      <?php if ($actor_role === 'main'): ?>
      <div class="form-card">
        <h2>Add New Admin</h2>
        <form method="post">
          <label>Username</label>
          <input type="text" name="username" required>
          <label>Password</label>
          <input type="password" name="password" required>
          <label>Role</label>
          <select name="role">
            <option value="employee">Employee</option>
            <option value="main">Main</option>
          </select>
          <br><br>
          <button class="btn btn-primary" type="submit" name="add_admin">Add Admin</button>
        </form>
      </div>
      <?php endif; ?>

      <div class="form-card">
        <h2>Change Password</h2>
        <form method="post">
          <?php if ($actor_role === 'main'): ?>
            <label>Choose Admin</label>
            <select name="target_id" required>
              <?php while ($row = $pw_admin->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                  <?= htmlspecialchars($row['username']) ?> (<?= $row['role'] ?>)
                </option>
              <?php endwhile; ?>
            </select>
          <?php else:
              $row = $pw_admin->fetch_assoc(); ?>
              <input type="hidden" name="target_id" value="<?= $row['id'] ?>">
              <p class="muted">You can only change your own password: <strong><?= htmlspecialchars($row['username']) ?></strong></p>
          <?php endif; ?>
          <label>New Password</label>
          <input type="password" name="new_password" required>
          <br><br>
          <button class="btn btn-primary" type="submit" name="change_password">Update Password</button>
        </form>
      </div>
    </div>

    <?php if ($actor_role === 'main'): ?>
    <div class="admin-card">
      <h2>Admins</h2>
      <table>
        <thead>
          <tr><th>Username</th><th>Role</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php while ($a = $all_admin->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($a['username']) ?></td>
              <td>
                <?php if ($a['role'] === 'main'): ?>
                  <span class="badge badge-main">main</span>
                <?php else: ?>
                  <span class="badge badge-emp">employee</span>
                <?php endif; ?>
              </td>
              <td class="actions">
                <?php if ((int)$a['id'] !== $actor_id && $a['role'] === 'employee'): ?>
                  <a class="btn btn-danger" href="?delete=<?= $a['id'] ?>" onclick="return confirm('Delete this admin?')">Delete</a>
                <?php else: ?>
                  <span class="muted">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

  </div>
</body>
</html>
