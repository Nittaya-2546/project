<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db/connect.php"; // ต้องมี $pdo จาก connect.php

// ----------------- เพิ่มสินค้าเข้าตะกร้า -----------------
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }

    // ✅ กลับไปหน้าที่มาก่อนหน้า (products.php หรือ index.php)
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
    exit;
}

// ----------------- ลบสินค้า -----------------
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// ----------------- ลบทั้งหมด -----------------
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>ตะกร้าสินค้า | MARKET</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">MARKET</a>
  </div>
</nav>

<main class="container py-5">
  <h3 class="fw-bold mb-4">ตะกร้าสินค้า</h3>
  <table class="table align-middle">
    <thead class="table-light">
      <tr>
        <th>สินค้า</th>
        <th>ชื่อสินค้า</th>
        <th>ราคา</th>
        <th>จำนวน</th>
        <th>รวม</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        $ids = implode(",", array_keys($_SESSION['cart']));

        if ($ids) {
            // ✅ ใช้ PDO เต็มรูปแบบ
            $sql = "SELECT * FROM products WHERE id IN ($ids)";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $id   = $row['id'];
                $qty  = $_SESSION['cart'][$id];
                $sum  = $row['price'] * $qty;
                $total += $sum;

                echo "<tr>
                    <td><img src='../upload/{$row['image']}' width='60'></td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . number_format($row['price']) . " ฿</td>
                    <td>$qty</td>
                    <td>" . number_format($sum) . " ฿</td>
                    <td><a href='cart.php?remove=$id' class='btn btn-sm btn-outline-danger'>ลบ</a></td>
                </tr>";
            }

            echo "<tr>
                <td colspan='4' class='text-end fw-bold'>รวมทั้งหมด</td>
                <td colspan='2' class='fw-bold'>" . number_format($total) . " ฿</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center text-muted'>ตะกร้าสินค้าว่างเปล่า</td></tr>";
    }
    ?>
    </tbody>
  </table>

  <div class="d-flex justify-content-between mt-3">
    <a href="cart.php?clear=1" class="btn btn-outline-danger">ลบทั้งหมด</a>
    <div>
      <a href="../index.php" class="btn btn-outline-dark me-2">เลือกซื้อสินค้าต่อ</a>
      <a href="checkout.php" class="btn btn-success">ชำระเงิน</a>
    </div>
  </div>
</main>

</body>
</html>
