<?php
$servername="localhost";
$username="root";
$password="";
$dbname="resturant_project";

$id = $_GET["id"];






$conn=mysqli_connect($servername,$username,$password,$dbname);

$update="delete from featured_food where id='$id'";

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
    header("location:Show_featured.php?ans=$ans");
}
else {
	$ans = "Not Deleted";
   
}