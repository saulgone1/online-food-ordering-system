<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Update Featured Food";
$activePage = "featured";
ob_start();

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_msg'] = "Access Denied! Please log in as admin to continue.";
    header("Location: login.php");
    exit;
}

include("connection.php");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$msg = "";

// Fetch current featured food data
$query = "SELECT * FROM featured_food WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = intval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $imgField = $row['imgsource'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $uploadDir = "imgsource/";
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmp, $uploadPath)) {
            $imgField = $fileName;
        } else {
            $msg = "Image upload failed. ";
        }
    }

    $update = "UPDATE featured_food SET title='$title', price=$price, description='$description', category='$category', imgsource='$imgField' WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        $msg .= "Featured food updated successfully!";
        $row = ['title' => $title, 'price' => $price, 'description' => $description, 'category' => $category, 'imgsource' => $imgField];
    } else {
        $msg .= "Error updating featured food.";
    }
}
?>

<style>
.featured-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.featured-header h2 {
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
    resize: vertical;
}
.featured-form input[type="text"]:focus,
.featured-form input[type="number"]:focus,
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

/* Success message styling */
.success-msg {
    background: linear-gradient(90deg, #43a047 0%, #a5d6a7 100%);
    color: #fff;
    font-weight: 700;
    border-radius: 8px;
    font-size: 1.15em;
    padding: 18px 0;
    margin-bottom: 22px;
    text-align: center;
    box-shadow: 0 2px 8px #43a04733;
    letter-spacing: 0.02em;
    animation: fadeIn 0.8s;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px);}
    to { opacity: 1; transform: translateY(0);}
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

<div class="featured-header">
    <h2>Update Featured Food Item</h2>
    <a href="Show_featured.php" style="color:#8e24aa;font-weight:600;text-decoration:none;font-size:1em;">← Back to Featured Food List</a>
</div>

<div class="featured-actions">
    <a href="Show_featured.php"><button class="nav-btn">Show All</button></a>
    <a href="add_featured.php"><button class="nav-btn" style="background: #8e24aa;">Add Featured</button></a>
</div>

<div class="featured-form-container">
    <?php if ($msg): ?>
        <div class="success-msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form class="featured-form" method="post" enctype="multipart/form-data">
        <div class="featured-form-flex">
            <div class="featured-form-img" style="min-width:200px;min-height:200px;position:relative;">
                <label style="margin-bottom:10px;">Image</label>
                <!-- Only one of these is visible at a time -->
                <?php if (!empty($row['imgsource'])): ?>
                    <img src="imgsource/<?= htmlspecialchars($row['imgsource']) ?>" 
                         alt="Current Image" class="img-preview" id="currentImg"
                         style="display:block;"/>
                    <div id="noImgBlock" class="no-img-block" style="display:none;">No Image</div>
                <?php else: ?>
                    <img src="" alt="Current Image" class="img-preview" id="currentImg" style="display:none;" />
                    <div id="noImgBlock" class="no-img-block" style="display:flex;">No Image</div>
                <?php endif; ?>
                <img id="preview" class="img-preview" style="display:none;"/>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)" />
            </div>
            <div class="featured-form-fields">
                <div class="featured-form-row">
                    <div>
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" required value="<?= htmlspecialchars($row['title'] ?? '') ?>" />
                    </div>
                    <div>
                        <label for="price">Price (₹)</label>
                        <input type="number" name="price" id="price" required value="<?= htmlspecialchars($row['price'] ?? '') ?>" min="0" step="1" />
                    </div>
                </div>
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                <label for="category">Category</label>
                <input type="text" name="category" id="category" required value="<?= htmlspecialchars($row['category'] ?? '') ?>" />
                <input type="submit" class="submit-btn" value="Update Featured Food" name="submit" />
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    var preview = document.getElementById('preview');
    var currentImg = document.getElementById('currentImg');
    var noImgBlock = document.getElementById('noImgBlock');
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if(currentImg) currentImg.style.display = 'none';
            if(noImgBlock) noImgBlock.style.display = 'none';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        if(currentImg && currentImg.src) currentImg.style.display = 'block';
        if(noImgBlock && (!currentImg || !currentImg.src)) noImgBlock.style.display = 'flex';
    }
}
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
