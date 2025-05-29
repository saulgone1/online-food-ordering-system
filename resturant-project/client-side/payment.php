<?php
session_name('USERSESSID');
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login or show an error
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout - Payment</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f6f8fa;
      margin: 0;
      padding: 0;
    }
    .checkout-container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
      display: flex;
      flex-wrap: wrap;
      overflow: hidden;
      align-items: stretch;
    }
    .checkout-summary {
      flex: 1 1 350px;
      background: linear-gradient(135deg, #e3f0ff 0%, #f4f7fb 100%);
      padding: 32px 24px;
      min-width: 320px;
      border-right: 1px solid #e3e6ee;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }
    .cart-list {
      flex: 1 1 auto;
      list-style: none;
      padding: 0;
      margin: 0 0 16px 0;
      max-height: 400px;
      overflow-y: auto;
      border-radius: 10px;
      background: #f8fafc;
      box-shadow: 0 1px 2px rgba(0,0,0,0.03);
      scrollbar-width: thin;
      scrollbar-color: #c5d3e6 #f8fafc;
      animation: cartListFadeIn 0.8s;
    }
    @keyframes cartListFadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to   { opacity: 1; transform: translateY(0);}
    }
    .cart-list li {
      display: flex;
      align-items: center;
      margin-bottom: 18px;
      border-bottom: 1px solid #e3e6ee;
      padding-bottom: 12px;
      opacity: 0;
      animation: fadeInItem 0.7s forwards;
    }
    .cart-list li:nth-child(1) { animation-delay: 0.1s; }
    .cart-list li:nth-child(2) { animation-delay: 0.2s; }
    .cart-list li:nth-child(3) { animation-delay: 0.3s; }
    .cart-list li:nth-child(4) { animation-delay: 0.4s; }
    .cart-list li:nth-child(5) { animation-delay: 0.5s; }
    .cart-list li:nth-child(6) { animation-delay: 0.6s; }
    @keyframes fadeInItem {
      from { opacity: 0; transform: translateY(20px);}
      to   { opacity: 1; transform: translateY(0);}
    }
    .cart-list img {
      width: 54px;
      height: 54px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      transition: transform 0.2s;
    }
    .cart-list img:hover {
      transform: scale(1.08) rotate(-2deg);
    }
    .cart-item-info {
      flex: 1;
    }
    .cart-item-title {
      font-weight: 600;
      color: #222;
      letter-spacing: 0.02em;
    }
    .cart-item-qty {
      color: #666;
      font-size: 0.95em;
    }
    .cart-item-price {
      font-weight: 500;
      color: #0a8f08;
      min-width: 70px;
      text-align: right;
    }
    .order-total {
      font-size: 1.2em;
      font-weight: 600;
      margin: 10px 0 0 0;
      color: #333;
      text-align: right;
      border-top: 2px dashed #b7d2f7;
      padding-top: 10px;
      background: #eaf4fe;
      border-radius: 0 0 10px 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.02);
      animation: fadeInItem 0.8s 0.3s backwards;
    }
    .checkout-form {
      flex: 2 1 400px;
      padding: 32px 32px 32px 24px;
      min-width: 320px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      animation: fadeInItem 0.8s 0.2s backwards;
    }
    h2 {
      margin-top: 0;
      color: #333;
      letter-spacing: 0.03em;
    }
    .form-group {
      margin-bottom: 18px;
      animation: fadeInItem 0.7s backwards;
    }
    label {
      display: block;
      font-size: 1em;
      color: #333;
      margin-bottom: 6px;
      letter-spacing: 0.01em;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #d2d7e0;
      border-radius: 6px;
      font-size: 1em;
      background: #f9fafb;
      transition: border 0.2s, box-shadow 0.2s;
    }
    input:focus, select:focus {
      border-color: #0070f3;
      box-shadow: 0 0 0 2px #cce5ff;
      outline: none;
    }
    .payment-options {
      display: flex;
      gap: 16px;
      margin-bottom: 20px;
    }
    .payment-option {
      flex: 1;
      padding: 14px 0;
      border: 1.5px solid #d2d7e0;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      background: #fff;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
      font-weight: 500;
      font-size: 1em;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
      user-select: none;
      position: relative;
      overflow: hidden;
    }
    .payment-option.selected {
      border-color: #0070f3;
      background: #eaf4fe;
      box-shadow: 0 2px 12px #a0d2ff44;
    }
    .payment-option:active::after {
      content: "";
      position: absolute;
      left: 50%; top: 50%;
      width: 120%; height: 120%;
      background: rgba(0,112,243,0.13);
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0.9);
      animation: ripple 0.4s linear;
      pointer-events: none;
    }
    @keyframes ripple {
      to { transform: translate(-50%, -50%) scale(1.5); opacity: 0;}
    }
    .checkout-btn {
      width: 100%;
      background: linear-gradient(90deg,#0070f3 60%,#00b8a9 100%);
      color: #fff;
      border: none;
      padding: 16px;
      border-radius: 8px;
      font-size: 1.1em;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.25s, box-shadow 0.2s;
      box-shadow: 0 2px 12px #0070f355;
      position: relative;
      overflow: hidden;
    }
    .checkout-btn:active::after {
      content: "";
      position: absolute;
      left: 50%; top: 50%;
      width: 120%; height: 120%;
      background: rgba(0,112,243,0.13);
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0.9);
      animation: ripple 0.4s linear;
      pointer-events: none;
    }
    .checkout-btn:hover {
      background: linear-gradient(90deg,#005bb5 60%,#009e8f 100%);
      box-shadow: 0 4px 24px #0070f344;
    }
    @media (max-width: 900px) {
      .checkout-container {
        flex-direction: column;
        max-width: 98vw;
      }
      .checkout-summary, .checkout-form {
        border-right: none;
        min-width: 0;
        padding: 24px;
      }
      .cart-list {
        max-height: 200px;
      }
    }
    /* Custom scrollbar for cart list */
    .cart-list::-webkit-scrollbar {
      width: 7px;
    }
    .cart-list::-webkit-scrollbar-thumb {
      background: #c5d3e6;
      border-radius: 4px;
    }
    .cart-list::-webkit-scrollbar-track {
      background: #f8fafc;
    }
  </style>
</head>
<body>
  <div class="checkout-container">
    <!-- Order Summary -->
    <div class="checkout-summary">
      <h2>Your Order</h2>
      <ul class="cart-list" id="checkout-cart-list">
        <!-- Cart items will be injected here -->
      </ul>
      <div class="order-total">Total: ₹<span id="checkout-order-total">0.00</span></div>
    </div>
    <!-- Checkout Form -->
    <form class="checkout-form" id="payment-form" autocomplete="on">
      <h2>Delivery & Payment</h2>
      <div class="form-group">
        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="fullname" required autocomplete="name" />
      </div>
      <div class="form-group">
        <label for="address">Delivery Address</label>
        <input type="text" id="address" name="address" required autocomplete="street-address" />
      </div>
      <div class="form-group">
        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10,13}" required autocomplete="tel" />
      </div>
      <div class="form-group">
        <label for="email">Email (for receipt)</label>
        <input type="email" id="email" name="email" required autocomplete="email" />
      </div>
      <div class="form-group">
        <label>Payment Method</label>
        <div class="payment-options" id="payment-options">
          <div class="payment-option selected" data-method="cod">Cash on Delivery</div>
          <div class="payment-option" data-method="card">Credit/Debit Card</div>
          <div class="payment-option" data-method="upi">UPI</div>
        </div>
      </div>
      <button type="submit" class="checkout-btn" id="place-order-btn">Place Order</button>
      <div id="error-msg" style="color: #c00; margin-top: 12px; min-height: 24px; text-align:center;"></div>
    </form>
  </div>
  <script>

// --- Cart rendering for summary ---
function renderCheckoutCart() {
  const cartList = document.getElementById('checkout-cart-list');
  const orderTotal = document.getElementById('checkout-order-total');
  let cart = JSON.parse(localStorage.getItem('cart')) || {};
  let total = 0;
  cartList.innerHTML = '';
  let i = 1;
  Object.keys(cart).forEach(key => {
    const item = cart[key];
    total += item.price * item.quantity;
    cartList.innerHTML += `
      <li style="animation-delay:${i * 0.1}s;">
        <img src="${item.img}" alt="${item.title}" />
        <div class="cart-item-info">
          <div class="cart-item-title">${item.title}</div>
          <div class="cart-item-qty">Qty: ${item.quantity}</div>
        </div>
        <div class="cart-item-price">₹${(item.price * item.quantity).toFixed(2)}</div>
      </li>
    `;
    i++;
  });
  orderTotal.textContent = total.toFixed(2);
}
renderCheckoutCart();

// --- Payment method selection ---
document.querySelectorAll('.payment-option').forEach(option => {
  option.addEventListener('click', function() {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    this.classList.add('selected');
  });
});

// --- Handle form submission ---
document.getElementById('payment-form').addEventListener('submit', function(e) {
  e.preventDefault();
  document.getElementById('error-msg').textContent = ""; // Clear previous errors

  // Collect form data
  const name = document.getElementById('fullname').value.trim();
  const address = document.getElementById('address').value.trim();
  const mobile = document.getElementById('mobile').value.trim();
  const email = document.getElementById('email').value.trim();
  const paymentOption = document.querySelector('.payment-option.selected');
  const paymentMethod = paymentOption ? paymentOption.dataset.method : "cod";
  const cart = JSON.parse(localStorage.getItem('cart')) || {};

  // Validation
  if (!name || !address || !mobile || !email) {
    document.getElementById('error-msg').textContent = "Please fill all fields.";
    return;
  }
  if (Object.keys(cart).length === 0) {
    document.getElementById('error-msg').textContent = "Your cart is empty!";
    return;
  }
  if (!/^\d{10,}$/.test(mobile)) {
    document.getElementById('error-msg').textContent = "Please enter a valid phone number.";
    return;
  }

  // Calculate total
  let total = 0;
  Object.values(cart).forEach(item => {
    total += item.price * item.quantity;
  });

  // Disable button to prevent double submit
  document.getElementById('place-order-btn').disabled = true;

  // Send order to backend
  fetch('save_order.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      name, address, mobile, email, paymentMethod, cart, total
    })
  })
  .then(res => res.json())
  .then(data => {
    console.log("Order response:", data);
    if (data.success) {
      localStorage.removeItem('cart'); // Clear cart after successful order
      window.location.href = 'profile_dashboard.php?page=orders';// Redirect to dashboard orders page
    } else {
      document.getElementById('error-msg').textContent = data.message || "Order failed, please try again.";
      document.getElementById('place-order-btn').disabled = false;
    }
  })
  .catch(() => {
    document.getElementById('error-msg').textContent = "Order failed, please try again.";
    document.getElementById('place-order-btn').disabled = false;
  });
});

  </script>
</body>
</html>
