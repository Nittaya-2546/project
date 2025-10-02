<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>สินค้า | MARKET</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../Homepage/style.css">
</head>
<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../Homepage/Index.html">MARKET</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#adidas">Adidas</a></li>
        <li class="nav-item"><a class="nav-link" href="#nike">Nike</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- PRODUCT LIST -->
<main class="container py-5">
  <h2 class="fw-bold mb-4">สินค้า</h2>

  <!-- Adidas -->
  <h4 id="adidas" class="mt-5 mb-3">Adidas</h4>
  <div class="row g-4" id="adidas-list"></div>

  <!-- Nike -->
  <h4 id="nike" class="mt-5 mb-3">Nike</h4>
  <div class="row g-4" id="nike-list"></div>
</main>

<script>
const PRODUCTS = [
  {id:'P001', brand:'Adidas', name:'Adizero Adios Pro Evo 2', price:20000, img:'../Homepage/adidas1.png'},
  {id:'P002', brand:'Adidas', name:'Adizero Takumi Sen 11',   price:6700,  img:'../Homepage/adidas2.png'},
  {id:'P003', brand:'Adidas', name:'Adizero Adios Pro 4',     price:8000,  img:'../Homepage/adidas3.png'},
  {id:'P004', brand:'Nike',   name:'Air Jordan 1 Low Silver', price:5300,  img:'../Homepage/nike1.png'},
  {id:'P005', brand:'Nike',   name:'Air Jordan 1 Low SE',     price:4900,  img:'../Homepage/nike2.png'},
  {id:'P006', brand:'Nike',   name:'Air Jordan 1 Low Pink',   price:5300,  img:'../Homepage/nike3.png'}
];

function addToCart(id){
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let product = PRODUCTS.find(p=>p.id===id);
  let found = cart.find(i=>i.id===id);
  if(found){ found.qty++; }
  else { cart.push({...product, qty:1}); }
  localStorage.setItem("cart", JSON.stringify(cart));
  alert("เพิ่มลงตะกร้าแล้ว");
}

function render(){
  const adidasList = document.getElementById("adidas-list");
  const nikeList = document.getElementById("nike-list");

  adidasList.innerHTML = PRODUCTS.filter(p=>p.brand==="Adidas").map(p=>card(p)).join('');
  nikeList.innerHTML = PRODUCTS.filter(p=>p.brand==="Nike").map(p=>card(p)).join('');
}

function card(p){
  return `
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <img src="${p.img}" class="card-img-top" alt="${p.name}">
        <div class="card-body d-flex flex-column">
  <h6 class="text-uppercase text-muted small">${p.brand}</h6>
  <h5 class="card-title fw-bold">${p.name}</h5>
  <div class="mt-auto d-flex justify-content-between align-items-center">
    <span class="fw-bold fs-5">${p.price.toLocaleString()} ฿</span>
    <div class="btn-group">
      <a href="../Product/detail.html?id=${p.id}" class="btn btn-outline-dark btn-sm">รายละเอียด</a>
      <button class="btn btn-accent btn-sm" onclick="addToCart('${p.id}')">ใส่ตะกร้า</button>
    </div>
  </div>
</div>

        </div>
      </div>
    </div>
  `;
}

render();
</script>
</body>
</html>
