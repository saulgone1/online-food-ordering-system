<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Ice Cream List";
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
include_once("connection.php");
$query = "SELECT * FROM `icecream`";
$result = mysqli_query($conn, $query);
?>
<style>
.icecream-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.icecream-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.icecream-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #e91e6322;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.icecream-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 650px;
}
.icecream-table th, .icecream-table td {
    padding: 10px 12px;
    text-align: center;
}
.icecream-table th {
    background: linear-gradient(90deg, #e91e63 0%, #f06292 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.icecream-table tr {
    transition: background 0.15s;
}
.icecream-table tr:nth-child(even) {
    background: #f8fafc;
}
.icecream-table tr:hover {
    background: #fce4ec;
}
.icecream-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.icecream-table td:last-child {
    min-width: 90px;
}
.icecream-table img {
    border-radius: 12px;
    box-shadow: 0 2px 8px #e91e6333;
    width: 80px;
    height: 80px;
    object-fit: cover;
    background: #eee;
}
.icecream-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.icecream-actions a {
    text-decoration: none;
}
.icecream-actions .add-btn {
    background: linear-gradient(90deg, #e91e63 0%, #f06292 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #e91e6322;
    cursor: pointer;
    transition: background 0.18s;
}
.icecream-actions .add-btn:hover {
    background: #e91e63;
}
.icecream-table .action-btn {
    padding: 5px 16px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    font-size: 1em;
    cursor: pointer;
    background: #d32f2f;
    color: #fff;
    transition: background 0.18s, box-shadow 0.18s;
    box-shadow: 0 1px 4px #d32f2f22;
}
.icecream-table .action-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .icecream-table-container { padding: 10px 2px;}
    .icecream-table { font-size: 0.97em; }
}
</style>

<div class="icecream-header">
    <h2>Ice Cream Items</h2>
    <a href="dashboard.php" style="color:#e91e63;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="icecream-actions">
    <a href="showicecream.php"><button class="add-btn" style="background: #1976D2;">Show All</button></a>
    <a href="servericecream.php"><button class="add-btn">Add Ice Cream</button></a>
</div>

<div class="icecream-table-container">
    <table class="icecream-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>";
                if (!empty($row['imgsource'])) {
                    $imgSrc = "http://localhost/php_training/resturant-project/server-side/imgsource/{$row['imgsource']}";
                    echo "<img src='{$imgSrc}' alt='Ice Cream Image' />";
                } else {
                    echo "<span style='color:#aaa;'>No Image</span>";
                }
                echo "</td>";
                echo "<td>
                        <a href='delete_icecream.php?id={$row['id']}' onclick='return checkdelete();'>
                            <button class='action-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='5' style='color:#888;font-weight:600;'>No ice cream records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this ice cream item?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
