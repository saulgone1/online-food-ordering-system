<?php
session_name('ADMINSESSID');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("connection.php");

// Handle login logic
$login_failed = false;
if (isset($_POST['submit'])) {
    $admin_username = $_POST['username'];
    $admin_pwd = $_POST['password'];
    $query = "SELECT * FROM `admin` WHERE `username` ='$admin_username' && `password` ='$admin_pwd'";
    $data = mysqli_query($conn, $query);
    $total = mysqli_num_rows($data);
    if ($total == 1) {
        $row = mysqli_fetch_assoc($data);
        $_SESSION['username'] = $row['username'];
        $_SESSION['admin_id'] = $row['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $login_failed = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="website icon" href="picure/amazon-weblogo.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: url('photo/beautiful.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
      flex-direction: column;
    }
    .box-1 {
      border-radius: 10px;
      border: 1px solid #ddd;
      background-color: rgba(255, 255, 255, 0.85);
      width: 80%;
      max-width: 700px;
      padding: 30px 40px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 18px;
    }
    .button-1 {
      background-color: rgba(240, 217, 12, 0.74);
      border-color: white;
      color: #0f1111;
      width: 30%;
      padding: 5px 0;
      cursor: pointer;
      border: none;
      font-weight: bold;
      border-radius: 4px;
    }
    .error-message {
      background-color: #f2dede;
      color: #a94442;
      border: 1px solid #ebccd1;
      padding: 14px;
      margin: 15px auto;
      width: 60%;
      text-align: center;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      font-size: 16px;
    }
    input.username {
      width: 70%;
      padding: 6px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .back-home-btn {
      display: inline-block;
      padding: 10px 28px;
      background: #4CAF50;
      color: #fff;
      border-radius: 6px;
      font-size: 1.05em;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 2px 10px #4caf5022;
      transition: background 0.18s;
      margin-top: 24px;
      text-align: center;
    }
    .back-home-btn:hover {
      background: #388e3c;
      color: #fff;
      text-decoration: none;
    }
    @media (max-width: 600px) {
      .box-1 { padding: 18px 4vw; }
      .back-home-btn { font-size: 1em; }
    }
  </style>
</head>
<body>

  <?php if ($login_failed): ?>
    <div class="error-message">
      Login failed. Please check your username and password.
    </div>
  <?php endif; ?>

  <form action="#" method="post" autocomplete="off">
    <div class="box-1">
      <h1><u>Admin Log-in</u></h1>
      <hr /><br />
      <div>
        <label><b>Enter your username</b></label><br />
        <i class="fa-solid fa-user"></i>
        <input type="text" class="username" name="username" required>
      </div><br />

      <div>
        <label><b>Enter your password</b></label><br />
        <i class="fa-solid fa-lock"></i>
        <input type="password" class="username" name="password" required>
      </div><br>

      <div>
        <input type="submit" class="button-1" value="Login" name="submit">
      </div>
    </div>
  </form>

  <!-- Go to Website Button -->
  <div style="text-align: center;">
    <a href="../client-side/home-1.php" class="back-home-btn">
      <i class="fa fa-arrow-left" style="margin-right:8px;"></i> Go to Website
    </a>
  </div>

</body>
</html>
