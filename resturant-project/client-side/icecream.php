<?php
session_name('USERSESSID');
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if(!isset($_SESSION['user_id'])){
  header('location:user_login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ice Cream Ordering</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .box-2 {
      margin-top: 6%;
      text-align: center;
    }

    .pizza-item {
      display: inline-block;
      background-color: skyblue;
      padding: 20px;
      margin: 10px;
      box-shadow: 1px 1px 3px 3px white;
      border-radius: 10px;
      text-align: center;
    }

    .pizza-item img {
      width: 100%;
      height: auto;
      max-width: 300px;
      max-height: 300px;
      border-radius: 10px;
    }

    .add-to-cart, .remove-from-cart {
      color: white;
      padding: 5px 10px;
      margin: 5px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }

    .add-to-cart {
      background-color: green;
    }

    .remove-from-cart {
      background-color: red;
    }
  </style>
</head>
<body>
<?php
  session_start(); 
  if (!isset($_SESSION['username'])) {
    header('location:user_login.php');
  } else {
    include_once('header.php');
    echo "<br/><div class='box-2'>";
    echo "<h1>Order Your Ice Cream</h1>";
    echo "<center><hr style='width:17%;background-color:#02A237;margin-top:5px;border:4px dashed white;'></center><br/>";

    $mysqli = new mysqli('localhost', 'root', '', 'resturant_project');
    $result = $mysqli->query("SELECT * FROM icecream") or die($mysqli->error);

    echo "<div style='display: flex; flex-wrap: wrap; justify-content: center;'>";
    while ($data = $result->fetch_assoc()) { 
      $image = trim($data['imgsource']);
      $imagePath = "http://localhost/php_training/resturant-project/server-side/imgsource/" . $image;

      echo "<div class='pizza-item'>";
      echo "<img src='{$imagePath}' alt='Ice Cream Image' onerror=\"this.onerror=null;this.src='https://via.placeholder.com/300x300?text=No+Image';\"/>";
      echo "<div>{$data['title']} - ₹{$data['price']}</div>";
      echo "<button class='add-to-cart' 
              data-id='{$data['id']}' 
              data-source-table='icecream' 
              data-title='{$data['title']}' 
              data-price='{$data['price']}' 
              data-image='{$imagePath}'>Add to Cart</button>";
      echo "<button class='remove-from-cart' 
              data-id='{$data['id']}' 
              data-source-table='icecream'>Remove</button>";
      echo "</div>";
    }

    echo "</div></div>";
    include_once('footer-2.php');
  }
?>

<!-- ✅ Include the Cart UI -->
<?php include 'cart.php'; ?>

<!-- ✅ External Cart Panel Logic -->
<script src="cart.js"></script>

<!-- ✅ Ice Cream Add/Remove Cart Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Add to Cart
  document.querySelectorAll('.add-to-cart').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const sourceTable = btn.getAttribute('data-source-table') || 'food';
      const itemId = btn.getAttribute('data-id');
      const cartKey = `${sourceTable}-${itemId}`;
      const title = btn.getAttribute('data-title');
      const price = parseFloat(btn.getAttribute('data-price'));
      const img = btn.getAttribute('data-image');

      let cart = JSON.parse(localStorage.getItem('cart')) || {};

      if (!cart[cartKey]) {
        cart[cartKey] = { title, price, quantity: 1, img };
      } else {
        cart[cartKey].quantity += 1;
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      if (typeof updateCartUI === 'function') updateCartUI();
    });
  });

  // Remove from Cart
  document.querySelectorAll('.remove-from-cart').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const sourceTable = btn.getAttribute('data-source-table') || 'food';
      const itemId = btn.getAttribute('data-id');
      const cartKey = `${sourceTable}-${itemId}`;
      let cart = JSON.parse(localStorage.getItem('cart')) || {};

      if (cart[cartKey]) {
        delete cart[cartKey];
        localStorage.setItem('cart', JSON.stringify(cart));
        if (typeof updateCartUI === 'function') updateCartUI();
      }
    });
  });
});
</script>

</body>
</html>
