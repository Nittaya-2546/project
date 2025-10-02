<?php
require_once __DIR__ . '/../db/connect.php';
require_once __DIR__ . '/../functions.php';
require_admin();
require_once __DIR__ . '/header.php';

// โหลดหมวดหมู่ไว้ใช้กับ select
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลสินค้า
$rows = $pdo->query("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
  <h2 class="fw-bold mb-4">จัดการสินค้า</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <!-- ฟอร์ม + ตาราง -->
</div>


  <!-- Alert -->
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <!-- ฟอร์มเพิ่ม -->
  <div class="card mb-3">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="row g-2">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="action" value="add">

        <div class="col-md-2"><input class="form-control" name="sku" placeholder="SKU" required></div>
        <div class="col-md-3">
          <select name="category_id" class="form-select" required>
            <option value="" disabled selected>เลือกประเภทสินค้า</option>
            <?php foreach($cats as $c): ?>
              <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3"><input class="form-control" name="name" placeholder="ชื่อสินค้า" required></div>
        <div class="col-md-2"><input class="form-control" type="number" step="0.01" name="price" placeholder="ราคา" required></div>
        <div class="col-md-2"><input class="form-control" type="number" name="stock" placeholder="สต๊อก" required></div>
        <div class="col-md-3"><input class="form-control" type="date" name="expected_restock_date"></div>
        <div class="col-md-3"><input class="form-control" type="file" name="image" accept="image/*"></div>
        <div class="col-md-2 form-check ms-2">
          <input class="form-check-input" type="checkbox" name="is_active" checked>
          <label class="form-check-label">แสดงหน้าเว็บ</label>
        </div>
        <div class="col-12"><button class="btn btn-primary">บันทึกสินค้า</button></div>
      </form>
    </div>
  </div>

  <!-- ตาราง -->
  <div class="table-responsive">
    <table class="table table-bordered align-middle bg-white shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>รูป</th>
          <th>SKU</th>
          <th>ชื่อ</th>
          <th>หมวด</th>
          <th class="text-end">ราคา</th>
          <th class="text-center">สต๊อก</th>
          <th>คาดเข้า</th>
          <th>สถานะ</th>
          <th width="160">การจัดการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $p): ?>
          <tr>
            <td><img src="<?= $p['image'] ? base_url('upload/'.$p['image']) : base_url('assets/no-image.png') ?>" width="48" class="rounded"></td>
            <td><?= htmlspecialchars($p['sku']) ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
            <td class="text-end">฿<?= money($p['price']) ?></td>
            <td class="text-center"><?= (int)$p['stock'] ?></td>
            <td><?= htmlspecialchars($p['expected_restock_date'] ?? '') ?></td>
            <td><?= $p['is_active'] ? '<span class="badge bg-success">แสดง</span>' : '<span class="badge bg-secondary">ซ่อน</span>' ?></td>
            <td>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#edit<?= $p['id'] ?>">แก้ไข</button>
              <form method="post" class="d-inline" onsubmit="return confirm('ลบสินค้านี้ใช่หรือไม่?')">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">ลบ</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
