<?php
$title = 'Projects';
require __DIR__ . '/config.php';

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
    $stmt = db()->prepare(
        'SELECT * FROM projects
         WHERE title LIKE :q1 OR description LIKE :q2 OR tech LIKE :q3
         ORDER BY created_at DESC'
    );
    $stmt->execute([':q1' => $like, ':q2' => $like, ':q3' => $like]);
} else {
    $stmt = db()->query('SELECT * FROM projects ORDER BY created_at DESC');
}
$projects = $stmt->fetchAll();

require __DIR__ . '/partials/header.php';
?>
<section class="hero">
  <div class="hero-text">
    <h1>I'm <?= e(SITE_NAME) ?>.<br>I build things for the web.</h1>
    <p><?= e(SITE_ROLE) ?> working with PHP and MySQL. Browse my work below or search by project name or technology.</p>
  </div>
  <?php if (file_exists(__DIR__ . '/' . SITE_PHOTO)): ?>
    <img class="profile-photo" src="<?= e(SITE_PHOTO) ?>" alt="Photo of <?= e(SITE_NAME) ?>" width="240" height="240">
  <?php endif; ?>
</section>

<section>
  <form class="search" method="get" action="index.php" role="search">
    <label for="q" class="visually-hidden">Search projects</label>
    <input id="q" type="search" name="q" value="<?= e($q) ?>" placeholder="Search projects, e.g. MySQL">
    <button type="submit">Search</button>
    <?php if ($q !== ''): ?><a class="clear" href="index.php">Clear</a><?php endif; ?>
  </form>

  <?php if ($q !== ''): ?>
    <p class="result-count"><?= count($projects) ?> result<?= count($projects) === 1 ? '' : 's' ?> for &ldquo;<?= e($q) ?>&rdquo;</p>
  <?php endif; ?>

  <?php if (!$projects): ?>
    <p class="empty">No projects match that search. Try a different word, or <a href="index.php">see all projects</a>.</p>
  <?php else: ?>
    <ul class="projects">
      <?php foreach ($projects as $p): ?>
        <li>
          <h2><?= e($p['title']) ?></h2>
          <p><?= e($p['description']) ?></p>
          <p class="tech"><?= e($p['tech']) ?></p>
          <?php if (!empty($p['url'])): ?>
            <a href="<?= e($p['url']) ?>" target="_blank" rel="noopener noreferrer">View project</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>