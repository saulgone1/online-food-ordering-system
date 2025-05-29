<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Dosa";
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
.dosa-add-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.dosa-add-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.dosa-form-container {
    max-width: 420px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #388e3c22;
    padding: 36px 28px 28px 28px;
    margin-top: 30px;
}
.dosa-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.dosa-form input[type="text"],
.dosa-form input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e0e0e0;
    margin-bottom: 18px;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.18s;
}
.dosa-form input[type="text"]:focus,
.dosa-form input[type="number"]:focus {
    border: 1.5px solid #388e3c;
    outline: none;
}
.dosa-form input[type="file"] {
    margin-bottom: 18px;
}
.dosa-form .submit-btn {
    width: 100%;
    padding: 10px 0;
    background: linear-gradient(90deg, #388e3c 0%, #a5d6a7 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #388e3c22;
    transition: background 0.18s;
}
.dosa-form .submit-btn:hover {
    background: #388e3c;
}
.dosa-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.dosa-actions a {
    text-decoration: none;
}
.dosa-actions .nav-btn {
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
.dosa-actions .nav-btn:hover {
    background: #1256a3;
}
@media (max-width: 600px) {
    .dosa-form-container { padding: 18px 6vw 18px 6vw; }
}
</style>

<div class="dosa-add-header">
    <h2>Add Dosa Item</h2>
    <a href="showdosa.php" style="color:#388e3c;font-weight:600;text-decoration:none;font-size:1em;">← Back to Dosa List</a>
</div>

<div class="dosa-actions">
    <a href="showdosa.php"><button class="nav-btn">Show All</button></a>
    <a href="serverdosa.php"><button class="nav-btn" style="background: #388e3c;">Add Dosa</button></a>
</div>

<div class="dosa-form-container">
    <form class="dosa-form" method="post" action="upload_dosa.php" enctype="multipart/form-data">
        <label for="title">Dosa Title</label>
        <input type="text" name="title" id="title" required placeholder="e.g. Masala Dosa" />

        <label for="price">Price (₹)</label>
        <input type="number" name="price" id="price" required placeholder="e.g. 120" min="0" step="1" />

        <label for="image">Dosa Image</label>
        <input type="file" name="image" id="image" required accept="image/*" />

        <input type="submit" class="submit-btn" value="Upload Dosa" name="submit" />
    </form>
</div>


<?php
$content = ob_get_clean();
include "layout.php";
?>
