<?php
// ใช้ตัวแปร $active กำหนดว่าเมนูไหนกำลังเปิดอยู่
// เช่น ใน products.php ก่อน include header.php ให้เขียนว่า
// $active = 'products';
$active = $active ?? '';  
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background:#f8f9fa; }
    .navbar-dark { background:#212529; }
    .nav-link.active { font-weight: bold; color: #fff !important; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold <?= $active==='dashboard'?'active':'' ?>" href="index.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topnav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= $active==='products'?'active':'' ?>" href="products.php">จัดการสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $active==='categories'?'active':'' ?>" href="categories.php">จัดการประเภท</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $active==='orders'?'active':'' ?>" href="orders.php">จัดการออเดอร์</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $active==='users'?'active':'' ?>" href="users.php">จัดการผู้ใช้</a>
        </li>
      </ul>
      <a href="logout.php" class="btn btn-outline-light">ออกจากระบบ</a>
    </div>
  </div>
</nav>

<div class="container my-4">
