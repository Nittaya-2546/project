<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db/connect.php"; // ✅ ใช้ไฟล์เชื่อมต่อ PDO เดิม

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($pass, $row['password']) || $pass === $row['password']) {
            $_SESSION['user'] = [
                'id'    => $row['id'],
                'name'  => $row['name'],
                'email' => $row['email'],
                'role'  => $row['role']
            ];

            if ($row['role'] === 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบบัญชีผู้ใช้";
    }
}
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>เข้าสู่ระบบ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">เข้าสู่ระบบ</h4>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input class="form-control" type="email" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input class="form-control" type="password" name="password" required>
              </div>
              <button class="btn btn-primary w-100">เข้าสู่ระบบ</button>
            </form>
            <div class="mt-3 text-center">
              <a href="register.php">สมัครสมาชิกใหม่</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
