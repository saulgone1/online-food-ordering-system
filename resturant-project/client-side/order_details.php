<?php
session_name('USERSESSID');
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    http_response_code(403);
    exit;
}
$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<h3>Order Details</h3><p style='color:#888;margin-top:12px;'>Order not found.</p>";
    exit;
}

// Fetch items
$orderItems = [];
$stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    $orderItems[] = $row;
}

// Render details
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
    <?php foreach ($orderItems as $i => $item):
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
