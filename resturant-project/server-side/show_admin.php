<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Admin List";
$activePage = "profile";

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
$query = "SELECT * FROM `admin`";
$result = mysqli_query($conn, $query);
?>
<style>
.admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.admin-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.admin-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.admin-actions a {
    text-decoration: none;
}
.admin-actions .nav-btn {
    background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #1976d222;
    cursor: pointer;
    transition: background 0.18s;
}
.admin-actions .nav-btn.add {
    background: linear-gradient(90deg, #43a047 0%, #a5d6a7 100%);
}
.admin-actions .nav-btn:hover {
    background: #1256a3;
}
.admin-actions .nav-btn.add:hover {
    background: #388e3c;
}
.admin-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #1976d122;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 650px;
}
.admin-table th, .admin-table td {
    padding: 10px 12px;
    text-align: center;
}
.admin-table th {
    background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.admin-table tr {
    transition: background 0.15s;
}
.admin-table tr:nth-child(even) {
    background: #f8fafc;
}
.admin-table tr:hover {
    background: #e3f2fd;
}
.admin-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.admin-table td:last-child {
    min-width: 90px;
}
.admin-table .action-btn {
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
.admin-table .action-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .admin-table-container { padding: 10px 2px;}
    .admin-table { font-size: 0.97em; }
}
</style>

<div class="admin-header">
    <h2>Admin Users</h2>
    <a href="profile.php" style="color:#1976d2;font-weight:600;text-decoration:none;font-size:1em;">← Back to Profile</a>
</div>

<div class="admin-actions">
    <a href="show_admin.php"><button class="nav-btn">Show All</button></a>
    <a href="add_admin.php"><button class="nav-btn add">Add Admin</button></a>
</div>

<div class="admin-table-container">
    <table class="admin-table">
    <thead>
    <tr>
        <th>S.No</th>
        <th>Image</th>
        <th>Username</th>
        <th>Password</th>
        <th>Confirm Password</th>
        <th>Operation</th>
    </tr>
</thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $imgSrc = !empty($row['image']) ? "uploads/" . htmlspecialchars($row['image']) : "https://i.pravatar.cc/100?img=5";
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td><img src='{$imgSrc}' alt='Profile Image' style='width:48px;height:48px;border-radius:50%;object-fit:cover;border:1.5px solid #e0e0e0;box-shadow:0 1px 4px #1976d122;'/></td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                echo "<td>" . htmlspecialchars($row['confirm password']) . "</td>";
                echo "<td>
                        <a href='delete_admin.php?id={$row['id']}' onclick='return checkdelete();'>
                            <button class='action-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='6' style='color:#888;font-weight:600;'>No admin records found.</td></tr>";
            }            
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this admin?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
