<?php
require_once __DIR__ . '/../db/connect.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'])) {
        die("CSRF token ไม่ถูกต้อง");
    }

    if (empty($_SESSION['user']) || empty($_SESSION['cart'])) {
        die("ไม่มีสิทธิ์ หรือ ตะกร้าว่าง");
    }

    $user_id = $_SESSION['user']['id'];
    $pickup_option = $_POST['pickup_option'];
    $pickup_date = $_POST['pickup_date'];
    $order_code = order_code();
    $status = "pending";

    // 1. insert order
    $sql = "INSERT INTO orders (order_code, user_id, pickup_option, pickup_date, status, created_at) 
            VALUES (?,?,?,?,?,NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisss", $order_code, $user_id, $pickup_option, $pickup_date, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 2. insert order items
    foreach ($_SESSION['cart'] as $item) {
        $sql2 = "INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?,?,?,?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iiid", $order_id, $item['id'], $item['qty'], $item['price']);
        $stmt2->execute();
    }

    // 3. clear cart
    unset($_SESSION['cart']);

    echo "<script>alert('สั่งซื้อสำเร็จ เลขที่คำสั่งซื้อ: $order_code'); 
          window.location='../Homepage/index.php';</script>";
}
