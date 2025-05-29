<?php
session_name('USERSESSID');
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    exit('<div style="padding:40px;color:#d32f2f;text-align:center;">Not logged in.</div>');
}
$user_id = $_SESSION['user_id'];
$orders = [];
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $orders[] = $row;
}
$orderItems = [];
if (!empty($orders)) {
    $orderIds = array_column($orders, 'id');
    $in = implode(',', array_map('intval', $orderIds));
    $sql2 = "SELECT * FROM order_items WHERE order_id IN ($in)";
    $res2 = $conn->query($sql2);
    while ($row = $res2->fetch_assoc()) {
        $orderItems[$row['order_id']][] = $row;
    }
}
?>

<style>
.orders-page-container {
  display: flex;
  flex-direction: row;
  min-height: 0;
  background: transparent;
}

.orders-list {
  flex: 1.5;
  background: var(--glass, #f8fafc);
  padding: 36px 0 36px 0;
  min-width: 340px;
  border-right: 1.5px solid var(--border, #e0e0e0);
  box-shadow: 0 2px 12px rgba(76,175,80,0.07);
  display: flex;
  flex-direction: column;
  align-items: stretch;
  max-height: calc(10 * 64px + 2*36px);
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #4caf50 #e0e0e0;
}
.orders-list::-webkit-scrollbar {
  width: 7px;
  background: #e0e0e0;
  border-radius: 6px;
}
.orders-list::-webkit-scrollbar-thumb {
  background: #4caf50;
  border-radius: 6px;
}
.orders-list::-webkit-scrollbar-thumb:hover {
  background: #388e3c;
}

.order-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0;
  padding: 18px 32px;
  border-radius: 12px;
  margin: 0 22px 16px 22px;
  background: rgba(255,255,255,0.95);
  box-shadow: 0 1px 8px rgba(60,60,60,0.05);
  border: 1.5px solid transparent;
  cursor: pointer;
  transition: box-shadow 0.18s, border-color 0.18s, transform 0.15s, background 0.18s;
  min-height: 64px;
  max-height: 64px;
}
.order-card.selected, .order-card:hover {
  border-color: var(--primary, #4CAF50);
  background: #f1f8e9;
  box-shadow: 0 3px 18px rgba(76,175,80,0.10);
  transform: translateY(-2px) scale(1.02);
}
.order-main {
  display: flex;
  align-items: center;
  gap: 16px;
  flex: 1;
  min-width: 0;
}
.order-img {
  width: 52px; height: 52px;
  border-radius: 7px;
  object-fit: cover;
  background: #eee;
  box-shadow: 0 1px 6px rgba(76,175,80,0.07);
  border: 1.5px solid #e0e0e0;
}
.order-info {
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.order-title {
  font-weight: 600;
  font-size: 1.09em;
  color: #222;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
}
.order-item-list {
  color: #444;
  font-size: 0.97em;
  margin-top: 2px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 220px;
}
.order-status-badge {
  background: #f1f8e9;
  color: var(--primary-dark, #388e3c);
  padding: 3px 13px;
  border-radius: 16px;
  font-size: 0.98em;
  font-weight: 600;
  margin: 0 14px;
  border: none;
  letter-spacing: 0.01em;
  min-width: 88px;
  text-align: center;
  box-shadow: 0 1px 4px rgba(76,175,80,0.04);
}
.order-status-delivered { background: #e8f5e9; color: #388e3c; }
.order-status-cancelled { background: #ffebee; color: #d32f2f; }
.order-status-pending, .order-status-out { background: #fffde7; color: #fbc02d; }
.order-status-processing { background: #e3f2fd; color: #1976d2; }
.order-status-other { background: #f3e5f5; color: #7b1fa2; }
.order-card.selected .order-status-badge,
.order-card:hover .order-status-badge {
  background: var(--primary, #4CAF50);
  color: #fff;
}
.order-card .order-title { max-width: 150px; }

/* Details panel: wider now */
.order-details-panel {
  flex: 1.2;
  background: rgba(255,255,255,0.97);
  min-width: 420px;   /* was 360px */
  max-width: 620px;   /* was 480px */
  padding: 38px 34px 34px 34px;
  box-shadow: 0 2px 18px rgba(76,175,80,0.06);
  display: flex;
  flex-direction: column;
  border-radius: 0 0 0 0;
  margin-left: 0;
  transition: box-shadow 0.3s;
  animation: fadeIn 0.5s;
  max-height: calc(10 * 64px + 2*36px);
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #4caf50 #e0e0e0;
}
.order-details-panel::-webkit-scrollbar {
  width: 7px;
  background: #e0e0e0;
  border-radius: 6px;
}
.order-details-panel::-webkit-scrollbar-thumb {
  background: #4caf50;
  border-radius: 6px;
}
.order-details-panel::-webkit-scrollbar-thumb:hover {
  background: #388e3c;
}
.order-details-panel h3 {
  color: var(--primary, #4CAF50);
  margin-bottom: 12px;
  font-size: 1.35em;
  font-weight: 700;
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
}
.order-info-block {
  font-size: 0.95em;
  color: #555;
  margin-bottom: 16px;
  line-height: 1.4;
}
.order-info-block span.label {
  font-weight: 600;
  color: #333;
  min-width: 90px;
  display: inline-block;
}
.badge {
  display: inline-block;
  padding: 3px 12px;
  border-radius: 14px;
  font-size: 0.95em;
  font-weight: 600;
  margin-left: 6px;
  white-space: nowrap;
}
.badge-delivered { background: #e8f5e9; color: #388e3c; }
.badge-cancelled { background: #ffebee; color: #d32f2f; }
.badge-pending, .badge-out { background: #fffde7; color: #fbc02d; }
.badge-processing { background: #e3f2fd; color: #1976d2; }
.badge-other { background: #f3e5f5; color: #7b1fa2; }
table.items-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
  font-size: 0.95em;
}
table.items-table th, table.items-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #ddd;
  text-align: left;
  vertical-align: middle;
}
table.items-table th {
  background-color: var(--accent, #f5f5f5);
  font-weight: 600;
  color: var(--primary-dark, #388e3c);
}
table.items-table td img {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  object-fit: cover;
  box-shadow: 0 1px 6px rgba(76,175,80,0.07);
}
table.items-table tr:last-child td { border-bottom: none; }
table.items-table tfoot td {
  font-weight: 700;
  font-size: 1.1em;
  border-top: 2px solid var(--primary, #4CAF50);
  color: var(--primary-dark, #388e3c);
}
table.items-table tfoot td:first-child { text-align: right; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px);}
  to { opacity: 1; transform: none;}
}
</style>

<div class="orders-page-container">
  <!-- Orders List -->
  <div class="orders-list" id="ordersList">
    <?php if (empty($orders)): ?>
      <div style="color:#aaa;text-align:center;padding:60px 0;">
        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="80" style="opacity:0.7;margin-bottom:12px;">
        <br>No orders yet!
      </div>
    <?php else: ?>
      <?php foreach ($orders as $idx => $order): ?>
        <?php
          $itemNames = [];
          $items = $orderItems[$order['id']] ?? [];
          foreach ($items as $it) {
            $itemNames[] = htmlspecialchars($it['item_title']) . ' x' . htmlspecialchars($it['quantity']);
          }
          $orderTitle = implode(', ', array_slice($itemNames, 0, 2));
          if (count($itemNames) > 2) $orderTitle .= ' +' . (count($itemNames)-2) . ' more';
          $orderDate = date('d-M-Y', strtotime($order['order_date']));
          $badgeClass = "order-status-other";
          $status = strtolower($order['status']);
          if ($status === "delivered") $badgeClass = "order-status-delivered";
          else if ($status === "processing") $badgeClass = "order-status-processing";
          else if ($status === "cancelled") $badgeClass = "order-status-cancelled";
          else if (strpos($status, "pending") !== false) $badgeClass = "order-status-pending";
          else if (strpos($status, "out") !== false) $badgeClass = "order-status-out";
        ?>
        <div class="order-card<?= $idx === 0 ? ' selected' : '' ?>" tabindex="0" data-order-id="<?= $order['id'] ?>">
          <div class="order-main">
            <img src="<?= htmlspecialchars($items[0]['item_image'] ?? 'https://via.placeholder.com/52') ?>" alt="Order" class="order-img">
            <div class="order-info">
              <span class="order-title"><?= $orderTitle ?></span>
              <span class="order-item-list"><?= $orderDate ?></span>
            </div>
          </div>
          <span class="order-status-badge <?= $badgeClass ?>"><?= htmlspecialchars($order['status']) ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <!-- Order Details Panel -->
  <div class="order-details-panel" id="orderDetailsPanel">
    <?php if (!empty($orders)): ?>
      <?php
        $order = $orders[0];
        $items = $orderItems[$order['id']] ?? [];
        $badgeClass = "badge-other";
        $status = strtolower($order['status']);
        if ($status === "delivered") $badgeClass = "badge-delivered";
        else if ($status === "processing") $badgeClass = "badge-processing";
        else if ($status === "cancelled") $badgeClass = "badge-cancelled";
        else if (strpos($status, "pending") !== false) $badgeClass = "badge-pending";
        else if (strpos($status, "out") !== false) $badgeClass = "badge-out";
        $total = 0;
      ?>
      <h3>Order Details <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($order['status']) ?></span></h3>
      <div class="order-info-block">
        <span class="label">Order #:</span> <?= htmlspecialchars($order['order_id']) ?><br>
        <span class="label">Date:</span> <?= date('d-M-Y', strtotime($order['order_date'])) ?><br>
        <span class="label">Payment:</span> <?= strtoupper(htmlspecialchars($order['payment_method'])) ?>
      </div>
      <div class="order-info-block">
        <span class="label">Name:</span> <?= htmlspecialchars($order['name']) ?><br>
        <span class="label">Address:</span> <?= htmlspecialchars($order['address']) ?><br>
        <span class="label">Phone:</span> <?= htmlspecialchars($order['phone']) ?><br>
        <span class="label">Email:</span> <?= htmlspecialchars($order['email']) ?>
      </div>
      <table class="items-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $i => $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
          ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><img src="<?= htmlspecialchars($item['item_image']) ?>" alt="<?= htmlspecialchars($item['item_title']) ?>" /></td>
              <td><?= htmlspecialchars($item['item_title']) ?></td>
              <td><?= htmlspecialchars($item['quantity']) ?></td>
              <td>₹<?= number_format($item['price'],2) ?></td>
              <td>₹<?= number_format($subtotal,2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" style="text-align:right;">Total:</td>
            <td>₹<?= number_format($total,2) ?></td>
          </tr>
        </tfoot>
      </table>
    <?php else: ?>
      <h3>Order Details</h3>
      <p style="color:#888;margin-top:12px;">Select an order to see the details</p>
    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.order-card').forEach(function(card) {
    card.addEventListener('click', function() {
      document.querySelectorAll('.order-card').forEach(el => el.classList.remove('selected'));
      card.classList.add('selected');
      var orderId = card.getAttribute('data-order-id');
      var panel = document.getElementById('orderDetailsPanel');
      panel.innerHTML = '<div style="color:#888;padding:40px 0;text-align:center;">Loading...</div>';
      fetch('order_details.php?order_id=' + encodeURIComponent(orderId))
        .then(resp => resp.text())
        .then(html => { panel.innerHTML = html; });
    });
  });
});
</script>
