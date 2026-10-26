<?php
$title = 'About';
require __DIR__ . '/partials/header.php';
?>
<section class="prose">
    <h1>About me</h1>
  <?php if (file_exists(__DIR__ . '/' . SITE_PHOTO)): ?>
    <img class="profile-photo small" src="<?= e(SITE_PHOTO) ?>" alt="Photo of <?= e(SITE_NAME) ?>" width="160" height="160">
  <?php endif; ?>
  <p>Hi, I'm <?= e(SITE_NAME) ?>, a <?= e(strtolower(SITE_ROLE)) ?>. Replace this paragraph with a short story about who you are, what you build, and what you enjoy working on.</p>

  <h2>Skills</h2>
  <ul class="skills">
    <li>PHP</li>
    <li>MySQL</li>
    <li>HTML &amp; CSS</li>
    <li>JavaScript</li>
    <li>Git</li>
  </ul>

  <h2>Contact</h2>
  <p>The quickest way to reach me is by email at <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.</p>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>