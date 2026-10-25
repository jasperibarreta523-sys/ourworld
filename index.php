<?php
require __DIR__ . '/app.php';
start_secure_session();
if (!is_logged_in()) { header('Location: login.php'); exit; }
$memories = load_json('memories.json', []);
$story = load_json('story.json', []);
usort($memories, fn($a,$b) => ($b['created'] ?? 0) <=> ($a['created'] ?? 0));
usort($story, fn($a,$b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$photos = array_values(array_filter($memories, fn($m) => ($m['type'] ?? '') === 'photo'));
$videos = array_values(array_filter($memories, fn($m) => ($m['type'] ?? '') === 'video'));
$view = $_GET['view'] ?? 'home';
if (!in_array($view, ['home','photos','videos','story'], true)) $view = 'home';
$notice = $_SESSION['notice'] ?? ''; unset($_SESSION['notice']);
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function card($m) {
  $id = rawurlencode($m['id']);
  $name = h($m['name'] ?? 'A memory');
  $type = $m['type'] ?? 'photo';
  $src = 'media.php?id=' . $id;
  echo '<article class="memory-card">';
  if ($type === 'video') echo '<video src="'.h($src).'" controls preload="metadata" playsinline></video>';
  else echo '<a href="'.h($src).'" target="_blank" rel="noopener"><img src="'.h($src).'" alt="'.$name.'" loading="lazy"></a>';
  echo '<div class="memory-caption"><span>'.$name.'</span><form method="post" action="delete.php" onsubmit="return confirm(\'Delete this memory?\')"><input type="hidden" name="csrf" value="'.h(csrf_token()).'"><input type="hidden" name="id" value="'.h($m['id']).'"><button class="delete" title="Delete memory" aria-label="Delete memory">×</button></form></div></article>';
}
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Our Little World ♡</title><link rel="stylesheet" href="style.css">
</head><body>
<header class="hero">
  <nav class="topbar"><a class="brand" href="index.php">♡ <span>our little world</span></a>
    <div class="top-links"><a href="index.php">Memories</a><a href="index.php?view=story">Our story</a><a href="logout.php">Lock site ↗</a></div>
  </nav>
  <div class="hero-inner"><span class="tiny-heart">♡ &nbsp; A PRIVATE PLACE FOR US &nbsp; ♡</span>
    <h1><?= h(site_title()) ?></h1><p><?= h(site_message()) ?></p>
    <div class="letter-stamp">sealed with love <span>♥</span></div>
  </div>
</header>
<main class="shell">
  <aside class="sidebar">
    <div class="polaroid"><div class="polaroid-art">♡</div><p>you + me</p><small>my favorite story</small></div>
    <p class="side-heading">OUR LITTLE COLLECTION</p>
    <a class="side-link <?= $view==='home'?'active':'' ?>" href="index.php">♡ <span>All memories</span></a>
    <a class="side-link <?= $view==='photos'?'active':'' ?>" href="index.php?view=photos">▧ <span>Photos</span></a>
    <a class="side-link <?= $view==='videos'?'active':'' ?>" href="index.php?view=videos">▷ <span>Videos</span></a>
    <a class="side-link <?= $view==='story'?'active':'' ?>" href="index.php?view=story">✉ <span>Our story</span></a>
    <div class="side-note">“In a world full of moments, you are my favorite.”</div>
  </aside>
  <section class="main-content">
    <?php if ($notice): ?><div class="notice"><?= h($notice) ?></div><?php endif; ?>
    <?php if ($view === 'story'): ?>
      <div class="heading"><div><p class="eyebrow">THE CHAPTERS OF US</p><h2>Our story, one memory at a time</h2><p class="subheading">Little dates and moments I never want to forget.</p></div></div>
      <div class="story-layout">
        <form class="paper-form" method="post" action="story_add.php">
          <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
          <div class="form-title">Write a new chapter <span>♡</span></div>
          <label>Memory title<input name="title" maxlength="90" required placeholder="The day we first met"></label>
          <label>Date<input name="date" type="date"></label>
          <label>Your note<textarea name="note" rows="4" maxlength="1200" placeholder="What made this day special?"></textarea></label>
          <button class="primary-button">Add to our story ♡</button>
        </form>
        <div class="timeline">
        <?php if (!$story): ?><div class="empty"><span>✉</span><h3>The first page is waiting</h3><p>Add the first special moment from your story.</p></div><?php endif; ?>
        <?php foreach ($story as $item): ?><article class="timeline-item"><span class="timeline-dot">♡</span><div class="date"><?= h(!empty($item['date']) ? date('F j, Y', strtotime($item['date'])) : 'A special day') ?></div><h3><?= h($item['title']) ?></h3><p><?= nl2br(h($item['note'] ?? '')) ?></p><form method="post" action="story_delete.php" onsubmit="return confirm('Remove this story entry?')"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="id" value="<?= h($item['id']) ?>"><button class="subtle-delete">remove entry</button></form></article><?php endforeach; ?>
        </div>
      </div>
    <?php else: ?>
      <div class="heading"><div><p class="eyebrow">A LITTLE TIME CAPSULE</p><h2><?= $view==='photos'?'Our photos':($view==='videos'?'Our videos':'All our favorite memories') ?></h2><p class="subheading">Every picture, every laugh, every little piece of us.</p></div>
      <form class="upload-form" method="post" action="upload.php" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><label class="upload-button">＋ Add memories<input type="file" name="media[]" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime" multiple required></label><small>Photos and videos · max 50 MB each</small><button class="primary-button compact">Upload</button></form></div>
      <?php if (!$memories): ?><div class="empty"><span>♡</span><h3>Your memories belong here</h3><p>Start by uploading a favorite photo or video. This space is just for the two of you.</p></div><?php endif; ?>
      <?php if ($view==='home' || $view==='photos'): ?><div class="section-title"><h3>♡ Photos</h3><span><?= count($photos) ?> memories</span></div><div class="gallery"><?php foreach ($photos as $m) card($m); ?></div><?php endif; ?>
      <?php if ($view==='home' || $view==='videos'): ?><div class="section-title video-title"><h3>▷ Videos</h3><span><?= count($videos) ?> memories</span></div><div class="gallery"><?php foreach ($videos as $m) card($m); ?></div><?php endif; ?>
      <?php if ($view==='photos' && !$photos): ?><p class="empty-inline">No photos yet — upload one above. ♡</p><?php endif; ?>
      <?php if ($view==='videos' && !$videos): ?><p class="empty-inline">No videos yet — upload one above. ♡</p><?php endif; ?>
    <?php endif; ?>
    <footer>Made with love, for our memories. <span>♥</span></footer>
  </section>
</main>
</body></html>