<?php
session_start();
require_once __DIR__ . '/../db/connect.php';

// ตรวจสอบสิทธิ์ admin
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit;
}

// นับจำนวนต่าง ๆ
$pc = (int)$pdo->query("SELECT COUNT(*) AS c FROM products")->fetch()['c'];
$oc = (int)$pdo->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'];
$cc = (int)$pdo->query("SELECT COUNT(*) AS c FROM categories")->fetch()['c'];
$uc = (int)$pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c']; // ✅ เพิ่มนับผู้ใช้
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">Admin Panel</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="products.php">จัดการสินค้า</a></li>
        <li class="nav-item"><a class="nav-link" href="categories.php">จัดการประเภท</a></li>
        <li class="nav-item"><a class="nav-link" href="orders.php">จัดการออเดอร์</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php">จัดการผู้ใช้</a></li> <!-- ✅ เพิ่มเมนู -->
      </ul>
      <a href="logout.php" class="btn btn-outline-light">ออกจากระบบ</a>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4 fw-bold">แผงควบคุมผู้ดูแลระบบ</h2>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted">จำนวนสินค้า</div>
          <div class="display-6 fw-bold"><?= $pc ?></div>
          <a href="products.php" class="btn btn-primary btn-sm mt-2">จัดการสินค้า</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted">จำนวนประเภท</div>
          <div class="display-6 fw-bold"><?= $cc ?></div>
          <a href="categories.php" class="btn btn-primary btn-sm mt-2">จัดการประเภท</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1"> <!-- ✅ แถวที่ 2 -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted">จำนวนออเดอร์</div>
          <div class="display-6 fw-bold"><?= $oc ?></div>
          <a href="orders.php" class="btn btn-primary btn-sm mt-2">จัดการออเดอร์</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted">จำนวนผู้ใช้</div>
          <div class="display-6 fw-bold"><?= $uc ?></div>
          <a href="users.php" class="btn btn-primary btn-sm mt-2">จัดการผู้ใช้</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
