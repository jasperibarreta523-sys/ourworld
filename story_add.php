<?php
require __DIR__ . '/app.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?view=story'); exit; }
check_csrf();
$title = trim((string)($_POST['title'] ?? '')); $date = trim((string)($_POST['date'] ?? '')); $note = trim((string)($_POST['note'] ?? ''));
if ($title === '' || mb_strlen($title) > 90 || mb_strlen($note) > 1200 || ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))) {
    $_SESSION['notice'] = 'Please check the story title, date, and note.'; header('Location: index.php?view=story'); exit;
}
$story = load_json('story.json', []);
$story[] = ['id'=>bin2hex(random_bytes(16)), 'title'=>$title, 'date'=>$date, 'note'=>$note];
save_json('story.json', $story);
$_SESSION['notice'] = 'A new chapter has been added. ♡'; header('Location: index.php?view=story'); exit;
