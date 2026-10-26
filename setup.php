<?php
$title = 'Setup';
require __DIR__ . '/config.php';

$count = (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count > 0) {
    exit('An account already exists. Delete setup.php from your server.');
}

$error = '';
$done = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u === '' || strlen($p) < 8) {
        $error = 'Enter a username and a password of at least 8 characters.';
    } else {
        $stmt = db()->prepare("INSERT INTO users (username, password_hash, role) VALUES (:u, :h, 'admin')");
        $stmt->execute([':u' => $u, ':h' => password_hash($p, PASSWORD_DEFAULT)]);
        $done = true;
    }
}

require __DIR__ . '/partials/header.php';
?>
<section class="narrow">
  <h1>Create your admin account</h1>
  <?php if ($done): ?>
    <p class="notice">Account created. Delete <code>setup.php</code> now, then <a href="login.php">log in</a>.</p>
  <?php else: ?>
    <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="stack">
      <?= csrf_field() ?>
      <label>Username <input type="text" name="username" required></label>
      <label>Password (8+ characters) <input type="password" name="password" minlength="8" required></label>
      <button type="submit">Create account</button>
    </form>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>