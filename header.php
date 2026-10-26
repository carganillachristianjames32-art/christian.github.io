<?php require_once __DIR__ . '/../config.php'; $page = basename($_SERVER['SCRIPT_NAME']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? SITE_NAME) ?> | <?= e(SITE_NAME) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="index.php"><?= e(SITE_NAME) ?></a>
  <nav>
    <a href="index.php" <?= $page === 'index.php' ? 'aria-current="page"' : '' ?>>Projects</a>
    <a href="about.php" <?= $page === 'about.php' ? 'aria-current="page"' : '' ?>>About</a>
    <?php if (is_logged_in()): ?>
      <a href="admin.php" <?= $page === 'admin.php' ? 'aria-current="page"' : '' ?>>Manage</a>
      <a href="logout.php">Log out</a>
    <?php else: ?>
      <a href="login.php" <?= $page === 'login.php' ? 'aria-current="page"' : '' ?>>Log in</a>
    <?php endif; ?>
  </nav>
</header>
<main>