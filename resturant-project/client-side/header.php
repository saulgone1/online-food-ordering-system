<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start(); 
    }
    error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="home-1.css"/>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .header {
            width: 100%;
            display: flex;
            align-items: center;
            color: white;
            background-color: green;
            padding: 5px 10px;
            gap: 10px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            font-size: 1.1rem;
            height: 50px;
        }

        .nav-logo {
            flex: 2;
            min-width: 250px;
            max-width: 250px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-left: 10px;
        }

        .nav-logo .nav-second {
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .nav-address {
            flex: 0 0 100px;
            min-width: 80px;
        }

        .logo {
            border: 1px solid purple;
            background-color: black;
            border-radius: 100%;
            padding: 5px;
            background-image: url('file:///C:/xampp/htdocs/php_training/resturant-project/client-side/photo/chef2.png');
            background-size: cover;
            height: 16px;
            width: 16px;
        }

        .add-icon {
            display: flex;
        }

        .add-first {
            color: #cccccc;
            font-size: 1rem;
            margin-left: 10px;
        }

        .add-second {
            color: white;
            font-size: 1rem;
        }

        .nav-search-all {
            display: flex;
            height: 30px;
        }

        .nav-search {
            display: flex;
            width: 550px;
            height: 30px;
            border-color: orange;
        }

        .search-input {
            width: 100%;
            font-size: 1rem;
            border: none;
        }

        .search-select {
            background-color: #f3f3f3;
            text-align: center;
            border-top-left-radius: 4px;
            width: 50px;
            border-radius: 4px;
            border: none;
        }

        .search-icon {
            display: flex;
            background-color: #fedb58;
            width: 35px;
            height: 30px;
            font-size: 1rem;
            align-items: center;
            justify-content: center;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .nav-sign-in,
        .nav-sign-up {
            width: 120px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: green;
            transition: background-color 0.3s, color 0.3s;
            position: relative;
        }

        .nav-sign-in:hover,
        .nav-sign-up:hover {
            background-color: orange;
        }

        .nav-sign-in a,
        .nav-sign-up a {
            font-size: 1.1rem;
            text-decoration: none;
            color: white;
        }

        .span {
            font-size: 0.9rem;
        }

        /* Dropdown styles */
        .account-menu {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
        }

        .account-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: green;
            min-width: 160px;
            z-index: 1001;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .account-dropdown li {
            padding: 10px;
            text-align: left;
        }

        .account-dropdown li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .account-dropdown li:hover {
            background-color: orange;
        }

        .nav-sign-in:hover .account-dropdown {
            display: block;
        }

        /* Lower Navbar */
        #Menu {
            position: fixed;
            top: 50px;
            left: 0;
            width: 100%;
            background-color: green;
            z-index: 999;
            font-size: 1rem;
        }

        #Menu ul {
            display: flex;
            list-style: none;
            background-color: green;
            margin: 0;
            padding: 0;
        }

        #Menu .ul-li {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            font-size: 1.3rem;
            background-color: green;
            position: relative;
            padding: 0 10px;
        }

        #Menu ul li a {
            text-decoration: none;
            color: white;
            display: block;
            width: 100%;
            height: 100%;
            text-align: center;
            line-height: 50px;
            font-weight: 500;
        }

        #Menu .ul-li:hover {
            background-color: orange;
            transition: background-color 0.3s;
        }

        #Menu .ul-li:hover > a {
            color: white;
        }

        #Menu .ul-li ul {
            display: none;
            position: absolute;
            top: 50px;
            left: 0;
            width: 100%;
            background-color: green;
            flex-direction: column;
        }

        #Menu .ul-li:hover ul {
            display: flex;
        }

        #Menu .ul-li ul li {
            width: 100%;
            height: 45px;
        }

        #Menu .ul-li ul li a {
            line-height: 45px;
            text-align: center;
        }

        #Menu .ul-li ul li:hover {
            background-color: orange;
        }
    </style>
</head>

<body>

<!-- Navigation Header -->
<div class="header">
    <div class="nav-logo border-4">
        <?php 
            $userprofile = isset($_SESSION['username']) ? $_SESSION['username'] : false;
            if (!$userprofile) {
                echo "<img src='photo/person1.png' width='30px' height='30px' />";
                echo "Create your account";
            } else {
                echo "<p><span class='span'>Welcome</span></p>";
                echo "<p class='nav-second' title='$userprofile'>$userprofile</p>";
            }
        ?>
    </div>

    <div class="nav-address border-1">
        <p class="add-first">Deliver to</p>
        <div class="add-icon">
            <i class="fa-solid fa-location-dot"></i>
            <p class="add-second">India</p>
        </div>
    </div>

    <div class="nav-search border-2">
        <div class="nav-search-all">
            <select class="search-select">
                <option>All</option>
            </select>
        </div>
        <input placeholder="Search your dishes" class="search-input">
        <div class="search-icon">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </div>

    <div class="nav-sign-in border-1">
        <div class="account-menu">
            <div>Account <i class="fa-solid fa-angle-down"></i></div>
            <ul class="account-dropdown">
                <?php 
                    if ($userprofile) {
                        echo "<li><a href='profile_dashboard.php'>My Profile</a></li>";
                        echo "<li><a href='profile_dashboard.php?page=orders'>My Orders</a></li>";
                        echo "<li><a href='user_logout.php'>Log out</a></li>";
                    } else {
                        echo "<li><a href='user_login.php'>User Login</a></li>";
                        echo "<li><a href='http://localhost/php_training/resturant-project/server-side/login.php'>Admin Login</a></li>";
                    }
                ?>
            </ul>
        </div>
    </div>

    <div class="nav-sign-up border-1">
        <a href="Sign-up-form.php">
            <?php 
                if ($userprofile) {
                    echo "<p><span class='span'>Sign up</span></p>";
                } else {
                    echo "<p><span class='span'>Create Your Account</span></p>";
                }
            ?>
        </a>
    </div>
</div>

<!-- Lower Navbar -->
<div id="Menu">
    <ul class="ul">
        <li class="ul-li"><a href="home-1.php">Home</a></li>
        <li class="ul-li"><a href="About-us.php">About</a></li>
        <li class="ul-li">
            <a href="foods.php">Foods</a>
            <ul>
                <li><a href="pizza.php">Pizza</a></li>
                <li><a href="dosa.php">Dosa</a></li>
                <li><a href="Cake.php">Cake</a></li>
                <li><a href="icecream.php">Ice Cream</a></li>
            </ul>
        </li>
        <li class="ul-li"><a href="profile_dashboard.php?page=orders">Order</a></li>
        <li class="ul-li"><a href="contact.php">Contacts</a></li>
    </ul>
</div>

</body>
</html>
