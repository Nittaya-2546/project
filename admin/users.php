<?php
session_start();
require_once __DIR__ . '/../db/connect.php'; // ใช้ $pdo จาก connect.php

// ตรวจสอบสิทธิ์ admin
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit;
}

// ✅ ลบผู้ใช้
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: users.php");
    exit;
}

// ✅ เปลี่ยน role
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $newRole = $row['role'] === 'admin' ? 'user' : 'admin';
        $stmt2 = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt2->execute([$newRole, $id]);
    }
    header("Location: users.php");
    exit;
}

// ✅ ดึงข้อมูล users ทั้งหมด
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>จัดการผู้ใช้</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Admin Panel</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">ออกจากระบบ</a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h3 class="fw-bold mb-4">จัดการผู้ใช้</h3>

  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>ชื่อ</th>
        <th>อีเมล</th>
        <th>Role</th>
        <th>วันที่สมัคร</th>
        <th width="200">จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $row): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td>
            <span class="badge bg-<?= $row['role'] === 'admin' ? 'warning' : 'secondary' ?>">
              <?= $row['role'] ?>
            </span>
          </td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="?toggle=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
              เปลี่ยนเป็น <?= $row['role'] === 'admin' ? 'User' : 'Admin' ?>
            </a>
            <a href="?delete=<?= $row['id'] ?>" 
               onclick="return confirm('คุณแน่ใจว่าจะลบผู้ใช้นี้?')" 
               class="btn btn-sm btn-danger">
              ลบ
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
