<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db/connect.php"; // ใช้ $pdo

// ----------------- ดึงสินค้า -----------------
$brand = $_GET['brand'] ?? '';  // รับ brand จาก URL

if ($brand) {
    $sql = "SELECT * FROM products WHERE is_active=1 AND brand = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$brand]);
} else {
    $sql = "SELECT * FROM products WHERE is_active=1";
    $stmt = $pdo->query($sql);
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ฟังก์ชันนับจำนวนสินค้าในตะกร้า
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>สินค้า | MARKET</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">MARKET</a>
    <div class="d-flex align-items-center">
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="me-3">สวัสดี, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="../Login/logout.php" class="btn btn-outline-danger btn-sm me-2">ออกจากระบบ</a>

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <a href="../admin/index.php" class="btn btn-warning btn-sm me-2">จัดการระบบ</a>
        <?php endif; ?>

      <?php else: ?>
        <a href="../Login/login.php" class="btn btn-outline-dark btn-sm me-2">เข้าสู่ระบบ</a>
        <a href="../Login/register.php" class="btn btn-outline-secondary btn-sm me-2">สมัครสมาชิก</a>
      <?php endif; ?>

      <a href="../Cart/cart.php" class="btn btn-dark btn-sm">
        ตะกร้า <span class="badge bg-danger ms-1"><?= $cartCount ?></span>
      </a>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container my-5">
  <h2 class="fw-bold mb-4">
    <?php if ($brand): ?>
      สินค้าแบรนด์ <?= htmlspecialchars($brand) ?>
    <?php else: ?>
      สินค้าทั้งหมด
    <?php endif; ?>
  </h2>

  <div class="row g-4">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $row): ?>
        <div class="col-md-4">
          <div class="product-card p-3 border rounded text-center">
            <a href="detail.php?id=<?= $row['id'] ?>">
              <img src="../upload/<?= htmlspecialchars($row['image']) ?>" 
                   alt="<?= htmlspecialchars($row['name']) ?>" 
                   onerror="this.onerror=null;this.src='../upload/placeholder.png'" 
                   class="img-fluid mb-2">
            </a>
            <h6 class="text-muted"><?= htmlspecialchars($row['brand'] ?? '') ?></h6>
            <h5><a href="detail.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></h5>
            <p class="fw-bold"><?= number_format($row['price'], 2) ?> ฿</p>
            <a href="../Cart/cart.php?add=<?= $row['id'] ?>" 
               class="btn btn-dark btn-sm"
               onclick="Swal.fire({icon:'success',title:'เพิ่มลงตะกร้าแล้ว',showConfirmButton:false,timer:1200});">
              ใส่ตะกร้า
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">ไม่พบสินค้าในแบรนด์นี้</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
