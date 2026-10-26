<?php
$title = 'Log in';
require __DIR__ . '/config.php';

if (is_logged_in()) {
    header('Location: ' . (is_admin() ? 'admin.php' : 'index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, password_hash, role FROM users WHERE username = :u');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role']    = $user['role'];
        header('Location: ' . ($user['role'] === 'admin' ? 'admin.php' : 'index.php'));
        exit;
    }
    $error = 'Wrong username or password.';
}

require __DIR__ . '/partials/header.php';
?>
<section class="narrow">
  <h1>Log in</h1>
  <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form method="post" class="stack">
    <?= csrf_field() ?>
    <label>Username <input type="text" name="username" required autofocus autocomplete="username"></label>
    <label>Password <input type="password" name="password" required autocomplete="current-password"></label>
    <button type="submit">Log in</button>
  </form>
  <p>No account yet? <a href="register.php">Create one</a>.</p>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>