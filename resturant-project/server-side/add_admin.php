<?php
session_name('ADMINSESSID');
session_start();
$pageTitle = "Add Admin";
$activePage = "profile";
ob_start();

// --- Access Control Check ---
if (!isset($_SESSION['admin_id'])) {
    // Not logged in, show error or redirect
    header("Location: login.php");
    exit;
}
// ---------------------------

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];
    $imageFile = $_FILES['image'] ?? null;

    if ($password !== $confirm) {
        $message = "<div class='error-msg'>Passwords do not match.</div>";
    } elseif (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
        $message = "<div class='error-msg'>Please select a profile image.</div>";
    } else {
        $conn = mysqli_connect("localhost", "root", "", "resturant_project");
        if (!$conn) {
            $message = "<div class='error-msg'>Database connection failed.</div>";
        } else {
            // Check if user exists
            $stmt = $conn->prepare("SELECT * FROM `admin` WHERE `username` = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $message = "<div class='error-msg'>User already exists.</div>";
            } else {
                // Handle image upload
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $message = "<div class='error-msg'>Invalid image format. Allowed: jpg, jpeg, png, gif, webp.</div>";
                } else {
                    $imgName = uniqid("admin_", true) . "." . $ext;
                    $uploadPath = "uploads/" . $imgName;
                    if (!is_dir("uploads")) mkdir("uploads");
                    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                        // Store password as plain text
                        $plain_password = $password;
                        $stmt = $conn->prepare("INSERT INTO `admin` (`username`, `password`, `confirm password`, `image`) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $username, $plain_password, $confirm, $imgName);
                        if ($stmt->execute()) {
                            $message = "<div class='success-msg'>Admin has been added successfully!</div>";
                        } else {
                            $message = "<div class='error-msg'>Failed to add admin.</div>";
                        }
                    } else {
                        $message = "<div class='error-msg'>Failed to upload image.</div>";
                    }
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
}

?>
<style>
.admin-actions {
    display: flex;
    gap: 14px;
    margin-bottom: 18px;
    justify-content: center;
}
.admin-actions a {
    text-decoration: none;
}
.admin-actions .nav-btn {
    background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 22px;
    font-weight: 700;
    font-size: 1em;
    box-shadow: 0 2px 8px #1976d222;
    cursor: pointer;
    transition: background 0.18s;
}
.admin-actions .nav-btn.add {
    background: linear-gradient(90deg, #43a047 0%, #a5d6a7 100%);
}
.admin-actions .nav-btn:hover {
    background: #1256a3;
}
.admin-actions .nav-btn.add:hover {
    background: #388e3c;
}
.box{
    margin: 70px auto 0 auto;
    justify-self:center;
    align-self:center;
    background-color:#fff;
    width:340px;
    border:1px solid #e0e0e0;
    border-radius: 18px;
    height:auto;
    color:black;
    box-shadow:0 4px 20px #1976d122;
    padding: 32px 25px 24px 25px;
}
.add-admin-header {
    font-size: 1.35em;
    font-weight: 800;
    color: #222;
    margin-bottom: 18px;
}
.add-admin-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #444;
    text-align: left;
}
.add-admin-form input[type="text"],
.add-admin-form input[type="password"] {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1.5px solid #e0e0e0;
    margin-bottom: 18px;
    font-size: 1em;
    background: #f8fafc;
    transition: border 0.18s;
}
.add-admin-form input[type="text"]:focus,
.add-admin-form input[type="password"]:focus {
    border: 1.5px solid #1976d2;
    outline: none;
}
.add-admin-form input[type="file"] {
    margin-bottom: 18px;
}
.image-preview-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 18px;
}
.image-preview {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    background: #f8fafc;
    border: 2px solid #e0e0e0;
    margin-bottom: 8px;
}
.add-admin-form .submit-btn {
    width: 100%;
    padding: 10px 0;
    background: linear-gradient(90deg, #43a047 0%, #a5d6a7 100%);
    color: #fff;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    font-size: 1.08em;
    cursor: pointer;
    box-shadow: 0 2px 8px #43a04722;
    transition: background 0.18s;
}
.add-admin-form .submit-btn:hover {
    background: #388e3c;
}
.success-msg {
    color: #388e3c;
    font-weight: 700;
    margin-top: 18px;
    margin-bottom: 0;
}
.error-msg {
    color: #d32f2f;
    font-weight: 700;
    margin-top: 18px;
    margin-bottom: 0;
}
@media (max-width: 600px) {
    .box { width: 95vw; padding: 18px 4vw 18px 4vw; }
}
</style>

<h1 style='background-color:white;'>Add Admin</h1>
<div class="admin-actions">
    <a href='show_admin.php'><button class="nav-btn">Show All</button></a>
    <a href='add_admin.php'><button class="nav-btn add">Add Admin</button></a>
</div>
<div class="box">
    <div class="add-admin-header">Add New Admin</div>
    <?php if ($message) echo $message; ?>
    <form class="add-admin-form" method="post" action="" enctype="multipart/form-data">
        <div class="image-preview-block">
            <img id="imgPreview" class="image-preview" src="https://i.pravatar.cc/100?img=5" alt="Image Preview" />
            <label for="image">Profile Image</label>
            <input type="file" name="image" id="image" required accept="image/*" onchange="previewImage(event)" />
        </div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required placeholder="Username..." />

        <label for="password">Password</label>
        <input type="password" name="password" id="p1" required placeholder="Password..." />

        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" id="p2" required placeholder="Confirm Password..." />

        <input type="submit" class="submit-btn" value="Submit" name="submit"/>
    </form>
</div>
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php
$content = ob_get_clean();
include "layout.php";
?>
