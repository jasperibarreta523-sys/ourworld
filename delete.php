<?php
require __DIR__ . '/app.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
check_csrf();
$id = $_POST['id'] ?? '';
$items = load_json('memories.json', []);
$kept = []; $removed = false;
foreach ($items as $item) {
    if (($item['id'] ?? '') === $id && safe_id($id)) {
        $path = private_file_path($item['filename'] ?? '');
        if ($path) unlink($path);
        $removed = true;
    } else $kept[] = $item;
}
save_json('memories.json', $kept);
$_SESSION['notice'] = $removed ? 'Memory deleted.' : 'Memory was not found.';
header('Location: index.php'); exit;
