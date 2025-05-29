<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Order Details";
$activePage = "orders";

ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}

include_once("connection.php");

// Get order ID from the URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$order_id) {
    echo "<div style='color:#d32f2f;font-weight:600;'>Invalid order ID.</div>";
    exit;
}

// Fetch order
$query = "SELECT * FROM `orders` WHERE id = $order_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div style='color:#d32f2f;font-weight:600;'>Order not found.</div>";
    exit;
}
$order = mysqli_fetch_assoc($result);

// Fetch order items for this order
$items_query = "SELECT * FROM order_items WHERE order_id = {$order['id']}";
$items_result = mysqli_query($conn, $items_query);
$order_items = [];
if ($items_result) {
    while ($item = mysqli_fetch_assoc($items_result)) {
        $order_items[] = $item;
    }
}
?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.order-details-container {
    max-width: 950px;
    margin: 40px auto 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 18px #ff980022;
    padding: 38px 48px 36px 48px;
    font-size: 1.08em;
}
.order-details-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.order-details-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.order-details-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 36px;
    list-style: none;
    padding: 0;
    margin: 0 0 18px 0;
}
.order-details-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f2f2f2;
}
.order-details-list li:nth-child(even) {
    border-left: 1px solid #f2f2f2;
    padding-left: 18px;
}
.order-details-list li:last-child,
.order-details-list li:nth-last-child(2) {
    border-bottom: none;
}
.order-details-label {
    color: #888;
    font-weight: 600;
    min-width: 120px;
}
.order-details-value {
    color: #222;
    font-weight: 500;
    text-align: right;
    max-width: 320px;
    word-break: break-word;
}
.order-status {
    display: inline-block;
    padding: 3px 14px;
    border-radius: 12px;
    background: #ffecb3;
    color: #b26a00;
    font-weight: 700;
    font-size: 0.98em;
    margin-left: 6px;
}
.order-details-actions {
    margin-top: 24px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.order-details-actions .action-btn {
    padding: 7px 22px;
    border-radius: 6px;
    border: none;
    font-size: 1em;
    font-weight: 600;
    cursor: pointer;
    background: #1976d2;
    color: #fff;
    transition: background 0.18s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.order-details-actions .action-btn.delete {
    background: #d32f2f;
}
.order-details-actions .action-btn.delete:hover {
    background: #b71c1c;
}
.order-details-actions .action-btn:hover {
    background: #125ea7;
}

/* Order Items Table - now INSIDE the main container */
.order-items-section {
    margin-top: 36px;
}
.order-items-section h3 {
    margin: 0 0 18px 0;
    color: #222;
    font-size: 1.18em;
    font-weight: 700;
    display: flex;
    align-items: center;
}
.order-items-section h3 i {
    color: #ff9800;
    margin-right: 8px;
}
.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
    font-size: 1em;
}
.items-table th, .items-table td {
    padding: 10px 8px;
    text-align: left;
}
.items-table th {
    background: #f8f9fa;
    color: #666;
    font-weight: 700;
    border-bottom: 2px solid #eee;
}
.items-table tr:nth-child(even) {
    background: #fafbfc;
}
.items-table td {
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}
.item-image {
    width: 54px;
    height: 54px;
    object-fit: cover;
    border-radius: 7px;
    background: #f3f3f3;
}
.item-title {
    font-weight: 600;
    color: #333;
}
.item-price, .item-subtotal {
    color: #222;
    font-weight: 500;
    text-align: right;
}
.item-quantity {
    text-align: center;
    color: #444;
}
.no-items {
    color: #888;
    font-weight: 600;
    text-align: center;
    padding: 20px 0;
}
@media (max-width: 900px) {
    .order-details-container {
        max-width: 98vw;
        padding: 20px 6vw 16px 6vw;
        font-size: 1em;
    }
    .order-details-list {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .order-details-list li:nth-child(even) {
        border-left: none;
        padding-left: 0;
    }
}
@media (max-width: 600px) {
    .order-details-container {
        padding: 12px 2vw 8px 2vw;
        font-size: 0.98em;
    }
    .order-details-label { min-width: 80px;}
    .order-details-value { max-width: 130px;}
}
</style>

<div class="order-details-container">
    <div class="order-details-header">
        <h2>
            <i class="fa fa-file-text-o" style="color:#ff9800;margin-right:7px;"></i>
            Order Details
        </h2>
        <a href="show_order.php" style="color:#ff9800;font-weight:600;text-decoration:none;font-size:1em;">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
    <ul class="order-details-list">
        <li>
            <span class="order-details-label">Order ID</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['order_id']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Order Date</span>
            <span class="order-details-value"><?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></span>
        </li>
        <li>
            <span class="order-details-label">Status</span>
            <span class="order-details-value">
                <span class="order-status"><?php echo htmlspecialchars($order['status']); ?></span>
            </span>
        </li>
        <li>
            <span class="order-details-label">Payment</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['payment_method']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Customer Name</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['name']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Email</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['email']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Phone</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['phone']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Address</span>
            <span class="order-details-value"><?php echo htmlspecialchars($order['address']); ?></span>
        </li>
        <li>
            <span class="order-details-label">Total</span>
            <span class="order-details-value">₹<?php echo htmlspecialchars($order['total']); ?></span>
        </li>
    </ul>
    <div class="order-details-actions">
        <a href="delete_order.php?id=<?php echo $order['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" title="Delete">
            <button class="action-btn delete"><i class="fa fa-trash"></i> Delete</button>
        </a>
    </div>

    <!-- ORDER ITEMS SECTION INSIDE THE SAME CONTAINER -->
    <div class="order-items-section">
        <h3>
            <i class="fa fa-list-alt"></i>
            Order Items
        </h3>
        <?php if (!empty($order_items)): ?>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($item['item_image']); ?>"
                             class="item-image"
                             alt="<?php echo htmlspecialchars($item['item_title']); ?>">
                    </td>
                    <td class="item-title"><?php echo htmlspecialchars($item['item_title']); ?></td>
                    <td class="item-price">₹<?php echo number_format($item['price'], 2); ?></td>
                    <td class="item-quantity"><?php echo $item['quantity']; ?></td>
                    <td class="item-subtotal">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="no-items">No items found in this order.</div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
