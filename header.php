<?php require_once __DIR__ . '/config.php'; $flash = flash(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title ?? 'Contacts') ?> · Contact Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background: #f4f6fb; }
    .navbar-brand i { color: #7c9cff; }
    .card { border: 0; box-shadow: 0 2px 12px rgba(20, 30, 70, .08); }
    .avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: #e3e9ff; color: #3b52c4; font-weight: 600;
      display: inline-flex; align-items: center; justify-content: center;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="read.php"><i class="bi bi-person-lines-fill me-2"></i>Contact Manager</a>
    <a class="btn btn-primary btn-sm" href="create.php"><i class="bi bi-plus-lg"></i> New Contact</a>
  </div>
</nav>
<main class="container pb-5">
<?php if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
    <?= e($flash['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
