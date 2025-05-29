<?php
session_name('USERSESSID');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>

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
      background-color: rgba(255, 255, 255, 0.8);
      width: 80%;
      max-width: 700px;
      padding: 30px 40px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
    }

    .logout-message {
      background-color: rgba(255, 255, 255, 0.8);
      color: #3c763d;
      border: 1px solid #d6e9c6;
      padding: 15px;
      margin: 20px auto;
      width: 60%;
      text-align: center;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      font-size: 16px;
    }

    .logout-message a {
      color: rgb(255, 0, 0);
      text-decoration: underline;
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
  </style>
</head>

<body>

  <?php if (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
    <div class="logout-message">
      <strong>You’ve been logged out successfully.</strong><br>
      <a href="home-1.php">Return to Homepage</a>
    </div>
  <?php endif; ?>

  <form action="#" method="post">
    <div class="box-1">
      <h1><u>Log-in</u></h1>
      <hr /><br />
      <div>
        <label><b>Enter your Email-address</b></label><br />
        <input type="hidden" name="name" required>
        <input type="hidden" name="image" required>
        <i class="fa-solid fa-user"></i>
        <input type="text" class="username" name="username" required>
      </div><br />

      <div>
        <label><b>Enter your password</b></label><br />
        <i class="fa-solid fa-lock"></i>
        <input type="password" class="username" name="password" required>
      </div><br>

      <div>
        <p>
          <input type="checkbox">
          <b>Don't have an account?</b>
          <a href="Sign-up-form.php" class="register-link">Sign up</a>
        </p>
        <input type="submit" class="button-1" value="Login" name="submit">
      </div>
    </div>
  </form>

  <!-- Back to Homepage Button -->
  <div style="text-align: center;">
    <a href="home-1.php" class="back-home-btn">
      <i class="fa fa-arrow-left" style="margin-right:8px;"></i> Back to Homepage
    </a>
  </div>

  <?php
  // Handle login logic
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "resturant_project";

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $username = $_POST['username'];
    $pwd = $_POST['password'];

    $query = "SELECT * FROM `user_signup` WHERE `email` ='$username' && `password` ='$pwd'";
    $data = mysqli_query($conn, $query);

    $total = mysqli_num_rows($data);
    if ($total == 1) {
      $user_data = mysqli_fetch_assoc($data);

      // session already started at top!
      $_SESSION['user_id'] = $user_data['ID'];
      $_SESSION['name'] = $user_data['name'];
      $_SESSION['image'] = $user_data['image'];
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $pwd;
      header("Location: home-1.php");
      exit;
    } else {
      echo "<div class='logout-message' style='background-color:#f2dede; color:#a94442; border-color:#ebccd1;'>
              Login failed. Please try again.
            </div>";
    }
  }
  ?>

  <script>
    window.onload = function() {
      const logoutMessage = document.querySelector('.logout-message');
      if (logoutMessage) {
        setTimeout(function() {
          logoutMessage.style.display = 'none';
        }, 10000);
      }
    };
  </script>

</body>
</html>
