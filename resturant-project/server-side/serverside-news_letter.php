<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Newsletter";
$activePage = "newsletter";

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
$query = "SELECT * FROM `news_letter`";
$result = mysqli_query($conn, $query);
?>
<style>
.newsletter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.newsletter-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.newsletter-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #0288d122;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.newsletter-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 450px;
}
.newsletter-table th, .newsletter-table td {
    padding: 10px 12px;
    text-align: center;
}
.newsletter-table th {
    background: linear-gradient(90deg, #0288d1 0%, #4fc3f7 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.newsletter-table tr {
    transition: background 0.15s;
}
.newsletter-table tr:nth-child(even) {
    background: #f8fafc;
}
.newsletter-table tr:hover {
    background: #e1f5fe;
}
.newsletter-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.newsletter-table td:last-child {
    min-width: 90px;
}
.newsletter-table .action-btn {
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
.newsletter-table .action-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .newsletter-table-container { padding: 10px 2px;}
    .newsletter-table { font-size: 0.97em; }
}
</style>

<div class="newsletter-header">
    <h2>Newsletter Subscribers</h2>
    <a href="dashboard.php" style="color:#0288d1;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="newsletter-table-container">
    <table class="newsletter-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Email Address</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>
                        <a href='delete_newsletter.php?id={$row['ID']}' onclick='return checkdelete();'>
                            <button class='action-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='3' style='color:#888;font-weight:600;'>No newsletter records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this newsletter subscriber?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
