<?php
session_name('USERSESSID');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'connection.php';

// Enable MySQLi exceptions for better debugging (recommended)[4][6]
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    die('<div style="color:red;text-align:center;">User not logged in.</div>');
}
$user_id = $_SESSION['user_id'];

try {
    // Fetch user info
    $stmt = $conn->prepare("SELECT name, email, phone, dob, address, image FROM user_signup WHERE ID=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) die('<div style="color:red;text-align:center;">User not found in database.</div>');

    // Fetch total orders
    $orderStmt = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE user_id=?");
    $orderStmt->bind_param("i", $user_id);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    $orderData = $orderResult->fetch_assoc();
    $total_orders = $orderData ? $orderData['total_orders'] : 0;

    // Fetch top 3 favourite foods from user's order history
    $favFoods = [];
    $favSql = "
    SELECT oi.item_title, COUNT(*) AS order_count
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY oi.item_title
    ORDER BY order_count DESC
    LIMIT 3
";
$favStmt = $conn->prepare($favSql);
$favStmt->bind_param("i", $user_id);
$favStmt->execute();
$favResult = $favStmt->get_result();
while ($row = $favResult->fetch_assoc()) {
    $favFoods[] = $row['item_title'];
}

    if (empty($favFoods)) {
        $favFoods = ["No orders yet"];
    }

    // Demo: Random member since date (last 2-5 years)
    $randYear = date("Y") - rand(2, 5);
    $randMonth = rand(1, 12);
    $memberSince = date("F Y", strtotime("$randYear-$randMonth-01"));

} catch (mysqli_sql_exception $e) {
    // Show a developer-friendly error, but not to end users in production[4][6]
    die('<div style="color:red;text-align:center;">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>');
}
?>

<style>
.profile-overview-cards {
  max-width: 900px;
  margin: 40px auto 0 auto;
  display: flex;
  flex-wrap: wrap;
  gap: 28px;
  justify-content: center;
}
.profile-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(76,175,80,0.09);
  padding: 28px 32px 24px 32px;
  min-width: 220px;
  max-width: 320px;
  flex: 1 1 240px;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: box-shadow 0.2s;
}
.profile-card:hover {
  box-shadow: 0 8px 32px rgba(76,175,80,0.13);
}
.profile-card .card-icon {
  font-size: 2.1em;
  margin-bottom: 12px;
  color: #388e3c;
  opacity: 0.85;
}
.profile-card .card-title {
  font-weight: 700;
  font-size: 1.11em;
  color: #333;
  margin-bottom: 8px;
  letter-spacing: 0.01em;
}
.profile-card .card-value {
  font-size: 1.13em;
  color: #222;
  margin-bottom: 2px;
  font-weight: 500;
}
.profile-card .fav-foods-list {
  margin: 0; padding: 0; list-style: none;
}
.profile-card .fav-foods-list li {
  font-size: 1.03em;
  color: #555;
  margin-bottom: 5px;
  padding-left: 0;
}
.profile-overview-pic {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 18px;
}
.profile-overview-pic img {
  width: 120px; height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 5px solid #4CAF50;
  box-shadow: 0 2px 16px rgba(76,175,80,0.13);
  margin-bottom: 12px;
}
.profile-overview-pic .profile-name {
  font-size: 1.35em;
  font-weight: 800;
  color: #388e3c;
  margin-bottom: 2px;
}
@media (max-width: 900px) {
  .profile-overview-cards { flex-direction: column; align-items: center; gap: 18px;}
  .profile-card { max-width: 98vw; min-width: 0; width: 98vw;}
}
</style>

<div class="profile-overview-pic">
  <img src="<?= $user['image'] ? '../server-side/imgsource/' . htmlspecialchars($user['image']) : 'https://i.pravatar.cc/100?img=5' ?>"
       alt="Profile">
  <div class="profile-name"><?= htmlspecialchars($user['name']) ?></div>
  <div style="color:#888;font-size:1.06em;"><?= htmlspecialchars($user['email']) ?></div>
</div>

<div class="profile-overview-cards">
  <div class="profile-card">
    <div class="card-icon">📱</div>
    <div class="card-title">Mobile Number</div>
    <div class="card-value"><?= htmlspecialchars($user['phone']) ?></div>
  </div>
  <div class="profile-card">
    <div class="card-icon">🎂</div>
    <div class="card-title">Date of Birth</div>
    <div class="card-value"><?= htmlspecialchars($user['dob']) ?></div>
  </div>
  <div class="profile-card">
    <div class="card-icon">🏠</div>
    <div class="card-title">Address</div>
    <div class="card-value"><?= htmlspecialchars($user['address']) ?></div>
  </div>
  <div class="profile-card">
    <div class="card-icon">🗓️</div>
    <div class="card-title">Member Since</div>
    <div class="card-value"><?= $memberSince ?></div>
  </div>
  <div class="profile-card">
    <div class="card-icon">📦</div>
    <div class="card-title">Total Orders</div>
    <div class="card-value"><?= $total_orders ?></div>
  </div>
  <div class="profile-card">
    <div class="card-icon">🍕</div>
    <div class="card-title">Favourite Foods</div>
    <ul class="fav-foods-list">
      <?php foreach($favFoods as $food): ?>
        <li><?= htmlspecialchars($food) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
