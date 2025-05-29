<?php
session_name('USERSESSID');
if(session_status() === PHP_SESSION_NONE){
  session_start(); 
}


// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'resturant_project');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch categories
$categories = $mysqli->query("SELECT DISTINCT category FROM featured_food") or die($mysqli->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Food Ordering System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      padding: 120px 15px 10px 15px;
      background: #f3f4f6;
      display: flex;
      min-height: 100vh;
      color: #1f2937;
    }
    .sidebar {
      width: 220px;
      background: #fff;
      padding: 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }
    .sidebar h2 {
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: 600;
    }
    .sidebar ul {
      list-style: none;
    }
    .sidebar li {
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      transition: background 0.3s;
    }
    .sidebar li.active,
    .sidebar li:hover {
      background: #ffeecd;
      font-weight: 600;
    }
    .main {
      flex: 1;
      padding: 20px;
      display: flex;
      flex-direction: column;
      width: calc(100% - 220px);
    }
    .search-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }
    .search-bar input {
      width: 50%;
      padding: 10px 16px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }
    .card {
      background: white;
      border-radius: 16px;
      padding: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: relative;
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 12px;
    }
    .label {
      position: absolute;
      top: 12px;
      left: 12px;
      background: #4dabf7;
      color: white;
      padding: 3px 6px;
      font-size: 12px;
      font-weight: bold;
      border-radius: 6px;
    }
    .card h3 {
      font-size: 16px;
      margin: 10px 0 4px;
    }
    .card p {
      font-size: 13px;
      color: #6b7280;
    }
    .price-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
    }
    .price {
      font-weight: bold;
      font-size: 16px;
    }
    .add-btn {
      background: #10b981;
      color: white;
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      font-size: 20px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .add-btn:hover {
      background: #059669;
    }

    .minus-btn {
  background:rgb(228, 61, 31);
  color: white;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  font-size: 20px;
  cursor: pointer;
  transition: background 0.3s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.minus-btn:hover {
  background:rgb(189, 40, 14);
}

.quantity {
  font-weight: bold;
  font-size: 16px;
  padding: 0 10px;
  min-width: 20px;
  text-align: center;
}

  </style>
</head>
<body>
    <?php include_once("header.php"); ?>

    <div class="sidebar">
        <h2>Choose Menu</h2>
        <ul>
            <li class="category" data-category="all">All Menu</li>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <li class="category" data-category="<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="main">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search a dish...">
        </div>
        <div class="menu-grid" id="menuGrid">
            <?php
            $result = $mysqli->query("SELECT * FROM featured_food ORDER BY RAND()") or die($mysqli->error);
            while ($item = $result->fetch_assoc()):
                $img = htmlspecialchars($item['imgsource']);
                $title = htmlspecialchars($item['title']);
                $desc = htmlspecialchars($item['description']);
                $price = number_format((float)$item['price'], 2);
                $category = htmlspecialchars($item['category']);
                $isPopular = rand(0, 1); // Random label for demo
            ?>
                <div class="card" data-id="<?php echo $item['id']; ?>" data-category="<?php echo $category; ?>" data-title="<?php echo $title; ?>">
  <?php if ($isPopular): ?>
    <div class="label"><?php echo (rand(0,1) ? "Popular" : "Disc 30%"); ?></div>
  <?php endif; ?>
  <img src="http://localhost/php_training/resturant-project/server-side/imgsource/<?php echo $img; ?>" alt="<?php echo $title; ?>">
  <h3><?php echo $title; ?></h3>
  <p><?php echo $desc; ?></p>
  <div class="price-row">
    <span class="price">₹<?php echo $price; ?></span>
    <div style="display: flex; gap: 5px; align-items: center;">
      <button class="minus-btn" style="display: none;">−</button>
      <span class="quantity">0</span>
      <button class="add-btn">+</button>
    </div>
  </div>
</div>

            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'cart.php'; ?>
<script src="cart.js"></script>


<script src="cart.js"></script>
<script>
  function refreshMenuQuantities() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    document.querySelectorAll('.card').forEach(card => {
      const itemId = card.getAttribute('data-id');
      const quantitySpan = card.querySelector('.quantity');
      const minusBtn = card.querySelector('.minus-btn');

      if (cart[itemId]) {
        quantitySpan.textContent = cart[itemId].quantity;
        minusBtn.style.display = 'inline-block';
      } else {
        quantitySpan.textContent = '0';
        minusBtn.style.display = 'none';
      }
    });
  }

  // Patch updateCartUI to always refresh menu quantities
  const originalUpdateCartUI = window.updateCartUI || function () {};
  window.updateCartUI = function () {
    originalUpdateCartUI();
    refreshMenuQuantities();
  };

  document.addEventListener('DOMContentLoaded', function () {
    if (!localStorage.getItem('cart')) {
      localStorage.setItem('cart', JSON.stringify({}));
    }

    const cards = document.querySelectorAll('.card');
    const categories = document.querySelectorAll('.category');
    const searchInput = document.getElementById('searchInput');

    // CATEGORY FILTER
    categories.forEach(category => {
      category.addEventListener('click', function () {
        const selectedCategory = this.getAttribute('data-category');
        categories.forEach(cat => cat.classList.remove('active'));
        this.classList.add('active');
        cards.forEach(card => {
          const cardCategory = card.getAttribute('data-category');
          card.style.display = (selectedCategory === 'all' || cardCategory === selectedCategory) ? 'block' : 'none';
        });
      });
    });

    // SEARCH FUNCTION
    searchInput.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase();
      cards.forEach(card => {
        const title = card.getAttribute('data-title').toLowerCase();
        if (title.includes(searchTerm)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });

    // CART BUTTON HANDLERS
    cards.forEach(card => {
      const itemId = card.getAttribute('data-id');
      const title = card.querySelector('h3').textContent.trim();
      const price = parseFloat(card.querySelector('.price').textContent.replace('₹', '').trim());
      const img = card.querySelector('img').src;
      const quantitySpan = card.querySelector('.quantity');
      const plusBtn = card.querySelector('.add-btn');
      const minusBtn = card.querySelector('.minus-btn');

      plusBtn.addEventListener('click', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || {};
        if (!cart[itemId]) {
          cart[itemId] = { title, price, quantity: 0, img };
        }
        cart[itemId].quantity++;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
      });

      minusBtn.addEventListener('click', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || {};
        if (cart[itemId]) {
          cart[itemId].quantity--;
          if (cart[itemId].quantity <= 0) {
            delete cart[itemId];
          }
          localStorage.setItem('cart', JSON.stringify(cart));
          updateCartUI();
        }
      });
    });

    updateCartUI(); // Final sync
  });
</script>

</body>
</html>
