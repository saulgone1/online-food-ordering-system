<?php include_once("connection.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
<?php

if(isset($_FILES['image'])){
  echo"<pre>";
  print_r($_FILES);
  echo"</pre>";
  if(isset($_POST['submit'])){
  $title=$_POST['title'];}
  if(isset($_POST['submit'])){
  $price=$_POST['price'];
  }
   $file_name=$_FILES['image']['name'];
   $file_size=$_FILES['image']['size'];
  $file_tmp=$_FILES['image']['tmp_name'];
  $file_type=$_FILES['image']['type'];
  if(move_uploaded_file($file_tmp,".\imgsource\ ".$file_name)){
    echo"<script>alert('file  uploaded successfully');</script>";


}else{
  echo"<script>alert('file not uploaded');</script>";
}
$servername="localhost";
$uername="root";
$password="";
$dbname="resturant_project";
$conn=mysqli_connect($servername,$username,$password,$dbname);

$query="INSERT INTO `dosa`( `title`, `price`, `image`) VALUES ('$title',$price,'$file_name')";

$run=mysqli_query($conn,$query);

        if($run) {
                     echo"your photo has been  uploded succesfully";
                 }
          else   {
                   echo "no";
                 }
} ?>

</body>
</html>