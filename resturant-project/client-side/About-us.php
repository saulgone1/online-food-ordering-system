<?php
session_name('USERSESSID'); // Set session name first!
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home-1.css">
    <title>About us</title>
    <style>
        *{
            margin:0;
            padding:0;
        }
        .emailbox1{
            padding:0;
            background-size: cover;
            height: 20pc;
            width: 100%;
            background-image:url(photo/slider1.jpg);
            width:auto;
            height:50pc;
            position: relative;
        }
        .logout{
            background-image:url(photo/beautiful.jpg);
            background-size: cover;
            width: 100%;
            height:30pc;
            position: relative;
        }
        .emailbox2{
            width:10pc;
            justify-content: center;
            justify-self: center;
            align-items: center;
            margin:20px;
            padding:20px;
            position: relative;
        }
        .emailbox3{
            border:1px solid yellow;
            box-shadow: 5px 5px 10px 1px grey;
            border-radius:100px 0px 100px 0px;
            background-color:transparent;
            justify-self: center;
            justify-content: center;
            text-align: center;
            width:50pc;
            padding:50px;
            align-items: center;
            align-self:center;
            position: relaive;
        }
        .emailbox3 input{
            border-radius:100px 100px 100px 100px;
            padding:5px;
            border-color: yellow;
        }
        .emailbox3 .submit{
            background-color: yellow;
        }
        .form{
            width:100%;
            height:100%;
            background-color: rgba(0, 0, 0, 0.5);
            box-shadow: inset -5px -5px -5px -5px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
<?php
    // Session is not required for public access, but you may start it if you want to show user info
    // if(session_status() === PHP_SESSION_NONE){
    //   session_start(); 
    // }

    include_once('header.php');
    include_once('About_us_frontlayout.php');
?>
<br/>

<h1 style="text-align:center;">Our Team Members</h1>
<center>
<hr style="width:21%;justify-self:center;background-color:#02A237;margin-top:5px;border:4px dashed white;align:center;">
<br/>
<?php 
$mysqli = new mysqli('localhost','root','','resturant_project');
$table='teammembers';

$result=$mysqli->query("SELECT * FROM $table") or die($mysqli->error);

while($data = $result->fetch_assoc()){ 
    echo "<span style='margin-left:5px; margin-right:5px;'>";
    echo "<img src='http://localhost/php_training/resturant-project/server-side/imgsource/%20{$data['imgsource']}' style='border:1px solid purple;background-color:black; border-radius:100%;padding:10px; box-shadow:2px solid grey;' height='200px' width='200px' alt='file not found'/>";
    echo "</span>";
}

echo "<h1 style='text-align:center;'>Just Swigy Us</h1>";
echo "<hr style='width:21%;justify-self:center;background-color:#02A237;margin-top:5px;border:4px dashed white;align:center;'>
<br/>
<video autoplay muted controls src='photo/WhatsApp-Video-2024-11-18-at-15.49.51.mp4' style='border:1px solid black;border-radius:2%;'></video>";
?>
<?php include_once('footer-2.php'); ?>
</center>
</body>
</html>
