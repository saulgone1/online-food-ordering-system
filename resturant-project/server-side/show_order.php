<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Orders";
$activePage = "orders";

ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}
// ---------------------------

include_once("connection.php");
$query = "SELECT * FROM `orders` ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!-- Font Awesome for action icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.order-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.order-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.order-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #ff980022;
    padding: 18px 4px 8px 4px;
    overflow-x: auto;
    max-width: 100%;
}
.order-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95em;
    min-width: 650px;
    table-layout: fixed;
}
.order-table th, .order-table td {
    padding: 8px 6px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.order-table th {
    background: linear-gradient(90deg, #ff9800 0%, #ffd54f 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
    font-size: 0.98em;
}
.order-table td.date-cell {
    min-width: 90px;
    font-size: 0.93em;
}
.order-table td.status-cell {
    min-width: 70px;
    font-size: 0.93em;
    font-weight: 600;
}
.order-table td.action-cell {
    min-width: 80px;
    white-space: nowrap;
}
.order-table tr:nth-child(even) {
    background: #f8fafc;
}
.order-table tr:hover {
    background: #fff8e1;
}
.order-table .action-btn {
    padding: 5px 8px;
    border-radius: 5px;
    border: none;
    font-size: 1.05em;
    cursor: pointer;
    background: #d32f2f;
    color: #fff;
    margin: 0 2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s, box-shadow 0.18s;
    box-shadow: 0 1px 4px #d32f2f22;
}
.order-table .action-btn.view {
    background:rgb(148, 199, 250);
}
.order-table .action-btn.view:hover {
    background: #125ea7;
}
.order-table .action-btn.delete:hover {
    background: #b71c1c;
}
.order-table .action-btn i {
    margin: 0;
    font-size: 1.1em;
}
@media (max-width: 900px) {
    .order-table { font-size: 0.90em; min-width: 480px;}
}
@media (max-width: 600px) {
    .order-table { font-size: 0.87em; min-width: 340px;}
    .order-table th:nth-child(3),
    .order-table td:nth-child(3), /* Name */
    .order-table th:nth-child(5),
    .order-table td:nth-child(5)  /* Order Date */
    { display: none; }
}
.order-table th.sn-col,
.order-table td.sn-col {
    width: 40px;
    min-width: 24px;
    max-width: 40px;
    padding-left: 0;
    padding-right: 0;
    text-align: center;
}

</style>

<div class="order-header">
    <h2>Orders</h2>
    <a href="dashboard.php" style="color:#ff9800;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="order-table-container">
    <table class="order-table">
        <thead>
            <tr>
            <th class="sn-col">S.No</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td class='sn-col'>{$i}</td>";
                echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td class='date-cell'>" . date('d M Y, H:i', strtotime($row['order_date'])) . "</td>";
                echo "<td>₹" . htmlspecialchars($row['total']) . "</td>";
                echo "<td class='status-cell'>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td class='action-cell'>
                        <a href='view_order.php?id={$row['id']}' title='View'>
        <button class='action-btn view'>👁</button></a>
                        <a href='delete_order.php?id={$row['id']}' onclick='return checkdelete();' title='Delete'>
                            <button class='action-btn delete'><i class='fa fa-trash'></i></button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='8' style='color:#888;font-weight:600;'>No order records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this order?');
}
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
