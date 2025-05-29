<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Cake";
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
<style>
.cake-add-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.cake-add-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.cake-form-container {
    max-width: 420px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #e5737322;
    padding: 36px 28px 28px 28px;
    margin-top: 30px;
}
.cake-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.cake-form input[type="text"],
.cake-form input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e0e0e0;
    margin-bottom: 18px;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.18s;
}
.cake-form input[type="text"]:focus,
.cake-form input[type="number"]:focus {
    border: 1.5px solid #e57373;
    outline: none;
}
.cake-form input[type="file"] {
    margin-bottom: 18px;
}
.cake-form .submit-btn {
    width: 100%;
    padding: 10px 0;
    background: linear-gradient(90deg, #e57373 0%, #ffd1dc 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #e5737322;
    transition: background 0.18s;
}
.cake-form .submit-btn:hover {
    background: #e57373;
}
.cake-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.cake-actions a {
    text-decoration: none;
}
.cake-actions .nav-btn {
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
.cake-actions .nav-btn:hover {
    background: #1256a3;
}
@media (max-width: 600px) {
    .cake-form-container { padding: 18px 6vw 18px 6vw; }
}
</style>

<div class="cake-add-header">
    <h2>Add Cake Item</h2>
    <a href="showcake.php" style="color:#e57373;font-weight:600;text-decoration:none;font-size:1em;">← Back to Cake List</a>
</div>

<div class="cake-actions">
    <a href="showcake.php"><button class="nav-btn">Show All</button></a>
    <a href="servercake.php"><button class="nav-btn" style="background: #e57373;">Add Cake</button></a>
</div>

<div class="cake-form-container">
    <form class="cake-form" method="post" action="upload_cake.php" enctype="multipart/form-data">
        <label for="title">Cake Title</label>
        <input type="text" name="title" id="title" required placeholder="e.g. Black Forest" />

        <label for="price">Price (₹)</label>
        <input type="number" name="price" id="price" required placeholder="e.g. 350" min="0" step="1" />

        <label for="image">Cake Image</label>
        <input type="file" name="image" id="image" required accept="image/*" />

        <input type="submit" class="submit-btn" value="Upload Cake" name="submit" />
    </form>
</div>


<?php
$content = ob_get_clean();
include "layout.php";
?>
