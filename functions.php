<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ ฟังก์ชัน base url
function base_url($path = '') {
    return "http://localhost/project/" . ltrim($path, '/');
}

// ✅ CSRF Token
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function check_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

// ✅ ฟังก์ชัน format เงิน
function money($n) {
    return number_format((float)$n, 2);
}

// ✅ ฟังก์ชันเช็คสิทธิ์ admin
function is_admin() {
    return !empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

// ✅ ฟังก์ชันบังคับให้เป็น admin เท่านั้น
function require_admin() {
    if (!is_admin()) {
        header("Location: " . base_url("Login/login.php"));
        exit;
    }
}
// functions.php
function cart_items() { return $_SESSION['cart'] ?? []; }
function cart_count() {
    return array_reduce(cart_items(), fn($s,$i)=>$s + (int)$i['qty'], 0);
}