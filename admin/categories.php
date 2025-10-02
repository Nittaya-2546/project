<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../functions.php';
require_admin();

// Helper
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// DB
try {
  $pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  exit('เชื่อมต่อฐานข้อมูลไม่ได้: '.h($e->getMessage()));
}

$success = $error = '';

// POST: add / update / delete
try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_csrf($_POST['csrf'] ?? '')) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
      $name = trim($_POST['name'] ?? '');
      $description = trim($_POST['description'] ?? '');
      if ($name === '') throw new Exception('กรุณากรอกชื่อประเภทสินค้า');
      $stmt = $pdo->prepare("INSERT INTO categories (name, description, created_at) VALUES (?, ?, NOW())");
      $stmt->execute([$name, $description]);
      $success = 'เพิ่มประเภทสินค้าเรียบร้อย';
    }

    if ($action === 'update') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $description = trim($_POST['description'] ?? '');
      if ($id <= 0) throw new Exception('ไม่พบรายการ');
      if ($name === '') throw new Exception('กรุณากรอกชื่อประเภทสินค้า');
      $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
      $stmt->execute([$name, $description, $id]);
      $success = 'แก้ไขประเภทสินค้าเรียบร้อย';
    }

    if ($action === 'delete') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id <= 0) throw new Exception('ไม่พบรายการ');
      $hasCol = $pdo->query("SHOW COLUMNS FROM products LIKE 'category_id'")->rowCount() > 0;
      if ($hasCol) {
        $stmtCnt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmtCnt->execute([$id]);
        if ((int)$stmtCnt->fetchColumn() > 0) {
          throw new Exception('มีสินค้ายังอ้างถึงประเภทนี้ จึงลบไม่ได้');
        }
      }
      $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
      $stmt->execute([$id]);
      $success = 'ลบประเภทสินค้าเรียบร้อย';
    }
  }
} catch (Throwable $e) {
  $error = $e->getMessage();
}

// GET: search + list
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
  $stmt = $pdo->prepare("SELECT id, name, description, created_at
                          FROM categories
                          WHERE name LIKE ? OR description LIKE ?
                          ORDER BY id DESC");
  $like = "%$q%";
  $stmt->execute([$like, $like]);
} else {
  $stmt = $pdo->query("SELECT id, name, description, created_at FROM categories ORDER BY id DESC");
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ include header
$active = 'categories';
require_once __DIR__ . '/header.php';
?>

<div class="container my-4">
  <h2 class="fw-bold mb-4">จัดการประเภทสินค้า</h2>

  <?php if ($success): ?>
    <div class="alert alert-success py-2"><?= h($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger py-2"><?= h($error) ?></div>
  <?php endif; ?>

  <!-- ฟอร์ม + ตาราง -->
</div>


<?php if ($success): ?><div class="alert alert-success py-2"><?= h($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger py-2"><?= h($error) ?></div><?php endif; ?>

<div class="card mb-3">
  <div class="card-body">
    <form class="row g-2" method="post">
      <input type="hidden" name="csrf" value="<?= h(csrf_token()); ?>">
      <input type="hidden" name="action" value="add">
      <div class="col-md-4"><input class="form-control" name="name" placeholder="ชื่อประเภท" required></div>
      <div class="col-md-6"><input class="form-control" name="description" placeholder="คำอธิบาย (ถ้ามี)"></div>
      <div class="col-md-2"><button class="btn btn-primary w-100">เพิ่มประเภท</button></div>
    </form>
  </div>
</div>

<form class="mb-3" method="get" style="max-width:520px">
  <div class="input-group">
    <input class="form-control" name="q" value="<?= h($q) ?>" placeholder="ค้นหาจากชื่อ/คำอธิบาย">
    <button class="btn btn-outline-secondary">ค้นหา</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">#</th>
        <th>ชื่อประเภท</th>
        <th>คำอธิบาย</th>
        <th style="width:180px">วันที่สร้าง</th>
        <th style="width:200px" class="text-end">การจัดการ</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!$rows): ?>
      <tr><td colspan="5" class="text-center text-muted">— ไม่พบข้อมูล —</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td><?= h($r['id']) ?></td>
        <td><?= h($r['name']) ?></td>
        <td><?= h($r['description']) ?></td>
        <td><?= h($r['created_at']) ?></td>
        <td class="text-end">
          <button class="btn btn-sm btn-primary me-2"
            data-bs-toggle="modal"
            data-bs-target="#editModal"
            data-id="<?= h($r['id']) ?>"
            data-name="<?= h($r['name']) ?>"
            data-description="<?= h($r['description']) ?>">แก้ไข</button>
          <form method="post" class="d-inline" onsubmit="return confirm('ยืนยันลบประเภทนี้?');">
            <input type="hidden" name="csrf" value="<?= h(csrf_token()); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= h($r['id']) ?>">
            <button class="btn btn-sm btn-danger">ลบ</button>
          </form>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post">
      <input type="hidden" name="csrf" value="<?= h(csrf_token()); ?>">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-header">
        <h5 class="modal-title">แก้ไขประเภทสินค้า</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">ชื่อประเภท</label>
          <input class="form-control" name="name" id="edit_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">คำอธิบาย</label>
          <input class="form-control" name="description" id="edit_description">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">บันทึก</button>
      </div>
    </form>
  </div>
</div>
