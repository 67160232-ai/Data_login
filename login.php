<?php
// login.php
session_start();

require_once __DIR__ . '/config_mysqli.php'; // ?????????????????? config
require_once __DIR__ . '/csrf.php';          // ?????/???? $_SESSION['csrf_token']

// Generate CSRF token if missing
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helpers
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

// Flash messages
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_errors  = $_SESSION['flash_errors']  ?? [];
$prefill_email = $_SESSION['prefill_email'] ?? '';

// Clear flashes after reading
unset($_SESSION['flash_success'], $_SESSION['flash_errors'], $_SESSION['prefill_email']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sign in</title>
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
      <h3 class="mb-3">Sign in</h3>

      <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success"><?= e($flash_success) ?></div>
      <?php endif; ?>

      <?php if (!empty($flash_errors)): ?>
        <div class="alert alert-danger">
          <div class="fw-bold mb-1">Please fix the following:</div>
          <ul class="mb-0">
            <?php foreach ($flash_errors as $err): ?>
              <li><?= e($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="login_process.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required
                 value="<?= e($prefill_email) ?>" placeholder="name@example.com" autocomplete="email">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required
                 placeholder="Your password" autocomplete="current-password">
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-primary" type="submit">Sign in</button>
        </div>

        <!-- Link / button to Register -->
        <div class="d-grid gap-2 mt-3">
          <a class="btn btn-outline-secondary" href="register.php">Create a new account</a>
        </div>
        <p class="text-center mt-2 mb-0">
          Don’t have an account yet? <a href="register.php">Sign up</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>
