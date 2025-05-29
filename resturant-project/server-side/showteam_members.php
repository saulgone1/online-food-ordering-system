<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Team Members List";
$activePage = "teammembers";

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
$query = "SELECT * FROM `teammembers`";
$result = mysqli_query($conn, $query);
?>
<style>
.team-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.team-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.team-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #fbc02d22;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.team-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 400px;
}
.team-table th, .team-table td {
    padding: 10px 12px;
    text-align: center;
}
.team-table th {
    background: linear-gradient(90deg, #fbc02d 0%, #ffe082 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.team-table tr {
    transition: background 0.15s;
}
.team-table tr:nth-child(even) {
    background: #f8fafc;
}
.team-table tr:hover {
    background: #fffde7;
}
.team-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.team-table td:last-child {
    min-width: 90px;
}
.team-table img {
    border-radius: 12px;
    box-shadow: 0 2px 8px #fbc02d33;
    width: 80px;
    height: 80px;
    object-fit: cover;
    background: #eee;
}
.team-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.team-actions a {
    text-decoration: none;
}
.team-actions .add-btn {
    background: linear-gradient(90deg, #fbc02d 0%, #ffe082 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #fbc02d22;
    cursor: pointer;
    transition: background 0.18s;
}
.team-actions .add-btn:hover {
    background: #fbc02d;
}
.team-table .action-btn {
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
.team-table .action-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .team-table-container { padding: 10px 2px;}
    .team-table { font-size: 0.97em; }
}
</style>

<div class="team-header">
    <h2>Team Members</h2>
    <a href="dashboard.php" style="color:#fbc02d;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="team-actions">
    <a href="showteam_members.php"><button class="add-btn" style="background: #1976D2;">Show All</button></a>
    <a href="team_members.php"><button class="add-btn">Add Member</button></a>
</div>

<div class="team-table-container">
    <table class="team-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Photo</th>
                <th>Operation</th>
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
                    echo "<img src='{$imgSrc}' alt='Team Member' />";
                } else {
                    echo "<span style='color:#aaa;'>No Image</span>";
                }
                echo "</td>";
                echo "<td>
                        <a href='delete_teammembers.php?id={$row['id']}' onclick='return checkdelete();'>
                            <button class='action-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='3' style='color:#888;font-weight:600;'>No team members found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this team member?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
