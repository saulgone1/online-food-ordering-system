<?php
session_name('USERSESSID');
session_start();
require 'connection.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
// Optionally fetch user info for sidebar/profile
$stmt = $conn->prepare("SELECT name, image FROM user_signup WHERE ID=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard | FoodiePro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* --- Copy your sidebar/header CSS from your current code --- */
    :root {
      --primary: #4CAF50;
      --primary-dark: #388e3c;
      --accent: #f5f5f5;
      --sidebar-bg: rgba(255,255,255,0.90);
      --sidebar-shadow: 0 8px 32px 0 rgba(60,60,60,0.12);
      --glass: rgba(255,255,255,0.7);
      --active: #e8f5e9;
      --hover: #f1f8e9;
      --border: #e0e0e0;
      --danger: #d32f2f;
      --warning: #fbc02d;
      --muted: #888;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(120deg, #f1f3f6 60%, #e8f5e9 100%);
      min-height: 100vh;
      display: flex;
      color: #222;
    }
    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      box-shadow: var(--sidebar-shadow);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 36px 0 20px 0;
      border-right: 1.5px solid var(--border);
      position: relative;
      z-index: 2;
      transition: box-shadow 0.3s;
    }
    .profile-pic-block {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 32px;
      position: relative;
    }
    .profile-pic {
      width: 78px; height: 78px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 2px 12px rgba(76,175,80,0.12);
      border: 3px solid var(--primary);
      background: #fff;
    }
    .sidebar ul {
      list-style: none;
      width: 100%;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 15px 34px;
      font-size: 1.08em;
      color: #333;
      cursor: pointer;
      border-left: 4px solid transparent;
      transition: background 0.25s, color 0.25s, border-left-color 0.25s, transform 0.15s;
      font-weight: 500;
      letter-spacing: 0.01em;
      user-select: none;
    }
    .sidebar ul li.active, .sidebar ul li:hover {
      background: var(--active);
      color: var(--primary);
      border-left: 4px solid var(--primary);
      transform: scale(1.03);
    }
    .sidebar ul li .icon {
      font-size: 1.18em;
      min-width: 22px;
      opacity: 0.82;
    }
    .sidebar .preferences-link {
      margin-top: auto;
      margin-bottom: 8px;
    }
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    .header {
      background: var(--primary);
      color: white;
      padding: 18px 36px;
      font-size: 1.3em;
      font-weight: 600;
      letter-spacing: 0.03em;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 12px rgba(76,175,80,0.09);
    }
    .header .user-name {
      font-size: 1.08em;
      font-weight: 500;
      opacity: 0.97;
      letter-spacing: 0.02em;
    }
    #mainContent { padding: 0 0 0 0; min-height: 80vh; }
    @media (max-width: 900px) {
      .sidebar { width: 100vw; position: static; min-height: 0; border-right: none; flex-direction: row; }
      .main { margin-left: 0; }
    }
    @media (max-width: 600px) {
      .sidebar { display: none; }
      .main { margin-left: 0; }
      .header { padding: 10px 12px; font-size: 1.1em;}
      #mainContent { padding: 10px 0 0 0; }
    }

    .header, .header-left  {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
  /* Reduce vertical padding */
  padding: 8px 24px 8px 18px;
  box-shadow: 0 6px 32px rgba(34,139,34,0.08);
  border-radius: 0 0 18px 18px;
  position: relative;
  z-index: 10;
}

.header-logo {
  font-size: 1.5em;  /* Slightly smaller */
  margin-right: 8px;
  filter: drop-shadow(0 2px 6px #fff8);
}
.header-title {
  font-size: 1.09em;
  font-weight: 800;
  color: #fff;
  letter-spacing: 0.02em;
  margin-right: 8px;
}
.header-subtitle {
  font-size: 0.98em;
  color: #e0ffe0;
  margin-right: 5px;
}
.header-user-chip {
  background: #fff;
  color: #43a047;
  font-weight: 700;
  border-radius: 16px;
  padding: 2px 10px;
  font-size: 0.97em;
  box-shadow: 0 2px 8px rgba(67,160,71,0.08);
  margin-left: 2px;
  transition: background 0.2s, color 0.2s;
  line-height: 1.7;
}
.header-user-chip:hover {
  background: #43a047;
  color: #fff;
}
.header-icons {
  display: flex;
  align-items: center;
  gap: 14px;
}
.header-icon {
  position: relative;
  font-size: 1.13em;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  background: rgba(255,255,255,0.13);
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  color: #fff;
}
.header-icon:hover {
  background: #fff;
  color: #43a047;
  transform: scale(1.09) rotate(-8deg);
  box-shadow: 0 2px 12px rgba(67,160,71,0.13);
}
.header-icon .badge {
  position: absolute;
  top: 2px;
  right: 1px;
  background: #d32f2f;
  color: #fff;
  font-size: 0.7em;
  font-weight: 700;
  border-radius: 9px;
  padding: 0 5px;
  min-width: 14px;
  text-align: center;
  box-shadow: 0 2px 6px #0002;
}
.header-avatar img {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #fff;
  box-shadow: 0 1px 8px rgba(67,160,71,0.12);
  margin-left: 4px;
}

@media (max-width: 700px) {
  .header { flex-direction: column; align-items: flex-start; padding: 8px 4vw 8px 4vw;}
  .header-icons { margin-top: 8px;}
}
.back-btn {
  position: absolute;
  top: 16px;
  left: 16px;
  width: 38px;
  height: 38px;
  background: rgba(255,255,255,0.95);
  border-radius: 50%;
  box-shadow: 0 2px 10px #0002;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: background 0.2s, box-shadow 0.2s;
  z-index: 5;
}
.back-btn:hover {
  background: #e0f2f1;
  box-shadow: 0 4px 16px #43e97b55;
}
.back-arrow {
  font-size: 1.5em;
  color: #43a047;
  font-weight: bold;
  line-height: 1;
  display: block;
  margin-left: 2px;
}
.profile-pic-block {
  position: relative; /* Needed for absolute positioning of the button */
}
.profile-pic-block {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 32px;
}

.back-btn {
  position: absolute;
  /* Vertically center with the image */
  top: 50%;
  left: -28px;   /* Negative value moves it outside to the left */
  transform: translateY(-50%);
  width: 38px;
  height: 38px;
  background: rgba(255,255,255,0.95);
  border-radius: 50%;
  box-shadow: 0 2px 10px #0002;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: background 0.2s, box-shadow 0.2s;
  z-index: 5;
}
.back-btn:hover {
  background: #e0f2f1;
  box-shadow: 0 4px 16px #43e97b55;
}
.back-arrow {
  font-size: 1.5em;
  color: #43a047;
  font-weight: bold;
  line-height: 1;
  display: block;
  margin-left: 2px;
}
/* Dropdown for profile image (hover to show logout) */
.profile-dropdown {
  position: relative;
  display: inline-block;
}
.profile-dropdown-menu {
  display: none;
  position: absolute;
  right: 0;
  top: 90%;
  min-width: 120px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 18px #0002;
  z-index: 99;
  padding: 8px 0;
  text-align: left;
}
.profile-dropdown-menu a {
  display: block;
  padding: 10px 18px;
  color: #333;
  text-decoration: none;
  font-weight: 600;
  border-radius: 7px;
  transition: background 0.15s;
}
.profile-dropdown-menu a:hover {
  background: #f1f8e9;
  color: #43a047;
}
.profile-dropdown:hover .profile-dropdown-menu,
.header-avatar:hover .profile-dropdown-menu {
  display: block;
}

  </style>
</head>
<body>
  <div class="sidebar">
    <div class="profile-pic-block">
    <a href="home-1.php" class="back-btn" title="Back to Home">
  <span class="back-arrow">&#8592;</span>
</a>

<img src="<?= $user && $user['image'] ? '../server-side/imgsource/' . htmlspecialchars($user['image']) : 'https://i.pravatar.cc/100?img=5' ?>" alt="Profile" class="profile-pic" id="profilePic">
      <div style="margin-top:8px;font-weight:600"><?= htmlspecialchars($user['name'] ?? '') ?></div>
    </div>
    <ul>
      <li class="active" data-page="profile_overview.php"><span class="icon">👨🏻‍💼</span><span class="label">My Profile</span></li>
      <li data-page="profile_edit.php"><span class="icon">👤</span><span class="label">Edit Account</span></li>
      <li data-page="orders.php"><span class="icon">📦</span><span class="label">Order History</span></li>
      <li data-page="profile_soon.php"><span class="icon">🏠</span><span class="label">Manage Address</span></li>
      <li data-page="profile_soon.php"><span class="icon">❤️</span><span class="label">Favourites</span></li>
      <li data-page="profile_soon.php"><span class="icon">💳</span><span class="label">Payment Options</span></li>
      <li data-page="profile_soon.php"><span class="icon">🔒</span><span class="label">Security</span></li>
      <li data-page="profile_soon.php"><span class="icon">❓</span><span class="label">Help & Support</span></li>
      <li class="preferences-link" data-page="profile_soon.php"><span class="icon">⚙️</span><span class="label">Preferences</span></li>
    </ul>
  </div>
  <div class="main">
  <div class="header">
  <div class="header-left">
    <span class="header-logo">👋🏻</span>
    <span class="header-title" id="headerTitle">Dashboard</span>
    <span class="header-subtitle">Welcome back,</span>
    <span class="header-user-chip"><?= htmlspecialchars($user['name'] ?? '') ?></span>
  </div>
  <div class="header-icons">
    <span class="header-icon msg-icon" title="Messages">💬<span class="badge">2</span></span>
    <span class="header-icon ring-icon" title="Notifications">🔔<span class="badge">5</span></span>
    <div class="header-avatar profile-dropdown">
    <img src="<?= $user && $user['image'] ? '../server-side/imgsource/' . htmlspecialchars($user['image']) : 'https://i.pravatar.cc/100?img=5' ?>" alt="Profile" />
  <div class="profile-dropdown-menu">
    <a href="user_logout.php">Logout</a>
  </div>
</div>
  </div>
</div>

    <div id="mainContent"></div>
  </div>
  <script>
function attachOrderCardListeners() {
  document.querySelectorAll('.order-card').forEach(function(card) {
    card.addEventListener('click', function() {
      document.querySelectorAll('.order-card').forEach(el => el.classList.remove('selected'));
      card.classList.add('selected');
      var orderId = card.getAttribute('data-order-id');
      var panel = document.getElementById('orderDetailsPanel');
      panel.innerHTML = '<div style="color:#888;padding:40px 0;text-align:center;">Loading...</div>';
      fetch('order_details.php?order_id=' + encodeURIComponent(orderId))
        .then(resp => resp.text())
        .then(html => { panel.innerHTML = html; });
    });
  });
}

function handleProfileEditFormSubmission() {
  var form = document.querySelector('#mainContent #editAccountForm');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(form);
    fetch('profile_edit.php', {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(resp => resp.text())
    .then(html => {
      if (html.trim() === "RELOAD_DASHBOARD") {
        window.location.reload();
      } else {
        document.getElementById('mainContent').innerHTML = html;
        handleProfileEditFormSubmission();
      }
    });
  });
  var imgInput = document.querySelector('#mainContent #image');
  if (imgInput) {
    imgInput.addEventListener('change', function(event) {
      var output = document.getElementById('imgPreview');
      output.src = URL.createObjectURL(event.target.files[0]);
    });
  }
}

function afterMainContentLoad() {
  handleProfileEditFormSubmission();
  if (typeof attachOrderCardListeners === 'function') attachOrderCardListeners();
}

function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

document.addEventListener('DOMContentLoaded', function() {
  // Sidebar AJAX navigation
  function setActive(li) {
    document.querySelectorAll('.sidebar ul li').forEach(el => el.classList.remove('active'));
    li.classList.add('active');
    document.getElementById('headerTitle').textContent = li.querySelector('.label').textContent;
  }

  // --- This loop should be OUTSIDE setActive ---
  document.querySelectorAll('.sidebar ul li').forEach(li => {
    li.addEventListener('click', function() {
      setActive(li);
      var page = li.getAttribute('data-page');
      // --- Update the URL in the address bar ---
      let pageParam = '';
      if (page === 'orders.php') pageParam = 'orders';
      else if (page === 'profile_overview.php') pageParam = 'profile';
      else if (page === 'profile_edit.php') pageParam = 'edit';
      // Add more as needed for other sections

      if (pageParam) {
        history.pushState({}, '', 'profile_dashboard.php?page=' + pageParam);
      } else {
        history.pushState({}, '', 'profile_dashboard.php');
      }
      // --- End URL update ---

      document.getElementById('mainContent').innerHTML = '<div style="padding:40px;text-align:center;">Loading...</div>';
      fetch(page)
        .then(resp => resp.text())
        .then(html => {
          document.getElementById('mainContent').innerHTML = html;
          afterMainContentLoad();
        });
    });
  });

  // --- Check URL param to select correct sidebar item on page load ---
  var pageParam = getUrlParameter('page');
  var found = false;
  if (pageParam) {
    document.querySelectorAll('.sidebar ul li').forEach(li => {
      var label = li.querySelector('.label').textContent.trim().toLowerCase();
      var dataPage = li.getAttribute('data-page');
      if (
        (pageParam === 'orders' && (label === 'order history' || dataPage === 'orders.php')) ||
        (pageParam === 'profile' && (label === 'my profile' || dataPage === 'profile_overview.php')) ||
        (pageParam === 'edit' && (label === 'edit account' || dataPage === 'profile_edit.php'))
      ) {
        li.click();
        found = true;
      }
    });
  }
  // If no param or not found, default to profile overview
  if (!found) {
    // Optionally update URL to profile
    history.replaceState({}, '', 'profile_dashboard.php?page=profile');
    fetch('profile_overview.php')
      .then(resp => resp.text())
      .then(html => {
        document.getElementById('mainContent').innerHTML = html;
        afterMainContentLoad();
      });
  }
});

// --- Optional: Handle browser back/forward navigation ---
window.addEventListener('popstate', function() {
  var pageParam = getUrlParameter('page');
  var found = false;
  if (pageParam) {
    document.querySelectorAll('.sidebar ul li').forEach(li => {
      var label = li.querySelector('.label').textContent.trim().toLowerCase();
      var dataPage = li.getAttribute('data-page');
      if (
        (pageParam === 'orders' && (label === 'order history' || dataPage === 'orders.php')) ||
        (pageParam === 'profile' && (label === 'my profile' || dataPage === 'profile_overview.php')) ||
        (pageParam === 'edit' && (label === 'edit account' || dataPage === 'profile_edit.php'))
      ) {
        li.click();
        found = true;
      }
    });
  }
  if (!found) {
    fetch('profile_overview.php')
      .then(resp => resp.text())
      .then(html => {
        document.getElementById('mainContent').innerHTML = html;
        afterMainContentLoad();
      });
  }
});
</script>

</body>
</html>
