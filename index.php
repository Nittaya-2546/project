<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>MARKET | Sneakers</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">MARKET</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">หน้าแรก</a></li>
        
        <!-- 🔹 เมนูสินค้า Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            สินค้า
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="Product/products.php?brand=adidas">Adidas</a></li>
            <li><a class="dropdown-item" href="Product/products.php?brand=nike">Nike</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="Product/products.php">สินค้าทั้งหมด</a></li>
          </ul>
        </li>
      </ul>

      <!-- 🔹 โซน User -->
<div id="userArea" class="d-flex align-items-center">
  <?php if (!empty($_SESSION['user'])): ?>
      <span class="me-3">สวัสดี, <?= $_SESSION['user']['name'] ?></span>
      <a href="Login/logout.php" class="btn btn-outline-danger btn-sm me-2">ออกจากระบบ</a>

      <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <a href="admin/index.php" class="btn btn-warning me-2">จัดการระบบ</a>
      <?php endif; ?>

  <?php else: ?>
      <a href="Login/login.php" class="btn btn-outline-dark me-2">เข้าสู่ระบบ</a>
      <a href="Login/register.php" class="btn btn-outline-secondary me-2">สมัครสมาชิก</a>
  <?php endif; ?>

  <a href="Cart/cart.php" class="btn btn-accent">
    ตะกร้า <span class="badge bg-danger ms-1">
      <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
    </span>
  </a>
</div>

  </div>
</nav>

<!-- HERO -->
<header class="hero">
  <div class="content text-center">
    <h1>FIND YOUR STYLE</h1>
    <p>รองเท้า Adidas & Nike คอลเลกชันใหม่</p>
    <a href="#products" class="btn btn-accent btn-lg mt-3">เลือกซื้อเลย</a>
  </div>
</header>

<!-- PRODUCTS -->
<section id="products" class="py-5">
  <div class="container">
    <div class="d-flex align-items-center mb-4">
      <h2 class="m-0 fw-bold">สินค้าแนะนำ</h2>
      <div class="ms-3 flex-grow-1 border-bottom"></div>
    </div>
    <div id="grid" class="row g-4"></div>
  </div>
</section>

<footer class="py-4 bg-dark text-white text-center">
  © 2025 MARKET — Local Images
</footer>

<script>
  // ----------------- ข้อมูลสินค้า -----------------
  const PRODUCTS = [
    {id:1, brand:'Adidas', name:'Adizero Adios Pro Evo 2', price:20000, img:'upload/adidas1.png'},
    {id:2, brand:'Adidas', name:'Adizero Takumi Sen 11',   price:6700,  img:'upload/adidas2.png'},
    {id:3, brand:'Adidas', name:'Adizero Adios Pro 4',     price:8000,  img:'upload/adidas3.png'},
    {id:4, brand:'Nike',   name:'Air Jordan 1 Low Silver', price:5300,  img:'upload/nike1.png'},
    {id:5, brand:'Nike',   name:'Air Jordan 1 Low SE',     price:4900,  img:'upload/nike2.png'},
    {id:6, brand:'Nike',   name:'Air Jordan 1 Low Pink',   price:5300,  img:'upload/nike3.png'}
  ];

  // ----------------- Render สินค้า -----------------
  function render(){
    const el = document.getElementById('grid');
    el.innerHTML = PRODUCTS.map(p=>`
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card product-card h-100 border-0 shadow-sm">
          <img src="${p.img}" class="card-img-top" alt="${p.name}" 
               onerror="this.src='upload/placeholder.png'">
          <div class="card-body d-flex flex-column">
            <h6 class="text-uppercase text-muted small">${p.brand}</h6>
            <h5 class="card-title fw-bold">${p.name}</h5>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <span class="fw-bold fs-5">${p.price.toLocaleString()} ฿</span>
              <div>
                <a href="Product/detail.php?id=${p.id}" class="btn btn-outline-dark btn-sm me-2">ดูรายละเอียด</a>
                <a href="Cart/cart.php?add=${p.id}" class="btn btn-accent btn-sm">ใส่ตะกร้า</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }

  render();
</script>
</body>
</html>
