<?php
// register.php
session_start();

require_once __DIR__ . '/config_mysqli.php';
require_once __DIR__ . '/csrf.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helpers
function e($str){ return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8"); }

// Flash old values and messages
$old     = $_SESSION['old'] ?? [];
$errors  = $_SESSION['flash_errors'] ?? [];
$success = $_SESSION['flash_success'] ?? "";

// Clear flash after reading
unset($_SESSION['old'], $_SESSION['flash_errors'], $_SESSION['flash_success']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Optional: Bootstrap for quick styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{ background:#f7f8fa; }
    .card{ max-width:520px; margin:48px auto; border-radius:16px; }
  </style>
</head>
<body>
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h3 class="mb-3">Create an account</h3>

      <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
      <?php endif; ?>

      <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
          <div class="fw-bold mb-1">Please fix the following:</div>
          <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
              <li><?= e($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="register_process.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
          <label class="form-label">Full name</label>
          <input type="text" name="full_name" class="form-control" required
                 value="<?= e($old['full_name'] ?? '') ?>" placeholder="e.g., John Smith">
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required
                 value="<?= e($old['email'] ?? '') ?>" placeholder="name@example.com">
        </div>

        <div class="mb-3">
          <label class="form-label">Username (optional)</label>
          <input type="text" name="username" class="form-control"
                 value="<?= e($old['username'] ?? '') ?>" placeholder="e.g., thewanat">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required minlength="8"
                 placeholder="At least 8 characters">
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm password</label>
          <input type="password" name="password_confirm" class="form-control" required minlength="8"
                 placeholder="Re-enter your password">
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-primary" type="submit">Sign up</button>
          <a class="btn btn-outline-secondary" href="login.php">Back to sign in</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
