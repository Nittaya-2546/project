<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../db/connect.php"; // ✅ ใช้ $pdo

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ ตรวจสอบว่า email ซ้ำหรือไม่
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "อีเมลนี้ถูกใช้งานแล้ว!";
    } else {
        // ✅ เข้ารหัสรหัสผ่าน
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role, created_at) 
                VALUES (?, ?, ?, 'user', NOW())";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$name, $email, $hashedPassword])) {
            header("Location: login.php");
            exit;
        } else {
            $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
        }
    }
}
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>สมัครสมาชิก</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">สมัครสมาชิก</h4>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input class="form-control" type="text" name="name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input class="form-control" type="email" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input class="form-control" type="password" name="password" required>
              </div>
              <button class="btn btn-primary w-100">สมัครสมาชิก</button>
            </form>
            <div class="mt-3 text-center">
              <a href="login.php">เข้าสู่ระบบ</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
