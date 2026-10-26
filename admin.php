<?php
$title = 'Manage projects';
require __DIR__ . '/config.php';
require_admin();

$error = '';
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $t    = trim($_POST['title'] ?? '');
        $d    = trim($_POST['description'] ?? '');
        $tech = trim($_POST['tech'] ?? '');
        $url  = trim($_POST['url'] ?? '');

        if ($t === '' || $d === '') {
            $error = 'Title and description are required.';
        } elseif ($url !== '' && !preg_match('#^https?://#i', $url)) {
            $error = 'The link must start with http:// or https://';
        } else {
            $stmt = db()->prepare('INSERT INTO projects (title, description, tech, url) VALUES (:t, :d, :tech, :u)');
            $stmt->execute([':t' => $t, ':d' => $d, ':tech' => $tech, ':u' => $url ?: null]);
            $notice = 'Project added.';
        }
    } elseif ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM projects WHERE id = :id');
        $stmt->execute([':id' => (int) ($_POST['id'] ?? 0)]);
        $notice = 'Project deleted.';
    }
}

$projects = db()->query('SELECT id, title, tech FROM projects ORDER BY created_at DESC')->fetchAll();

require __DIR__ . '/partials/header.php';
?>
<section class="narrow">
  <h1>Manage projects</h1>
  <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <?php if ($notice): ?><p class="notice" role="status"><?= e($notice) ?></p><?php endif; ?>

  <form method="post" class="stack">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add">
    <label>Title <input type="text" name="title" maxlength="120" required></label>
    <label>Description <textarea name="description" rows="4" required></textarea></label>
    <label>Technologies <input type="text" name="tech" maxlength="255" placeholder="PHP, MySQL, CSS"></label>
    <label>Link (optional) <input type="url" name="url" maxlength="255" placeholder="https://"></label>
    <button type="submit">Add project</button>
  </form>

  <h2>Your projects</h2>
  <?php if (!$projects): ?>
    <p class="empty">No projects yet. Add your first one above.</p>
  <?php else: ?>
    <ul class="manage-list">
      <?php foreach ($projects as $p): ?>
        <li>
          <span><strong><?= e($p['title']) ?></strong><br><small><?= e($p['tech']) ?></small></span>
          <form method="post" onsubmit="return confirm('Delete this project?');">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
            <button type="submit" class="danger">Delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>