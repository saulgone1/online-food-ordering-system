<?php
// connecting to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resturant_project";

// create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
