<?php
session_name('USERSESSID');
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    exit('<div style="padding:40px;color:#d32f2f;text-align:center;">Not logged in.</div>');
}
$user_id = $_SESSION['user_id'];

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Handle form submission
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $image = $_POST['current_image'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $newName = 'profile_' . $user_id . '_' . time() . '.' . $ext;

            // Save to ../server-side/imgsource/ relative to client-side/
            $targetDir = '../server-side/imgsource/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $target = $targetDir . $newName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $newName;
            } else {
                $error = "Image upload failed.";
            }
        } else {
            $error = "Invalid image type.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE user_signup SET name=?, phone=?, dob=?, address=?, email=?, image=? WHERE ID=?");
        $stmt->bind_param("ssssssi", $name, $phone, $dob, $address, $email, $image, $user_id);
        if ($stmt->execute()) {
            if ($isAjax) {
                echo "RELOAD_DASHBOARD";
                exit;
            } else {
                header("Location: profile_edit.php");
                exit;
            }
        } else {
            $error = "Update failed. Please try again.";
        }
    }
}

// Fetch user data for form
$stmt = $conn->prepare("SELECT * FROM user_signup WHERE ID=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) die("User not found.");
?>

<style>
:root {
--primary: #4CAF50;
--primary-dark: #388e3c;
--accent: #f5f5f5;
--border: #e0e0e0;
--muted: #888;
--danger: #d32f2f;
--shadow: 0 8px 32px rgba(76,175,80,0.12);
--gradient: linear-gradient(135deg, #e8f5e9 65%, #f1f8e9 100%);
}
.edit-account-bg {
width: 100%;
height: 100%;
min-height: 100%;
background: transparent;
padding: 0;
}
.edit-account-full {
display: flex;
gap: 0;
background: transparent;
min-height: 560px;
margin: 32px 0 0 0;
border-radius: 20px;
overflow: visible;
}
.edit-account-card {
display: flex;
flex: 1;
background: #fff;
border-radius: 20px;
box-shadow: var(--shadow);
min-height: 560px;
width: 100%;
overflow: hidden;
animation: fadeIn 0.5s;
margin: 0 auto;
position: relative;
}
@media (max-width: 900px) {
.edit-account-full { flex-direction: column; margin: 0; }
.edit-account-card { flex-direction: column; min-height: 0; }
}
.edit-account-sidebar {
width: 320px;
min-width: 220px;
background: var(--gradient);
display: flex;
flex-direction: column;
align-items: center;
justify-content: flex-start;
border-right: 2px solid var(--border);
padding: 48px 24px 48px 24px;
box-shadow: 0 2px 12px rgba(76,175,80,0.08);
position: relative;
z-index: 1;
}
.edit-account-sidebar .profile-pic-preview {
width: 120px;
height: 120px;
border-radius: 50%;
object-fit: cover;
box-shadow: 0 4px 24px rgba(76,175,80,0.15);
border: 4px solid var(--primary);
background: #fff;
margin-bottom: 18px;
transition: box-shadow 0.3s;
}
.edit-account-sidebar .user-name {
font-size: 1.22em;
font-weight: 800;
color: #222;
margin-bottom: 5px;
letter-spacing: 0.01em;
}
.edit-account-sidebar .user-email {
font-size: 0.98em;
color: var(--muted);
margin-bottom: 18px;
word-break: break-all;
}
.edit-account-sidebar label {
display: block;
font-weight: 600;
margin-bottom: 8px;
color: #388e3c;
text-align: left;
}
.edit-account-sidebar input[type="file"] {
width: 100%;
padding: 8px 0;
border-radius: 8px;
border: 1.5px solid var(--border);
cursor: pointer;
background: #fff;
margin-bottom: 6px;
font-size: 0.98em;
}
.edit-account-sidebar input[type="file"]:hover {
border-color: var(--primary);
}
.edit-account-sidebar .note {
font-size: 0.93em;
color: var(--muted);
margin-top: 6px;
text-align: center;
}
.edit-account-main {
flex: 1;
min-width: 0;
padding: 48px 48px 64px 48px;
display: flex;
flex-direction: column;
justify-content: flex-start;
position: relative;
}
.edit-account-main h2 {
color: var(--primary);
font-weight: 800;
font-size: 1.55em;
margin-bottom: 34px;
letter-spacing: 0.02em;
border-bottom: 2px solid var(--primary);
padding-bottom: 10px;
background: none;
}
.form-group {
margin-bottom: 28px;
}
.form-group label {
display: block;
font-weight: 600;
margin-bottom: 10px;
color: #333;
letter-spacing: 0.01em;
}
.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="date"] {
width: 100%;
padding: 14px 16px;
font-size: 1.08em;
border: 1.5px solid var(--border);
border-radius: 9px;
transition: border-color 0.25s, box-shadow 0.25s;
background: #f8fafc;
font-weight: 500;
}
.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="date"]:focus {
border-color: var(--primary);
outline: none;
box-shadow: 0 0 8px var(--primary, #4CAF50);
}
.form-group input[readonly] {
background: #f9f9f9;
color: var(--muted);
cursor: not-allowed;
}
.btn-submit {
background: var(--primary);
color: white;
font-weight: 700;
font-size: 1.13em;
padding: 16px 0;
border: none;
border-radius: 13px;
width: 240px;
cursor: pointer;
letter-spacing: 0.02em;
transition: background 0.3s, box-shadow 0.2s;
margin-top: 20px;
box-shadow: 0 2px 12px rgba(76,175,80,0.09);
align-self: flex-start;
}
.btn-submit:hover {
background: var(--primary-dark);
box-shadow: 0 4px 16px rgba(76,175,80,0.13);
}
.message {
color: var(--primary-dark);
font-weight: 700;
margin-bottom: 20px;
text-align: center;
background: #e8f5e9;
border-radius: 8px;
padding: 10px 0;
}
.error {
color: var(--danger);
font-weight: 700;
margin-bottom: 20px;
text-align: center;
background: #ffebee;
border-radius: 8px;
padding: 10px 0;
}
@media (max-width: 1100px) {
.edit-account-main { padding: 32px 12px 44px 12px;}
.edit-account-sidebar { padding: 32px 12px 32px 12px;}
}
@media (max-width: 900px) {
.edit-account-card { flex-direction: column; }
.edit-account-main { padding: 28px 8px 44px 8px;}
.edit-account-sidebar { width: 100%; border-right: none; border-bottom: 2px solid var(--border);}
}
@media (max-width: 600px) {
.edit-account-main { padding: 12px 2vw 32px 2vw;}
.edit-account-sidebar { padding: 20px 2vw 20px 2vw;}
}
@keyframes fadeIn {
from {opacity: 0; transform: translateY(20px);}
to {opacity: 1; transform: none;}
}

.form-row {
  display: flex;
  gap: 24px;
  margin-bottom: 0;
}
.form-group.half {
  flex: 1 1 0;
  min-width: 0;
}
.form-group.full {
  flex: 1 1 100%;
}
@media (max-width: 700px) {
  .form-row { flex-direction: column; gap: 0; }
}


</style>

<div class="edit-account-bg">
<form id="editAccountForm" method="post" enctype="multipart/form-data" autocomplete="off">
<div class="edit-account-full">
<div class="edit-account-card">
<!-- Sidebar/Profile column (unchanged) -->
<div class="edit-account-sidebar">
<img src="<?= $user['image'] ? '../server-side/imgsource/' . htmlspecialchars($user['image']) : 'https://i.pravatar.cc/100?img=5' ?>" alt="Profile Picture" class="profile-pic-preview" id="imgPreview">
<div class="user-name"><?= htmlspecialchars($user['name']) ?></div>
<div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
<label for="image">Change Profile Image</label>
<input type="file" name="image" id="image" accept="image/*" onchange="previewImg(event)">
<input type="hidden" name="current_image" value="<?= htmlspecialchars($user['image']) ?>">
<div class="note">JPG, PNG, GIF. Max size: 2MB.</div>
</div>
<!-- Main form column (improved) -->
<div class="edit-account-main">
  <h2>Edit Account</h2>
  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- First row: Full Name & Email -->
  <div class="form-row">
    <div class="form-group half">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
    </div>
    <div class="form-group half">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
    </div>
  </div>

  <!-- Second row: Phone & DOB -->
  <div class="form-row">
    <div class="form-group half">
      <label for="phone">Mobile Number</label>
      <input type="text" id="phone" name="phone" required value="<?= htmlspecialchars($user['phone']) ?>">
    </div>
    <div class="form-group half">
      <label for="dob">Date of Birth</label>
      <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">
    </div>
  </div>

  <!-- Third row: Address (full width) -->
  <div class="form-row">
    <div class="form-group full">
      <label for="address">Address</label>
      <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>">
    </div>
  </div>

  <button type="submit" class="btn-submit">Save Changes</button>
</div>
</div>
</div>
</form>
</div>
