<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Featured Item";
$activePage = "featured";
ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
// ---------------------------

include_once("connection.php");

// Fetch all unique categories from the database
$categories = [];
$result = mysqli_query($conn, "SELECT DISTINCT category FROM featured_food WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category'];
    }
}
?>

<style>
.featured-add-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.featured-add-header h2 {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin: 0;
}
.featured-form-container {
    max-width: 740px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px #8e24aa22;
    padding: 38px 34px 38px 34px;
    margin-top: 30px;
}
.featured-form-flex {
    display: flex;
    gap: 36px;
    align-items: flex-start;
}
.featured-form-img {
    flex: 0 0 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    padding: 0 18px 28px 18px;
}
.img-preview, .no-img-block {
    width: 180px;
    height: 180px;
    border-radius: 12px;
    box-shadow: 0 2px 8px #8e24aa33;
    object-fit: cover;
    background: #eee;
    margin-bottom: 18px;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
}
.img-preview { z-index: 2; }
.no-img-block {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888;
    font-weight: 600;
    font-size: 1.07em;
    z-index: 1;
    background: #eee;
}
.featured-form-img input[type="file"] {
    margin-top: 200px;
    margin-bottom: 10px;
    width: 100%;
    padding: 8px 0 8px 0;
}
.featured-form-fields {
    flex: 1;
}
.featured-form-row {
    display: flex;
    gap: 18px;
    margin-bottom: 18px;
}
.featured-form-row > div {
    flex: 1;
}
.featured-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.featured-form input[type="text"],
.featured-form input[type="number"],
.featured-form select,
.featured-form textarea {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e0e0e0;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.18s;
}
.featured-form textarea {
    min-height: 70px;
    resize: none;
    overflow-y: hidden;
}
.featured-form input[type="text"]:focus,
.featured-form input[type="number"]:focus,
.featured-form select:focus,
.featured-form textarea:focus {
    border: 1.5px solid #8e24aa;
    outline: none;
}
.featured-form .submit-btn {
    width: 100%;
    padding: 12px 0;
    background: linear-gradient(90deg, #8e24aa 0%, #ba68c8 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #8e24aa22;
    transition: background 0.18s;
    margin-top: 18px;
}
.featured-form .submit-btn:hover {
    background: #8e24aa;
}
.featured-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
}
.featured-actions a {
    text-decoration: none;
}
.featured-actions .nav-btn {
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
.featured-actions .nav-btn:hover {
    background: #1256a3;
}
@media (max-width: 950px) {
    .featured-form-container { max-width: 98vw; }
    .featured-form-flex { flex-direction: column; gap: 0; }
    .featured-form-img { padding: 0 0 18px 0; }
    .img-preview, .no-img-block { position: static; margin: 0 auto 18px auto; }
}
@media (max-width: 600px) {
    .featured-form-container { padding: 18px 6vw 18px 6vw; }
    .featured-form-row { flex-direction: column; gap: 0; }
}
</style>

<div class="featured-add-header">
    <h2>Add Featured Food Item</h2>
    <a href="Show_featured.php" style="color:#8e24aa;font-weight:600;text-decoration:none;font-size:1em;">← Back to Featured List</a>
</div>

<div class="featured-actions">
    <a href="Show_featured.php"><button class="nav-btn">Show All</button></a>
    <a href="add_featured.php"><button class="nav-btn" style="background: #8e24aa;">Add Featured</button></a>
</div>

<div class="featured-form-container">
    <form class="featured-form" method="post" action="upload_featured.php" enctype="multipart/form-data" autocomplete="off">
        <div class="featured-form-flex">
            <div class="featured-form-img" style="min-width:200px;min-height:200px;position:relative;">
                <label style="margin-bottom:10px;">Image</label>
                <img id="preview" class="img-preview" style="display:none;" />
                <div id="noImgBlock" class="no-img-block" style="display:flex;">No Image</div>
                <input type="file" name="image" id="image" required accept="image/*" onchange="previewImage(event)" />
            </div>
            <div class="featured-form-fields">
                <div class="featured-form-row">
                    <div>
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" required placeholder="e.g. Pasta Primavera" />
                    </div>
                    <div>
                        <label for="price">Price (₹)</label>
                        <input type="number" name="price" id="price" required placeholder="e.g. 180" min="0" step="1" />
                    </div>
                </div>
                <label for="description">Description</label>
                <textarea name="description" id="description" required placeholder="Description..." oninput="autoExpand(this)"></textarea>
                <div class="featured-form-row">
                    <div>
                        <label for="category">Category</label>
                        <select name="category" id="category">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="new_category">Add Category</label>
                        <input type="text" name="new_category" id="new_category" placeholder="e.g. Pizza" />
                    </div>
                </div>
                <input type="submit" class="submit-btn" value="Upload Featured" name="submit" />
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    var preview = document.getElementById('preview');
    var noImgBlock = document.getElementById('noImgBlock');
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if(noImgBlock) noImgBlock.style.display = 'none';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        if(noImgBlock) noImgBlock.style.display = 'flex';
    }
}

// Ensure either category or new_category is filled
document.querySelector('.featured-form').addEventListener('submit', function(e) {
    var cat = document.getElementById('category').value.trim();
    var newCat = document.getElementById('new_category').value.trim();
    if (!cat && !newCat) {
        alert('Please select a category or add a new one.');
        document.getElementById('category').focus();
        e.preventDefault();
        return false;
    }
});

// Auto-expand textarea
function autoExpand(field) {
    field.style.height = 'inherit';
    field.style.height = (field.scrollHeight) + "px";
}

window.addEventListener('DOMContentLoaded', function() {
    var desc = document.getElementById('description');
    if (desc) autoExpand(desc);
});
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
