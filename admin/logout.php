<?php
require_once __DIR__ . '/../functions.php';

// ลบ session admin
unset($_SESSION['admin']);

// กลับไปที่หน้าแรกของ shop
header('Location: ' . base_url('index.php'));
exit;
