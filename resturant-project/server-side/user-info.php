<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Manage Users";
$activePage = "users";

ob_start();
include_once("connection.php");

// Enhanced search: split input into terms, search name/email/phone for each
$search = '';
$where = '';
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = trim($_GET['search']);
    $terms = preg_split('/\s+/', $search);
    $likeParts = [];
    foreach ($terms as $term) {
        $safeTerm = mysqli_real_escape_string($conn, $term);
        $likeParts[] = "(name LIKE '%$safeTerm%' OR email LIKE '%$safeTerm%' OR phone LIKE '%$safeTerm%')";
    }
    $where = "WHERE " . implode(" AND ", $likeParts);
}
$query = "SELECT * FROM `user_signup` $where";
$result = mysqli_query($conn, $query);
?>
<style>
.user-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.user-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #155263;
    margin: 0;
}
.user-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #1976d222;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.user-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 900px;
}
.user-table th, .user-table td {
    padding: 10px 12px;
    text-align: center;
}
.user-table th {
    background: linear-gradient(90deg, #1976D2 0%, #26C6DA 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.user-table tr {
    transition: background 0.15s;
}
.user-table tr:nth-child(even) {
    background: #f4fafd;
}
.user-table tr:hover {
    background: #e3f2fd;
}
.user-table td {
    color: #222;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.user-table td.action-cell {
    min-width: 60px;
    max-width: 70px;
    width: 65px;
    padding: 0 2px;
}
.user-table img {
    border-radius: 12px;
    box-shadow: 0 2px 8px #1976d233;
    width: 60px;
    height: 60px;
    object-fit: cover;
    background: #eee;
}
.user-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
    align-items: center;
}
.user-actions form {
    display: flex;
    gap: 8px;
    align-items: center;
}
.user-actions input[type="text"] {
    padding: 7px 12px;
    border-radius: 6px;
    border: 1px solid #b0bec5;
    font-size: 1em;
    outline: none;
    width: 220px;
    transition: border 0.2s;
}
.user-actions input[type="text"]:focus {
    border: 1.5px solid #1976D2;
}
.user-actions button.search-btn {
    background: linear-gradient(90deg, #1976D2 0%, #26C6DA 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 18px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #1976d222;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background 0.18s;
}
.user-actions button.search-btn:hover {
    background: #1976D2;
}
.user-actions .showall-btn {
    background: #43a047 !important;
    transition: background 0.18s;
}
.user-actions .showall-btn:hover {
    background: #2e7031 !important;
}
.user-table .icon-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 6px;
    font-size: 1.2em;
    transition: color 0.18s;
    vertical-align: middle;
}
.user-table .edit-btn {
    color: #43a047;
}
.user-table .edit-btn:hover {
    color: #2e7031;
}
.user-table .delete-btn {
    color: #d32f2f;
}
.user-table .delete-btn:hover {
    color: #b71c1c;
}
@media (max-width: 900px) {
    .user-table-container { padding: 10px 2px;}
    .user-table { font-size: 0.97em; min-width: 600px;}
    .user-actions input[type="text"] { width: 120px;}
    .user-table td.action-cell { min-width: 40px; max-width: 50px; width: 45px;}
}
</style>
<!-- Material Icons CDN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="user-header">
    <h2>Manage Users</h2>
    <a href="dashboard.php" style="color:#1976D2;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="user-actions">
    <form method="get" action="" style="display:flex;gap:8px;align-items:center;">
        <input type="text" name="search" placeholder="Search name, email, phone..." value="<?php echo htmlspecialchars($search); ?>" />
        <button class="search-btn" type="submit">
            <span class="material-icons" style="font-size:1.1em;">search</span>
            Search
        </button>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>">
            <button type="button" class="search-btn showall-btn" style="gap:6px;">
                <span class="material-icons" style="font-size:1.1em;">list</span>
                Show All
            </button>
        </a>
    </form>
</div>

<div class="user-table-container">
    <table class="user-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Address</th>
                <th class="action-cell" colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>";
                if (!empty($row['image'])) {
                    $imgSrc = "http://localhost/php_training/resturant-project/server-side/imgsource/{$row['image']}";
                    echo "<img src='{$imgSrc}' alt='User Image' />";
                } else {
                    echo "<span style='color:#aaa;'>No Image</span>";
                }
                echo "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td class='action-cell'>
                        <a href='delete_user.php?id={$row['ID']}' onclick='return checkdelete();'>
                            <button class='icon-btn delete-btn' title='Delete'>
                                <span class='material-icons'>delete</span>
                            </button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='9' style='color:#888;font-weight:600;'>No user records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this user?');
}
function checkedit(){
    return window.confirm('Are you sure you want to edit this user?');
}
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
