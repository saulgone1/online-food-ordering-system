<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Profile";
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
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";
?>
<style>
.profile-header {
    margin-bottom: 24px;
}
.profile-header h1 {
    font-size: 2em;
    font-weight: 800;
    color: #333;
    margin: 0;
}
.profile-header .welcome {
    color: #ff9800;
    font-weight: 700;
}
.profile-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 28px;
    margin-top: 28px;
}
.profile-card {
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
.profile-card:hover {
    box-shadow: 0 6px 24px #43e97b33;
    transform: translateY(-2px) scale(1.025);
}
.profile-card .icon {
    font-size: 2.5em;
    margin-bottom: 10px;
    border-radius: 10px;
    padding: 10px 14px;
    color: #fff;
    box-shadow: 0 2px 8px #43e97b22;
}
.profile-card .add-admin { background: linear-gradient(120deg, #ff9800 60%, #ffd54f 100%);}
.profile-card .show-admin { background: linear-gradient(120deg, #1976d2 60%, #64b5f6 100%);}
.profile-card .logout { background: linear-gradient(120deg, #d32f2f 60%, #ff7961 100%);}
.profile-card .title {
    font-size: 1.15em;
    font-weight: 700;
    margin-bottom: 2px;
    color: #333;
    letter-spacing: 0.01em;
    opacity: 0.91;
}
.profile-card .action-btn {
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
.profile-card .action-btn:hover {
    background: #388e3c;
    opacity: 1;
}
@media (max-width: 900px) {
    .profile-cards { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;}
}
</style>

<div class="profile-header">
    <h1>Welcome <span class="welcome"><?= htmlspecialchars($username) ?></span></h1>
    <h2 style="font-weight:700;color:#555;margin-top:10px;">Admin Panel</h2>
</div>

<div class="profile-cards">
    <div class="profile-card">
        <div class="icon add-admin"><i class="fa-solid fa-user-plus"></i></div>
        <div class="title">Add Admin</div>
        <a class="action-btn" href="add_admin.php">Add Admin</a>
    </div>
    <div class="profile-card">
        <div class="icon show-admin"><i class="fa-solid fa-user"></i></div>
        <div class="title">Show Admins</div>
        <a class="action-btn" href="show_admin.php">Show Admins</a>
    </div>
    <div class="profile-card">
        <div class="icon logout"><i class="fa-solid fa-user-minus"></i></div>
        <div class="title">Logout</div>
        <a class="action-btn" href="login.php">Logout</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include "layout.php";
?>
