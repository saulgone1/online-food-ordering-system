<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <style>
    /* Cart Button */
    #cart-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(45deg, #28a745, #218838);
      color: white;
      padding: 15px 20px;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      z-index: 9999;
      font-weight: bold;
      transition: transform 0.3s ease;
    }

    /* Cart Panel */
    #cart-panel {
  position: fixed;
  top: 0;
  right: 0;
  width: 350px;
  height: 100%;
  background: white;
  box-shadow: -2px 0 15px rgba(0,0,0,0.2);
  padding: 0 20px 20px 20px;
  display: flex;              /* Always flex, never 'none' */
  flex-direction: column;
  overflow-y: auto;
  z-index: 9999;
  transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.4s cubic-bezier(0.4,0,0.2,1);
  transform: translateX(100%);
  opacity: 0;
  pointer-events: none;       /* Prevent interaction when hidden */
}

#cart-panel.open {
  transform: translateX(0%);
  opacity: 1;
  pointer-events: auto;       /* Enable interaction when open */
}

    /* Sticky Cart Header */
    .cart-header {
  position: sticky;
  top: 0;
  background: white;
  z-index: 1;
  padding: 15px 0 10px 0; /* Adjust vertical padding */
  margin: 0; /* Remove margin */
  border-bottom: 1px solid #f1f1f1;
}

    #cart-panel h2 {
      margin: 0;
    }

    #cart-close-button {
      background: none;
      border: none;
      font-size: 30px;
      cursor: pointer;
      color: #666;
    }

    .cart-item {
      display: flex;
      flex-direction: column;
      border-bottom: 1px solid #f1f1f1;
      padding-bottom: 15px;
      margin-bottom: 15px;
    }

    .cart-item-top {
      display: flex;
      gap: 10px;
    }

    .cart-item-info {
      flex: 1;
      width: 100%;
    }

    .cart-item-title-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .cart-item-title {
      font-size: 16px;
      font-weight: bold;
      flex: 1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .remove-item {
      background: none;
      border: none;
      color: #dc3545;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      margin-left: 10px;
    }

    .cart-item-details-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 8px;
      width: 100%;
    }

    .cart-item-price {
      font-size: 14px;
      color: #555;
      flex: 1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .cart-item-actions {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .quantity-btn {
      padding: 5px 10px;
      border-radius: 5px;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      transition: background 0.2s;
    }

    .quantity-btn.add {
      background: #28a745;
    }

    .quantity-btn.add:hover {
      background: #218838;
    }

    .quantity-btn.subtract {
      background: #dc3545;
    }

    .quantity-btn.subtract:hover {
      background: #b52a37;
    }

    #cart-footer {
      font-weight: bold;
      font-size: 18px;
      margin-top: auto;
    }

    #cart-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    #empty-cart-button {
      background: #ffc107;
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      font-weight: bold;
      cursor: pointer;
    }

    #checkout-button {
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .cart-empty-message {
      color: #888;
      text-align: center;
      font-size: 18px;
      margin: 40px 0 30px 0;
      padding: 30px 0;
      border-radius: 8px;
      background: #f8f9fa;
      border: 1px dashed #e0e0e0;
    }

    /* For WebKit browsers (Chrome, Safari, Edge, Opera) */
#cart-panel::-webkit-scrollbar {
  width: 6px;               /* Thin scrollbar width */
}

#cart-panel::-webkit-scrollbar-track {
  background: transparent;  /* Transparent track for minimal look */
}

#cart-panel::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2); /* Light semi-transparent thumb */
  border-radius: 3px;                    /* Rounded edges */
}

#cart-panel::-webkit-scrollbar-thumb:hover {
  background-color: rgba(0, 0, 0, 0.4); /* Darker on hover */
}

/* For Firefox */
#cart-panel {
  scrollbar-width: thin;            /* Makes scrollbar thin */
  scrollbar-color: rgba(0,0,0,0.2) transparent; /* Thumb and track colors */
}

  </style>
</head>
<body>

<!-- Floating Cart Button -->
<div id="cart-button">🛒 Cart (<span id="cart-count">0</span>)</div>

<!-- Cart Panel -->
<div id="cart-panel">
  <div class="cart-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Your Cart</h2>
    <button id="cart-close-button" title="Close cart">&times;</button>
  </div>

  <div id="cart-items"></div>

  <div id="cart-footer">Total: ₹<span id="cart-total">0.00</span></div>

  <div id="cart-actions">
    <button id="empty-cart-button">Empty Cart</button>
    <button id="checkout-button">Proceed to Checkout</button>
  </div>
</div>

<script>
  function updateCartUI() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};

    const cartCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const cartTotal = Object.values(cart).reduce((sum, item) => sum + item.quantity * item.price, 0);

    document.getElementById('cart-count').textContent = cartCount;
    document.getElementById('cart-total').textContent = cartTotal.toFixed(2);

    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    if (Object.keys(cart).length === 0) {
      cartItemsContainer.innerHTML = `<div class="cart-empty-message">🛒 Your cart is empty!<br>Start adding some delicious items.</div>`;
      return;
    }

    for (const id in cart) {
      const item = cart[id];
      const div = document.createElement('div');
      div.className = 'cart-item';

      div.innerHTML = `
        <div class="cart-item-top">
          <img src="${item.img}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
          <div class="cart-item-info">
            <div class="cart-item-title-row">
              <span class="cart-item-title">${item.title}</span>
              <button class="remove-item" data-id="${id}">&times;</button>
            </div>
            <div class="cart-item-details-row">
              <span class="cart-item-price">₹${item.price} × ${item.quantity} = ₹${(item.quantity * item.price).toFixed(2)}</span>
              <div class="cart-item-actions">
                <button class="quantity-btn subtract" data-id="${id}">-</button>
                <button class="quantity-btn add" data-id="${id}">+</button>
              </div>
            </div>
          </div>
        </div>
      `;

      cartItemsContainer.appendChild(div);
    }

    document.querySelectorAll('.quantity-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const cart = JSON.parse(localStorage.getItem('cart')) || {};

        if (btn.classList.contains('add')) {
          cart[id].quantity++;
        } else {
          cart[id].quantity--;
          if (cart[id].quantity <= 0) {
            delete cart[id];
          }
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
      });
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const cart = JSON.parse(localStorage.getItem('cart')) || {};
        delete cart[id];
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    const cartPanel = document.getElementById('cart-panel');

    document.getElementById('cart-button').addEventListener('click', () => {
      cartPanel.classList.add('open');
    });

    document.getElementById('cart-close-button').addEventListener('click', () => {
      cartPanel.classList.remove('open');
    });

    document.getElementById('empty-cart-button').addEventListener('click', () => {
      localStorage.setItem('cart', JSON.stringify({}));
      updateCartUI();
    });

    updateCartUI();
  });

  document.getElementById('checkout-button').addEventListener('click', function() {
  const cart = JSON.parse(localStorage.getItem('cart')) || {};
  if (!cart || Object.keys(cart).length === 0) {
    alert("Your cart is empty!");
    return;
  }
  window.location.href = 'payment.php';
});

</script>

</body>
</html>
