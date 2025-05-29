<?php
$servername="localhost";
$username="root";
$password="";
$dbname="resturant_project";

$id = $_GET["id"];






$conn=mysqli_connect($servername,$username,$password,$dbname);

$update="delete from pizza where id='$id'";

$run=mysqli_query($conn,$update);
if($run){
	echo"yes";
}
else{
	echo "no";
}
$ans = "";
if($run) {
   
	$ans = "Deleted";
    header("location:showpizza.php?ans=$ans");
}
else {
	$ans = "Not Deleted";
   
}