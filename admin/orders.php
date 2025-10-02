<?php
require_once __DIR__.'/../db/connect.php';
require_once __DIR__.'/../functions.php';
require_admin();
require_once __DIR__.'/header.php';

$ok=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST' && check_csrf($_POST['csrf']??'')){
  try{
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status,$id]);
    $ok='อัปเดตสถานะเรียบร้อย';
  }catch(Throwable $e){ $err=$e->getMessage(); }
}

$q = "
SELECT o.*, u.name AS user_name, u.email
FROM orders o
JOIN users u ON u.id = o.user_id
ORDER BY o.id DESC";
$orders = $pdo->query($q)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-4">
  <h2 class="fw-bold mb-4">จัดการออเดอร์</h2>

  <?php if($ok): ?>
    <div class="alert alert-success"><?= htmlspecialchars($ok) ?></div>
  <?php endif; ?>
  <?php if($err): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>
    <a class="btn btn-outline-secondary" href="<?= base_url('../Product/admin/index.php') ?>">← กลับแผงควบคุม</a>
  </div>
  <?php if($ok): ?><div class="alert alert-success mt-3"><?= htmlspecialchars($ok) ?></div><?php endif;?>
  <?php if($err): ?><div class="alert alert-danger mt-3"><?= htmlspecialchars($err) ?></div><?php endif;?>

  <div class="table-responsive mt-3">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th><th>รหัสคำสั่งซื้อ</th><th>ลูกค้า</th>
          <th>วิธีรับ</th><th>วันที่รับ</th><th>สถานะ</th><th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($orders as $o): ?>
        <tr>
          <td><?= (int)$o['id'] ?></td>
          <td><?= htmlspecialchars($o['order_code']) ?></td>
          <td><?= htmlspecialchars($o['user_name']) ?> <small class="text-muted">(<?= htmlspecialchars($o['email']) ?>)</small></td>
          <td><?= htmlspecialchars($o['pickup_option']) ?></td>
          <td><?= htmlspecialchars($o['pickup_date']) ?></td>
          <td>
            <form method="post" class="d-flex gap-2">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
              <select class="form-select form-select-sm" name="status">
                <?php
                  $statuses=['pending','confirmed','preparing','ready_for_pickup','picked_up','cancelled'];
                  foreach($statuses as $st){
                    $sel = $st===$o['status']?'selected':'';
                    echo "<option $sel value=\"$st\">$st</option>";
                  }
                ?>
              </select>
              <button class="btn btn-sm btn-primary">บันทึก</button>
            </form>
          </td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="orders_view.php?id=<?= (int)$o['id'] ?>">ดูรายการ</a>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
