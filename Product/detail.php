<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>รายละเอียดสินค้า | MARKET</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <style>
    .product-img {
      max-height: 400px;
      object-fit: cover;
    }
    .btn-accent {
      background-color: #ff6600;
      color: #fff;
    }
    .btn-accent:hover {
      background-color: #e65c00;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">MARKET</a>
  </div>
</nav>

<!-- MAIN -->
<main class="container py-5" id="detail"></main>

<script>
// ----------------- สินค้าทดลอง -----------------
const PRODUCTS = [
  {id:'P001', brand:'Adidas', name:'Adizero Adios Pro Evo 2', price:20000, img:'../upload/adidas1.png', desc:'รองเท้าวิ่งน้ำหนักเบา ออกแบบสำหรับนักวิ่งมืออาชีพ'},
  {id:'P002', brand:'Adidas', name:'Adizero Takumi Sen 11',   price:6700,  img:'../upload/adidas2.png', desc:'รองเท้าวิ่งระยะสั้น – กลาง ยืดหยุ่นและกระชับ'},
  {id:'P003', brand:'Adidas', name:'Adizero Adios Pro 4',     price:8000,  img:'../upload/adidas3.png', desc:'รองเท้าวิ่งระยะไกล พื้นคาร์บอนเพื่อแรงส่งที่ดี'},
  {id:'P004', brand:'Nike',   name:'Air Jordan 1 Low Silver', price:5300,  img:'../upload/nike1.png', desc:'รองเท้าสนีกเกอร์สีเงิน ดีไซน์คลาสสิก'},
  {id:'P005', brand:'Nike',   name:'Air Jordan 1 Low SE',     price:4900,  img:'../upload/nike2.png', desc:'สนีกเกอร์สุดฮิต ใส่สบายทุกโอกาส'},
  {id:'P006', brand:'Nike',   name:'Air Jordan 1 Low Pink',   price:5300,  img:'../upload/nike3.png', desc:'สนีกเกอร์โทนชมพู สวยโดดเด่นไม่เหมือนใคร'}
];

// ----------------- ฟังก์ชัน -----------------
function addToCart(id){
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let product = PRODUCTS.find(p=>p.id===id);
  let found = cart.find(i=>i.id===id);
  if(found){ found.qty++; }
  else { cart.push({...product, qty:1}); }
  localStorage.setItem("cart", JSON.stringify(cart));
  alert("เพิ่มสินค้าลงตะกร้าแล้ว");
}

function renderDetail(){
  const url = new URLSearchParams(window.location.search);
  const id = url.get("id");
  const p = PRODUCTS.find(x=>x.id===id);

  if(!p){
    document.getElementById("detail").innerHTML = `<p class="text-danger">ไม่พบสินค้า</p>`;
    return;
  }

  document.getElementById("detail").innerHTML = `
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="${p.img}" class="img-fluid rounded shadow product-img" alt="${p.name}">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold">${p.name}</h2>
        <p class="text-muted mb-1">${p.brand}</p>
        <p class="mb-3">${p.desc}</p>
        <h3 class="fw-bold text-danger mb-4">${p.price.toLocaleString()} ฿</h3>
        <button class="btn btn-accent btn-lg px-4" onclick="addToCart('${p.id}')">🛒 ใส่ตะกร้า</button>
      </div>
    </div>
  `;
}

renderDetail();
</script>

</body>
</html>
