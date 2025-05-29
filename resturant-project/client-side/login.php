<?php
session_name('ADMINSESSID'); // Set session name BEFORE session_start!
session_start();
include_once("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>admin-login</title>
 
  <link rel="stylesheet" >
  <link rel="website icon" href="picure/amazon-weblogo.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
.box {
    margin-left: 1rem;
    margin-right: 1rem;
    padding: 15px;
    margin-top: 15px;
    
    height: 400px;
    width: 20%;
    background-color: white;

}
.login {
    background: transparent;
    width: 10%;
    align-self: cover center;
    transition: .1s;

}.box-1 {
    margin-top:200px;
    position: relative;
    border-radius: 10px;
    border: 1px solid black;
    background-color: white;
    width: 50%;
    border-color: rgb(15, 1, 1);
    align-items: space-evenly;

}

.button-1 {
    background-color: rgba(240, 217, 12, 0.74);
    border-color: white;
    color: #0f1111;
    border-radius: 1px solid;
    width: 30%;
    padding-top: 5px;
    padding-bottom: 5px;

}</style>

</head>

<body background="photo/beautiful.jpg" >
<form name="form1" action="#" method="post"  autocomplete="off">
  <br>
 
  <br style="height: 20%;"/>
  <form align="center" action="dashboard.php" method="post" onsubmit="return  onsubmitdata()">
    <center>


      <div class="box-1"><br>
        <h1><u>Admin Log-in</u></h1><hr/><br/><br/>
        <div>
            <label><b>Enter your username</b></label><br/>
         
            <i class='fa-duotone fa-solid fa-user'></i>  <input type="text" class="username" name="username"  required id="input-1" ></div><br/><br/>
      
        <div><b>
       <label>Enter your password</label><br/>
          
       <i class="fa-solid fa-lock"></i> <input type="password" class="username" name="password"  required id="input-2"></div><br><br>
         <br>
          <div><input type="submit" class="button-1" value="Login" name="submit"></div><br>
        </div>
    </center>
  </form>


</body>

</html>
<?php 
include("connection.php");
$servername= "localhost";
$username="root";
$password="";
$dbname="resturant_project";
// create a connection
$conn=mysqli_connect($servername,$username,$password,$dbname);

if(isset($_POST['submit'])){
  $conn=mysqli_connect($servername,$username,$password,$dbname);
  $username=$_POST['username'];
  $pwd=$_POST['password'];
  $query="SELECT * FROM `admin` WHERE `username` ='$username' && `password` ='$pwd' ";
  $data=mysqli_query($conn,$query);
  
  $total=mysqli_num_rows($data);
  if($total == 1){
    session_start();
      $_SESSION['username']=$username;
  header("location:dashboard.php");
}
else{
  echo"<center><h1 style='text-align:center;color:red;border:1px solid white; padding:5px;:white;width:25%;justify-self:center;background:white;border: 1px solid red ;border-radius:2px 2px 2px 2px solid red;'>login failed</h1></center>";
}}