
<?php
// connectiog to the database
$servername= "localhost";
$username="root";
$password="";
$dbname="resturant_project";
// create a connection
$c=mysqli_connect($servername,$username,$password,$dbname);

if(isset($_POST['submit'])){
 
  $email=$_POST['email'];
    $qry = "SELECT * FROM news_letter where email ='$email' ";
   $result=mysqli_query($c,$qry);
   $count=mysqli_num_rows($result);
   if($count>0){
    
     header("location:email_already_exits.php?GET['email']");
  
   }
   else{
    
   echo $insert="INSERT INTO `news_letter` ( `email`) VALUES ( '$email') ";
 
 
     $run=mysqli_query($c,$insert);
      $ans = "";
        if($run) {
                     $ans = "Saved";
                   
                     header("location:home-1.php");
                 }
          else   {
                      $ans = "Not Saved";
                 }
           
}}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>