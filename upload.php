<?php
require __DIR__ . '/app.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
check_csrf();
if (!isset($_FILES['media'])) { $_SESSION['notice']='Choose a photo or video first.'; header('Location: index.php'); exit; }
$allowed = [
'image/jpeg'=>['photo','jpg'], 'image/png'=>['photo','png'], 'image/webp'=>['photo','webp'], 'image/gif'=>['photo','gif'],
'video/mp4'=>['video','mp4'], 'video/webm'=>['video','webm'], 'video/quicktime'=>['video','mov']
];
$items = load_json('memories.json', []);
$success = 0; $errors = [];
$count = count($_FILES['media']['name']);
for ($i=0; $i<$count; $i++) {
    if ($_FILES['media']['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
    if ($_FILES['media']['error'][$i] !== UPLOAD_ERR_OK) { $errors[]='A file could not be uploaded. Check the server upload limit.'; continue; }
    if ($_FILES['media']['size'][$i] > MAX_UPLOAD_BYTES) { $errors[]=$_FILES['media']['name'][$i].' is larger than 50 MB.'; continue; }
    $tmp = $_FILES['media']['tmp_name'][$i];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
    if (!isset($allowed[$mime])) { $errors[]='Unsupported file type: '.basename($_FILES['media']['name'][$i]); continue; }
    [$type,$ext] = $allowed[$mime];
    $id = bin2hex(random_bytes(16));
    $filename = $id.'.'.$ext;
    if (!move_uploaded_file($tmp, __DIR__.'/uploads/'.$filename)) { $errors[]='Could not save a file. Check folder permissions.'; continue; }
    $items[] = ['id'=>$id,'filename'=>$filename,'name'=>mb_substr(basename($_FILES['media']['name'][$i]),0,120),'type'=>$type,'created'=>time()];
    $success++;
}
save_json('memories.json', $items);
$_SESSION['notice'] = $success.' file(s) uploaded. ♡'.($errors ? ' '.implode(' ', $errors) : '');
header('Location: index.php'); exit;
