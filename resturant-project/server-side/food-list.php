<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Food Item List";
$activePage = "fooditems";
ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    // Not logged in, set error message and redirect
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}
// ---------------------------
?>

<!-- Your HTML, CSS, PHP goes here -->
<?php
$conn = mysqli_connect("localhost", "root", "", "resturant_project");
function get_total($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM `$table`");
    if ($row = mysqli_fetch_assoc($result)) return $row['total'];
    return 0;
}
?>
<style>
.food-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.food-list-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.food-list-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 28px;
    margin-top: 28px;
}
.food-list-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #43e97b18;
    padding: 28px 18px 22px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: box-shadow 0.18s, transform 0.16s;
    min-height: 170px;
    position: relative;
}
.food-list-card:hover {
    box-shadow: 0 6px 24px #43e97b33;
    transform: translateY(-2px) scale(1.025);
}
.food-list-card .icon {
    font-size: 2.5em;
    margin-bottom: 10px;
    border-radius: 10px;
    padding: 10px 14px;
    color: #fff;
    box-shadow: 0 2px 8px #43e97b22;
}
.food-list-card .dosa { background: linear-gradient(120deg, #388E3C 60%, #81C784 100%);}
.food-list-card .pizza { background: linear-gradient(120deg, #FF9800 60%, #FFD54F 100%);}
.food-list-card .cake { background: linear-gradient(120deg, #E57373 60%, #FFD1DC 100%);}
.food-list-card .icecream { background: linear-gradient(120deg, #E91E63 60%, #F06292 100%);}
.food-list-card .title {
    font-size: 1.15em;
    font-weight: 700;
    margin-bottom: 2px;
    color: #333;
    letter-spacing: 0.01em;
    opacity: 0.91;
}
.food-list-card .count {
    font-size: 2em;
    font-weight: 800;
    color: #43a047;
    margin-bottom: 0;
    line-height: 1.1;
}
.food-list-card .view-btn {
    margin-top: 14px;
    display: inline-block;
    padding: 7px 20px;
    border-radius: 7px;
    background: #43a047;
    color: #fff;
    font-size: 1em;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.18s;
    opacity: 0.92;
    box-shadow: 0 1px 6px #43a04722;
}
.food-list-card .view-btn:hover {
    background: #388e3c;
    opacity: 1;
}
@media (max-width: 900px) {
    .food-list-cards { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;}
}
</style>

<div class="food-list-header">
    <h2>Food Items Overview</h2>
</div>

<div class="food-list-cards">
    <div class="food-list-card">
        <div class="icon dosa">🥞</div>
        <div class="title">Dosa</div>
        <div class="count"><?= get_total($conn, "dosa") ?></div>
        <a class="view-btn" href="showdosa.php">View Dosa</a>
    </div>
    <div class="food-list-card">
        <div class="icon pizza">🍕</div>
        <div class="title">Pizza</div>
        <div class="count"><?= get_total($conn, "pizza") ?></div>
        <a class="view-btn" href="showpizza.php">View Pizza</a>
    </div>
    <div class="food-list-card">
        <div class="icon cake">🍰</div>
        <div class="title">Cake</div>
        <div class="count"><?= get_total($conn, "cake") ?></div>
        <a class="view-btn" href="showcake.php">View Cake</a>
    </div>
    <div class="food-list-card">
        <div class="icon icecream">🍦</div>
        <div class="title">Ice Cream</div>
        <div class="count"><?= get_total($conn, "icecream") ?></div>
        <a class="view-btn" href="showicecream.php">View Ice Cream</a>
    </div>
</div>
<?php mysqli_close($conn); ?>


<?php
$content = ob_get_clean();
include "layout.php";
?>
