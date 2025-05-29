<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Ice Cream";
$activePage = "fooditems";
ob_start(); // Start output buffering

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    // Not logged in, set error message and redirect
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}
// ---------------------------
?>

<style>
.icecream-add-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.icecream-add-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.icecream-form-container {
    max-width: 420px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #e91e6322;
    padding: 36px 28px 28px 28px;
    margin-top: 30px;
}
.icecream-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.icecream-form input[type="text"],
.icecream-form input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e0e0e0;
    margin-bottom: 18px;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.18s;
}
.icecream-form input[type="text"]:focus,
.icecream-form input[type="number"]:focus {
    border: 1.5px solid #e91e63;
    outline: none;
}
.icecream-form input[type="file"] {
    margin-bottom: 18px;
}
.icecream-form .submit-btn {
    width: 100%;
    padding: 10px 0;
    background: linear-gradient(90deg, #e91e63 0%, #f06292 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #e91e6322;
    transition: background 0.18s;
}
.icecream-form .submit-btn:hover {
    background: #e91e63;
}
.icecream-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.icecream-actions a {
    text-decoration: none;
}
.icecream-actions .nav-btn {
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
.icecream-actions .nav-btn:hover {
    background: #1256a3;
}
@media (max-width: 600px) {
    .icecream-form-container { padding: 18px 6vw 18px 6vw; }
}
</style>

<div class="icecream-add-header">
    <h2>Add Ice Cream Item</h2>
    <a href="showicecream.php" style="color:#e91e63;font-weight:600;text-decoration:none;font-size:1em;">← Back to Ice Cream List</a>
</div>

<div class="icecream-actions">
    <a href="showicecream.php"><button class="nav-btn">Show All</button></a>
    <a href="servericecream.php"><button class="nav-btn" style="background: #e91e63;">Add Ice Cream</button></a>
</div>

<div class="icecream-form-container">
    <form class="icecream-form" method="post" action="upload_icecream.php" enctype="multipart/form-data">
        <label for="title">Ice Cream Title</label>
        <input type="text" name="title" id="title" required placeholder="e.g. Choco Fudge" />

        <label for="price">Price (₹)</label>
        <input type="number" name="price" id="price" required placeholder="e.g. 80" min="0" step="1" />

        <label for="image">Ice Cream Image</label>
        <input type="file" name="image" id="image" required accept="image/*" />

        <input type="submit" class="submit-btn" value="Upload Ice Cream" name="submit" />
    </form>
</div>

<?php
$content = ob_get_clean(); // End output buffering, store output in $content
include "layout.php";      // Now include the layout, which will echo $content
?>
