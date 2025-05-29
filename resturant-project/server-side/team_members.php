<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Team Member";
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
<style>
.team-add-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.team-add-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.team-form-container {
    max-width: 420px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #fbc02d22;
    padding: 36px 28px 28px 28px;
    margin-top: 30px;
}
.team-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.team-form input[type="file"] {
    margin-bottom: 18px;
}
.team-form .submit-btn {
    width: 100%;
    padding: 10px 0;
    background: linear-gradient(90deg, #fbc02d 0%, #ffe082 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #fbc02d22;
    transition: background 0.18s;
}
.team-form .submit-btn:hover {
    background: #fbc02d;
}
.team-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.team-actions a {
    text-decoration: none;
}
.team-actions .nav-btn {
    background: #1976D2;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #1976D222;
    cursor: pointer;
    transition: background 0.18s;
}
.team-actions .nav-btn:hover {
    background: #1256a3;
}
@media (max-width: 600px) {
    .team-form-container { padding: 18px 6vw 18px 6vw; }
}
</style>

<div class="team-add-header">
    <h2>Add Team Member</h2>
    <a href="showteam_members.php" style="color:#fbc02d;font-weight:600;text-decoration:none;font-size:1em;">← Back to Team List</a>
</div>

<div class="team-actions">
    <a href="showteam_members.php"><button class="nav-btn">Show All</button></a>
    <a href="team_members.php"><button class="nav-btn" style="background: #fbc02d;">Add Member</button></a>
</div>

<div class="team-form-container">
    <form class="team-form" method="post" action="team_member_upload.php" enctype="multipart/form-data">
        <label for="image">Team Member Photo</label>
        <input type="file" name="image" id="image" required accept="image/*" />

        <input type="submit" class="submit-btn" value="Upload" name="submit" />
    </form>
</div>


<?php
$content = ob_get_clean();
include "layout.php";
?>
