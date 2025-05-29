<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Dashboard";
$activePage = "dashboard";
ob_start(); // Start output buffering

// Prevent browser caching of sensitive pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    // Not logged in, show error or redirect
    header("Location: login.php");
    exit;
}
// ---------------------------

// Database connection
$conn = mysqli_connect("localhost", "root", "", "resturant_project");
function get_total($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM `$table`");
    if ($row = mysqli_fetch_assoc($result)) return $row['total'];
    return 0;
}
?>
<style>
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 24px;
    margin-top: 28px;
}
.dashboard-card {
    background: linear-gradient(135deg, #f8fafc 60%, #e8f5e9 100%);
    border-radius: 16px;
    box-shadow: 0 1.5px 8px #43e97b18;
    padding: 20px 18px 16px 18px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    transition: box-shadow 0.18s, transform 0.16s;
    position: relative;
    min-height: 110px;
}
.dashboard-card:hover {
    box-shadow: 0 6px 24px #43e97b33;
    transform: translateY(-2px) scale(1.025);
}
.dashboard-card .icon {
    font-size: 1.9em;
    margin-bottom: 8px;
    border-radius: 10px;
    padding: 7px 10px;
    color: #fff;
    background: linear-gradient(120deg, #4CAF50 60%, #38f9d7 100%);
    box-shadow: 0 2px 8px #43e97b22;
    margin-right: 0;
}
.dashboard-card .title {
    font-size: 1.01em;
    font-weight: 700;
    margin-bottom: 2px;
    color: #333;
    letter-spacing: 0.01em;
    opacity: 0.91;
}
.dashboard-card .count {
    font-size: 1.7em;
    font-weight: 800;
    color: #43a047;
    margin-bottom: 0;
    line-height: 1.1;
}
.dashboard-card a {
    margin-top: 8px;
    display: inline-block;
    padding: 4px 13px;
    border-radius: 7px;
    background: #43a047;
    color: #fff;
    font-size: 0.95em;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.18s;
    opacity: 0.92;
}
.dashboard-card a:hover {
    background: #388e3c;
    opacity: 1;
}
@media (max-width: 900px) {
    .dashboard-cards { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px;}
}
</style>

<h1 style="margin-top:0; font-size:1.45em; font-weight:800;">
    Welcome, <span style="color:#4CAF50"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
</h1>
<h2 style="margin-bottom:10px;font-size:1.15em;font-weight:600;opacity:0.7;">Dashboard Overview</h2>
<div class="dashboard-cards">
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#FFA000,#FFD54F);">📇</div>
        <div class="title">Contacts</div>
        <div class="count"><?= get_total($conn, "contact") ?></div>
        <a href="show_contact.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#1976D2,#64b5f6);">🍕</div>
        <div class="title">Pizza</div>
        <div class="count"><?= get_total($conn, "pizza") ?></div>
        <a href="showpizza.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#388E3C,#81C784);">🥞</div>
        <div class="title">Dosa</div>
        <div class="count"><?= get_total($conn, "dosa") ?></div>
        <a href="showdosa.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#D32F2F,#e57373);">🍰</div>
        <div class="title">Cake</div>
        <div class="count"><?= get_total($conn, "cake") ?></div>
        <a href="showcake.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#8E24AA,#ba68c8);">✨</div>
        <div class="title">Featured Food</div>
        <div class="count"><?= get_total($conn, "featured_food") ?></div>
        <a href="Show_featured.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#0288D1,#4fc3f7);">📰</div>
        <div class="title">Newsletter</div>
        <div class="count"><?= get_total($conn, "news_letter") ?></div>
        <a href="serverside-news_letter.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#FBC02D,#ffe082);">👥</div>
        <div class="title">Team Members</div>
        <div class="count"><?= get_total($conn, "teammembers") ?></div>
        <a href="showteam_members.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#FF9800,#ffb74d);">🧾</div>
        <div class="title">Orders</div>
        <div class="count"><?= get_total($conn, "orders") ?></div>
        <a href="show_order.php">View</a>
    </div>
    <div class="dashboard-card">
        <div class="icon" style="background:linear-gradient(120deg,#E91E63,#f06292);">🍦</div>
        <div class="title">Ice Cream</div>
        <div class="count"><?= get_total($conn, "icecream") ?></div>
        <a href="showicecream.php">View</a>
    </div>
</div>
<?php mysqli_close($conn); ?>

<?php
$content = ob_get_clean(); // End output buffering, store output in $content
include "layout.php";      // Now include the layout, which will echo $content
?>
