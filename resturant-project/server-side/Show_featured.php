<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Featured Food";
$activePage = "featured";

ob_start();
?>

<!-- Your HTML, CSS, PHP goes here -->
<?php
include_once("connection.php");
$query = "SELECT * FROM `featured_food`";
$result = mysqli_query($conn, $query);
?>
<style>
.featured-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.featured-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.featured-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #8e24aa22;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.featured-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 900px;
}
.featured-table th, .featured-table td {
    padding: 10px 12px;
    text-align: center;
}
.featured-table th {
    background: linear-gradient(90deg, #8e24aa 0%, #ba68c8 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.featured-table tr {
    transition: background 0.15s;
}
.featured-table tr:nth-child(even) {
    background: #f8fafc;
}
.featured-table tr:hover {
    background: #f3e5f5;
}
.featured-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.featured-table td:last-child {
    min-width: 90px;
}
.featured-table img {
    border-radius: 12px;
    box-shadow: 0 2px 8px #8e24aa33;
    width: 80px;
    height: 80px;
    object-fit: cover;
    background: #eee;
}
.featured-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.featured-actions a {
    text-decoration: none;
}
.featured-actions .add-btn {
    background: linear-gradient(90deg, #8e24aa 0%, #ba68c8 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #8e24aa22;
    cursor: pointer;
    transition: background 0.18s;
}
.featured-actions .add-btn:hover {
    background: #8e24aa;
}
.featured-table .action-btn {
    padding: 5px 14px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    font-size: 1em;
    cursor: pointer;
    color: #fff;
    transition: background 0.18s, box-shadow 0.18s;
    box-shadow: 0 1px 4px #8e24aa22;
}
.featured-table .update-btn {
    background: #43a047;
    margin-right: 6px;
}
.featured-table .update-btn:hover {
    background: #2e7031;
}
.featured-table .delete-btn {
    background: #d32f2f;
}
.featured-table .delete-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .featured-table-container { padding: 10px 2px;}
    .featured-table { font-size: 0.97em; min-width: 600px;}
}
</style>

<div class="featured-header">
    <h2>Featured Food Items</h2>
    <a href="dashboard.php" style="color:#8e24aa;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="featured-actions">
    <a href="Show_featured.php"><button class="add-btn" style="background: #1976D2;">Show All</button></a>
    <a href="add_featured.php"><button class="add-btn">Add Featured</button></a>
</div>

<div class="featured-table-container">
    <table class="featured-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th colspan="2">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>";
                if (!empty($row['imgsource'])) {
                    $imgSrc = "http://localhost/php_training/resturant-project/server-side/imgsource/{$row['imgsource']}";
                    echo "<img src='{$imgSrc}' alt='Featured Image' />";
                } else {
                    echo "<span style='color:#aaa;'>No Image</span>";
                }
                echo "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                echo "<td>
                        <a href='update_featured.php?id={$row['id']}' onclick='return checkupdate();'>
                            <button class='action-btn update-btn'>Update</button>
                        </a>
                      </td>";
                echo "<td>
                        <a href='delete_featured.php?id={$row['id']}' onclick='return checkdelete();'>
                            <button class='action-btn delete-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='8' style='color:#888;font-weight:600;'>No featured food records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this featured item?');
}
function checkupdate(){
    return window.confirm('Are you sure you want to update this featured item?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
