<?php
// Change the password and couple text in config.php before publishing.
require_once __DIR__ . '/config.php';
function start_secure_session() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params(['httponly'=>true, 'secure'=>$secure, 'samesite'=>'Lax']);
        session_start();
    }
}
function is_logged_in() { start_secure_session(); return !empty($_SESSION['ourworld_ok']); }
function require_login() { if (!is_logged_in()) { header('Location: login.php'); exit; } }
function csrf_token() { start_secure_session(); if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function check_csrf() { start_secure_session(); if (!isset($_POST['csrf'], $_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) { http_response_code(403); exit('Request could not be verified. Go back and try again.'); } }
function load_json($file, $fallback) { $path = __DIR__ . '/data/' . $file; if (!is_file($path)) return $fallback; $data = json_decode(file_get_contents($path), true); return is_array($data) ? $data : $fallback; }
function save_json($file, $data) { $path = __DIR__ . '/data/' . $file; $temp = $path . '.tmp'; file_put_contents($temp, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX); rename($temp, $path); }
function site_title() { return SITE_TITLE; }
function site_message() { return SITE_MESSAGE; }
function safe_id($id) { return is_string($id) && preg_match('/^[a-f0-9]{32}$/', $id); }
function private_file_path($filename) {
    if (!is_string($filename) || !preg_match('/^[a-f0-9]{32}\.(jpg|png|webp|gif|mp4|webm|mov)$/', $filename)) return false;
    $path = __DIR__ . '/uploads/' . $filename;
    return is_file($path) ? $path : false;
}
