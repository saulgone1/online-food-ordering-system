<?php
include_once("connection.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-up-form</title>
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
      background-color: rgba(255, 255, 255, 0.88);
      width: 95%;
      max-width: 900px;
      padding: 30px 50px 20px 50px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.10);
      margin-top: 40px;
    }
    .signup-form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 22px 36px;
      width: 100%;
    }
    .signup-form-col {
      display: flex;
      flex-direction: column;
    }
    .button-row {
      grid-column: 1 / span 2;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .button-1 {
      background-color: rgba(240, 217, 12, 0.74);
      border-color: white;
      color: #0f1111;
      width: 40%;
      padding: 10px 0;
      cursor: pointer;
      border: none;
      font-weight: bold;
      border-radius: 6px;
      font-size: 1.07em;
      margin-top: 6px;
      margin-bottom: 10px;
      box-shadow: 0 2px 8px #fbc02d22;
      transition: background 0.18s;
    }
    .button-1:hover {
      background: #fbc02d;
      color: #fff;
    }
    .form-error {
      color: #a94442;
      background: #f2dede;
      border: 1px solid #ebccd1;
      border-radius: 7px;
      padding: 12px 10px;
      margin-bottom: 12px;
      width: 100%;
      text-align: center;
      font-weight: 600;
    }
    .form-success {
      color: #3c763d;
      background: #dff0d8;
      border: 1px solid #d6e9c6;
      border-radius: 7px;
      padding: 12px 10px;
      margin-bottom: 12px;
      width: 100%;
      text-align: center;
      font-weight: 600;
    }
    .signup-label {
      font-weight: 600;
      color: #222;
      margin-bottom: 4px;
      display: block;
      margin-top: 10px;
    }
    .signup-input,
    .signup-file {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background: #f8fafc;
      font-size: 1em;
      margin-bottom: 10px;
      transition: border 0.2s;
    }
    .signup-input:focus {
      border: 1.5px solid #1976d2;
      outline: none;
    }
    .signup-file {
      padding: 4px 0;
      background: none;
    }
    .signin-link {
      font-size: 0.97em;
      color: #1976d2;
      text-decoration: none;
      font-weight: 600;
      margin-left: 6px;
      transition: color 0.18s;
    }
    .signin-link:hover {
      color: #0d47a1;
      text-decoration: underline;
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
    @media (max-width: 900px) {
      .box-1 {
        padding: 18px 2vw 18px 2vw;
      }
      .signup-form-grid {
        grid-template-columns: 1fr;
        gap: 12px 0;
      }
      .button-row {
        grid-column: 1;
      }
    }
    @media (max-width: 600px) {
      .box-1 {
        width: 99vw;
        padding: 12px 1vw 12px 1vw;
      }
      .signup-input,
      .signup-file {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <form action="#" method="post" enctype="multipart/form-data">
    <div class="box-1">
      <h1><u>Sign-up</u></h1>
      <hr /><br />

      <?php
      if(isset($_POST['submit'])){
        $input1 = $_POST["input-1"];
        $input2 = $_POST["input-2"];
        $input3 = $_POST["input-3"];
        $input4 = $_POST["input-4"];
        $input6 = $_POST["input-6"];
        $input5 = "";

        // Handle image upload
if(isset($_FILES['input-5']) && $_FILES['input-5']['error'] == 0){
  $file_name = basename($_FILES['input-5']['name']);
  $file_tmp = $_FILES['input-5']['tmp_name'];
  $target_dir = "../server-side/imgsource/";
  $target_file = $target_dir . $file_name;
  if(move_uploaded_file($file_tmp, $target_file)){
    $input5 = $file_name;
    echo "<div class='form-success'>Image uploaded successfully.</div>";
  } else {
    echo "<div class='form-error'>Image upload failed.</div>";
  }
}


        $conn = mysqli_connect('localhost', 'root', '', 'resturant_project');
        $qry = "SELECT * FROM `user_signup` where email ='{$input3}' ";
        $result = mysqli_query($conn, $qry);
        $count = mysqli_num_rows($result);
        if($count > 0){
          echo "<div class='form-error'>Email already exists.</div>";
        } else {
          $insert = "INSERT INTO user_signup (`name`, `phone`, `email`, `dob`, `image`, `password`) VALUES ('$input1','$input2','$input3','$input4','$input5','$input6')";
          $run = mysqli_query($conn, $insert);
          if($run) {
            echo "<div class='form-success'>You created your account successfully!</div>";
          } else {
            echo "<div class='form-error'>Account not created. Please try again.</div>";
          }
        }
      }
      ?>

      <div class="signup-form-grid">
        <div class="signup-form-col">
          <label class="signup-label" for="input-1"><i class="fa-solid fa-user"></i> Full Name</label>
          <input type="text" name="input-1" id="input-1" class="signup-input" required>

          <label class="signup-label" for="input-3"><i class="fa-solid fa-envelope"></i> Email Address</label>
          <input type="email" name="input-3" id="input-3" class="signup-input" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">

          <label class="signup-label" for="input-5"><i class="fa-solid fa-image"></i> Upload Your Image</label>
          <input type="file" name="input-5" id="input-5" class="signup-file" accept="image/*" required>
        </div>
        <div class="signup-form-col">
          <label class="signup-label" for="input-2"><i class="fa-solid fa-phone"></i> Phone Number</label>
          <input type="number" name="input-2" id="input-2" class="signup-input" maxlength="10" placeholder="10-digit number" required>

          <label class="signup-label" for="input-4"><i class="fa-solid fa-calendar"></i> Date of Birth</label>
          <input type="date" name="input-4" id="input-4" class="signup-input" required>

          <label class="signup-label" for="input-6"><i class="fa-solid fa-lock"></i> Create Password</label>
          <input type="password" name="input-6" id="input-6" class="signup-input" required>
        </div>
        <div class="button-row">
          <input type="submit" class="button-1" value="Continue" name="submit">
          <div style="margin-top:6px;">
            <span style="font-size:0.97em;">Already have an account?</span>
            <a href="user_login.php" class="signin-link">Sign in</a>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div style="text-align: center;">
    <a href="home-1.php" class="back-home-btn">
      <i class="fa fa-arrow-left" style="margin-right:8px;"></i> Back to Homepage
    </a>
  </div>

</body>
</html>
