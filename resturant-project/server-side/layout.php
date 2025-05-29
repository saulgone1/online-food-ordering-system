<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once 'connection.php';

// SESSION CHECK: Add this block right here!
if (!isset($_SESSION['admin_id'])) {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Access Denied</title>
      <style>
          body {
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              min-height: 100vh;
              background: #fbe9e7;
              font-family: 'Segoe UI', Arial, sans-serif;
          }
          .error-box {
              background: #fff;
              border: 2px solid #d32f2f;
              color: #d32f2f;
              border-radius: 10px;
              padding: 40px 30px;
              box-shadow: 0 2px 12px #d32f2f22;
              text-align: center;
          }
          .error-icon {
              font-size: 4em;
              margin-bottom: 12px;
          }
          .error-msg {
              font-size: 1.2em;
              margin-bottom: 16px;
          }
          .login-link {
              display: inline-block;
              margin-top: 10px;
              padding: 8px 20px;
              background: #d32f2f;
              color: #fff;
              border-radius: 6px;
              text-decoration: none;
              font-weight: 600;
              transition: background 0.2s;
          }
          .login-link:hover {
              background: #b71c1c;
          }
      </style>
  </head>
  <body>
      <div class="error-box">
          <div class="error-icon">⛔</div>
          <div class="error-msg">Access Denied!<br>You must be logged in as admin to view this page.</div>
          <a href="login.php" class="login-link">Go to Login</a>
      </div>
  </body>
  </html>
  <?php
  exit;
}
// END SESSION CHECK

$admin_id = $_SESSION['admin_id'] ?? null;
$admin = [
    'name' => 'Admin',
    'image' => null
];

if ($admin_id) {
    $query = "SELECT username, image FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $admin['name'] = $row['username'];
        $admin['image'] = $row['image'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
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
      min-height: 100vh;
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
    .sidebar ul li a {
      color: inherit;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 16px;
      width: 100%;
      height: 100%;
    }
    .sidebar .logout-link {
      margin-top: auto;
      margin-bottom: 8px;
      color: var(--danger);
    }
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      min-height: 100vh;
    }
    /* --- Modern Navbar/Header --- */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      padding: 10px 32px;
      box-shadow: 0 6px 32px rgba(34,139,34,0.08);
      border-radius: 0 0 18px 18px;
      position: relative;
      z-index: 10;
      min-height: 56px;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .header-logo {
      font-size: 1.5em;
      margin-right: 8px;
      filter: drop-shadow(0 2px 6px #fff8);
    }
    .header-title {
      font-size: 1.12em;
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
      aspect-ratio: 1 / 1;
      border-radius: 50%;
      object-fit: cover;
      background: #fff;
      border: 2px solid #fff;
      box-shadow: 0 1px 8px rgba(67,160,71,0.12);
      margin-left: 4px;
      display: block;
    }
    #mainContent { padding: 40px 32px; min-height: 80vh; }
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
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }
    .profile-dropdown img {
      cursor: pointer;
      border: 2px solid #fff;
      transition: box-shadow 0.18s;
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
    .profile-dropdown:hover .profile-dropdown-menu {
      display: block;
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
      top: 50%;
      left: -44px;
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
    /* --- Floating Back to Top Button --- */
    #floatingBackToTop {
      position: fixed;
      right: 28px;
      bottom: 34px;
      width: 56px;
      height: 56px;
      background: none;
      border: none;
      border-radius: 50%;
      box-shadow: 0 4px 18px rgba(76, 175, 80, 0.14);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 0;
      cursor: pointer;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    #floatingBackToTop:hover {
      box-shadow: 0 8px 28px rgba(56, 142, 60, 0.20);
      transform: translateY(-4px) scale(1.08);
    }
    #floatingBackToTop svg {
      display: block;
      width: 100%;
      height: 100%;
    }
    @media (max-width: 600px) {
      #floatingBackToTop {
        right: 14px;
        bottom: 18px;
        width: 44px;
        height: 44px;
      }
    }
    /* --- End Floating Back to Top Button --- */
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="profile-pic-block">
      <a href="../client-side/home-1.php" class="back-btn" title="Back to Home">
        <span class="back-arrow">&#8592;</span>
      </a>
      <img src="<?= $admin['image'] ? 'uploads/' . htmlspecialchars($admin['image']) : 'https://img.icons8.com/ios-filled/100/000000/user.png' ?>" alt="Profile" class="profile-pic">
      <div style="margin-top:8px;font-weight:600"><?= htmlspecialchars($admin['name']) ?></div>
    </div>
    <ul>
      <li class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
        <a href="dashboard.php"><span class="icon">📊</span><span class="label">Dashboard</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'users' ? 'active' : '' ?>">
        <a href="user-info.php"><span class="icon">🌐</span><span class="label">Users</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'contacts' ? 'active' : '' ?>">
        <a href="show_contact.php"><span class="icon">📇</span><span class="label">Contacts</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'featured' ? 'active' : '' ?>">
        <a href="Show_featured.php"><span class="icon">⭐</span><span class="label">Featured</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'newsletter' ? 'active' : '' ?>">
        <a href="serverside-news_letter.php"><span class="icon">📰</span><span class="label">Newsletter</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'teammembers' ? 'active' : '' ?>">
        <a href="showteam_members.php"><span class="icon">👥</span><span class="label">Team Members</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'orders' ? 'active' : '' ?>">
        <a href="show_order.php"><span class="icon">🧾</span><span class="label">Orders</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'fooditems' ? 'active' : '' ?>">
        <a href="food-list.php"><span class="icon">🍔</span><span class="label">Food-items</span></a>
      </li>
      <li class="<?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>">
        <a href="profile.php"><span class="icon">👤</span><span class="label">Profile</span></a>
      </li>
      <li class="logout-link <?= ($activePage ?? '') === 'logout' ? 'active' : '' ?>">
        <a href="admin_logout.php"><span class="icon">🚪</span><span class="label">Logout</span></a>
      </li>
    </ul>
  </div>
  <div class="main">
    <div class="header">
      <div class="header-left">
        <span class="header-logo">👨🏻‍💼</span>
        <span class="header-title"><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></span>
        <span class="header-subtitle">Welcome back,</span>
        <span class="header-user-chip"><?= htmlspecialchars($admin['name']) ?></span>
      </div>
      <div class="header-icons">
        <span class="header-icon msg-icon" title="Messages">💬<span class="badge">7</span></span>
        <span class="header-icon ring-icon" title="Notifications">🔔<span class="badge">7</span></span>
        <div class="header-avatar profile-dropdown">
          <img src="<?= $admin['image'] ? 'uploads/' . htmlspecialchars($admin['image']) : 'https://img.icons8.com/ios-filled/100/000000/user.png' ?>" alt="Profile" />
          <div class="profile-dropdown-menu">
            <?php if ($admin_id): ?>
              <a href="admin_logout.php">Logout</a>
            <?php else: ?>
              <a href="login.php">Login</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div id="mainContent">
      <?php
        if (isset($content)) {
          echo $content;
        } else {
          echo "<h2>Welcome to the Admin Dashboard</h2><p>Choose an option from the sidebar.</p>";
        }
      ?>
    </div>
  </div>

  <!-- Floating Modern Back to Top Button (arrow in a circle) -->
  <button id="floatingBackToTop" title="Back to top" aria-label="Back to top">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
      <circle cx="16" cy="16" r="15" stroke="#4CAF50" stroke-width="2" fill="white"/>
      <polyline points="10,18 16,12 22,18" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
  <script>
    // Floating Back to Top Button Logic
    const floatingBackToTop = document.getElementById('floatingBackToTop');
    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        floatingBackToTop.style.display = 'flex';
      } else {
        floatingBackToTop.style.display = 'none';
      }
    });
    floatingBackToTop.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  </script>
</body>
</html>
