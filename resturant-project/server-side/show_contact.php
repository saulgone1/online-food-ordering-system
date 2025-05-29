<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Contacts";
$activePage = "contacts";

ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    // Not logged in, set error message and redirect
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}
// ---------------------------

// Your PHP code goes here
include_once("connection.php");
$query = "SELECT * FROM `contact`";
$result = mysqli_query($conn, $query);
?>

<style>
.contacts-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.contacts-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.contacts-table-container {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #43e97b18;
    padding: 24px 16px 12px 16px;
    overflow-x: auto;
}
.contacts-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
    min-width: 750px;
}
.contacts-table th, .contacts-table td {
    padding: 10px 12px;
    text-align: center;
}
.contacts-table th {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e0e0e0;
}
.contacts-table tr {
    transition: background 0.15s;
}
.contacts-table tr:nth-child(even) {
    background: #f8fafc;
}
.contacts-table tr:hover {
    background: #e8f5e9;
}
.contacts-table td {
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
}
.contacts-table td:last-child {
    min-width: 90px;
}
.contacts-table .action-btn {
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
.contacts-table .action-btn:hover {
    background: #b71c1c;
}
@media (max-width: 900px) {
    .contacts-table-container { padding: 10px 2px;}
    .contacts-table { font-size: 0.97em; }
}
</style>

<div class="contacts-header">
    <h2>Contact Submissions</h2>
    <a href="dashboard.php" style="color:#43a047;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dashboard</a>
</div>

<div class="contacts-table-container">
    <table class="contacts-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Contact Number</th>
                <th>Gender</th>
                <th>Report Details</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>
                        <a href='delete_contact.php?id={$row['id']}' onclick='return checkdelete();'>
                            <button class='action-btn'>Delete</button>
                        </a>
                      </td>";
                echo "</tr>";
                $i++;
            }
            if ($i === 1) {
                echo "<tr><td colspan='7' style='color:#888;font-weight:600;'>No contact records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function checkdelete(){
    return window.confirm('Are you sure you want to delete this contact?');
}
</script>


<?php
$content = ob_get_clean();
include "layout.php";
?>
