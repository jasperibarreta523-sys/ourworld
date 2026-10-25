<?php
require __DIR__ . '/app.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?view=story'); exit; }
check_csrf();
$id = $_POST['id'] ?? ''; $story = load_json('story.json', []);
$story = array_values(array_filter($story, fn($item) => ($item['id'] ?? '') !== $id));
save_json('story.json', $story);
$_SESSION['notice'] = 'Story entry removed.'; header('Location: index.php?view=story'); exit;
