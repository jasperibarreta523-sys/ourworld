<?php
require __DIR__ . '/app.php';
require_login();
$id = $_GET['id'] ?? '';
if (!safe_id($id)) { http_response_code(404); exit('Not found'); }
$items = load_json('memories.json', []);
$item = null;
foreach ($items as $m) if (($m['id'] ?? '') === $id) { $item = $m; break; }
if (!$item) { http_response_code(404); exit('Not found'); }
$path = private_file_path($item['filename'] ?? '');
if (!$path) { http_response_code(404); exit('Not found'); }
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = ['jpg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp','gif'=>'image/gif','mp4'=>'video/mp4','webm'=>'video/webm','mov'=>'video/quicktime'];
header('Content-Type: '.($types[$ext] ?? 'application/octet-stream'));
header('Content-Length: '.filesize($path));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($path);
exit;
