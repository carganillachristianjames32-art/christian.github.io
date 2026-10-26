<?php
$title = 'Register';
require __DIR__ . '/config.php';

if (is_logged_in()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
        $error = 'Username must be 3 to 50 characters: letters, numbers or underscores.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'The two passwords do not match.';
    } else {
        $check = db()->prepare('SELECT id FROM users WHERE username = :u');
        $check->execute([':u' => $username]);
        if ($check->fetch()) {
            $error = 'That username is taken. Choose another one.';
        } else {
            $stmt = db()->prepare("INSERT INTO users (username, password_hash, role) VALUES (:u, :h, 'member')");
            $stmt->execute([':u' => $username, ':h' => password_hash($password, PASSWORD_DEFAULT)]);

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) db()->lastInsertId();
            $_SESSION['role']    = 'member';
            header('Location: index.php');
            exit;
        }
    }
}

require __DIR__ . '/partials/header.php';
?>
<section class="narrow">
  <h1>Create an account</h1>
  <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form method="post" class="stack">
    <?= csrf_field() ?>
    <label>Username <input type="text" name="username" required minlength="3" maxlength="50" autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>"></label>
    <label>Password (8+ characters) <input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
    <label>Confirm password <input type="password" name="confirm" required minlength="8" autocomplete="new-password"></label>
    <button type="submit">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Log in</a>.</p>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>