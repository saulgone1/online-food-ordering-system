<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Pizza";
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

<!-- Your page content starts here -->
<style>
/* Your CSS here (same as before) */
.pizza-add-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
.pizza-add-header h2 { font-size: 1.35em; font-weight: 800; color: #222; margin: 0; }
.pizza-form-container { max-width: 420px; margin: 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 2px 16px #ff980022; padding: 36px 28px 28px 28px; margin-top: 30px; }
.pizza-form label { display: block; font-weight: 600; margin-bottom: 6px; color: #444; text-align: left; }
.pizza-form input[type="text"], .pizza-form input[type="number"] { width: 100%; padding: 8px 12px; border-radius: 7px; border: 1.5px solid #e0e0e0; margin-bottom: 18px; font-size: 1em; background: #f8fafc; transition: border 0.18s; }
.pizza-form input[type="text"]:focus, .pizza-form input[type="number"]:focus { border: 1.5px solid #ff9800; outline: none; }
.pizza-form input[type="file"] { margin-bottom: 18px; }
.pizza-form .submit-btn { width: 100%; padding: 10px 0; background: linear-gradient(90deg, #ff9800 0%, #ffd54f 100%); color: #fff; font-weight: 700; border: none; border-radius: 8px; font-size: 1.08em; cursor: pointer; box-shadow: 0 2px 8px #ff980022; transition: background 0.18s; }
.pizza-form .submit-btn:hover { background: #ff9800; }
.pizza-actions { display: flex; gap: 14px; margin-bottom: 18px; }
.pizza-actions a { text-decoration: none; }
.pizza-actions .nav-btn { background: #1976D2; color: #fff; border: none; border-radius: 7px; padding: 8px 22px; font-weight: 700; font-size: 1em; box-shadow: 0 2px 8px #1976D222; cursor: pointer; transition: background 0.18s; }
.pizza-actions .nav-btn:hover { background: #1256a3; }
@media (max-width: 600px) { .pizza-form-container { padding: 18px 6vw 18px 6vw; } }
</style>

<div class="pizza-add-header">
    <h2>Add Pizza Item</h2>
    <a href="showpizza.php" style="color:#ff9800;font-weight:600;text-decoration:none;font-size:1em;">← Back to Pizza List</a>
</div>

<div class="pizza-actions">
    <a href="showpizza.php"><button class="nav-btn">Show All</button></a>
    <a href="serverpizza.php"><button class="nav-btn" style="background: #ff9800;">Add Pizza</button></a>
</div>

<div class="pizza-form-container">
    <form class="pizza-form" method="post" action="upload_pizza.php" enctype="multipart/form-data">
        <label for="title">Pizza Title</label>
        <input type="text" name="title" id="title" required placeholder="e.g. Margherita" />

        <label for="price">Price (₹)</label>
        <input type="number" name="price" id="price" required placeholder="e.g. 250" min="0" step="1" />

        <label for="image">Pizza Image</label>
        <input type="file" name="image" id="image" required accept="image/*" />

        <input type="submit" class="submit-btn" value="Upload Pizza" name="submit" />
    </form>
</div>
<!-- Your page content ends here -->

<?php
$content = ob_get_clean(); // End output buffering, store output in $content
include "layout.php";      // Now include the layout, which will echo $content
?>
