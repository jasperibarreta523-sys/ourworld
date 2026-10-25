<?php
require __DIR__ . '/app.php';
start_secure_session();
if (is_logged_in()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $entered = (string)($_POST['password'] ?? '');
    if (SITE_PASSWORD !== 'CHANGE-THIS-TO-A-LONG-UNIQUE-PASSWORD' && hash_equals(SITE_PASSWORD, $entered)) {
        session_regenerate_id(true);
        $_SESSION['ourworld_ok'] = true;
        header('Location: index.php'); exit;
    }
    $error = SITE_PASSWORD === 'CHANGE-THIS-TO-A-LONG-UNIQUE-PASSWORD' ? 'Set your own password in config.php before using the site.' : 'That password does not match. Try again.';
}
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>A little place for us ♡</title><link rel="stylesheet" href="style.css"></head>
<body class="login-page"><main class="login-card"><div class="login-heart">♡</div><p class="eyebrow">JUST BETWEEN US</p><h1>A little place for us</h1><p class="login-copy">Our memories, letters, and little pieces of forever.</p>
<?php if ($error): ?><div class="login-error"><?= h($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><label for="password">Enter our secret password</label><input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Our little secret"><button class="primary-button">Open our world ♡</button></form><small>Only people with the password can open this page.</small></main></body></html>