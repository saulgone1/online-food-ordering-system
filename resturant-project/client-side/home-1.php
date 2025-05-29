<?php
session_name('USERSESSID'); // Set session name first!
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Project</title>
    <link rel="stylesheet" href="home-1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Slider Container */
.slider-container {
    position: relative;
    max-width: 100%;
    overflow: hidden; /* Hides images that are outside the container */
}

.featured-outer1 {
    display: flex;
    transition: transform 0.5s ease-in-out; /* Smooth transition for sliding */
}

.featured-img {
    min-width: 200px; /* Set width for each image */
    margin-right: 15px; /* Space between images */
}

.featured-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px; /* Optional: to make images look nicer */
}

/* Slider Buttons (Left and Right) */
.slider-btn {
    position: absolute;
    top: 50%;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    font-size: 2rem;
    padding: 10px;
    cursor: pointer;
    z-index: 10;
    border-radius: 50%;
    transform: translateY(-50%);
    transition: background-color 0.3s ease;
}

.slider-btn:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

/* Position the buttons */
.prev-btn {
    left: 10px;
}

.next-btn {
    right: 10px;
}

        /* General Styles */
/* View More Button */

.view-more-container {
    text-align: right;
    margin-top: 20px;
}

.view-more-btn {
    background: linear-gradient(135deg, #02A237, #007b1c); /* Gradient effect */
    color: white;
    padding: 12px 24px;
    font-size: 1.3rem;
    font-weight: bold;
    text-decoration: none;
    border-radius: 50px; /* Rounded corners for a modern look */
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    transition: all 0.3s ease-in-out;
}

.view-more-btn:hover {
    background: linear-gradient(135deg, #02782a, #005a1a); /* Darker gradient on hover */
    transform: translateY(-3px); /* Slight lift effect */
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2); /* More intense shadow for depth */
    letter-spacing: 1px; /* Slightly spread out letters */
}

.view-more-btn:active {
    transform: translateY(1px); /* Subtle effect when clicked */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.view-more-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(2, 162, 55, 0.5); /* Focus state for accessibility */
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Header and Navigation */
        header {
            background-color: #02A237;
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 2rem;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.2rem;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        /* Why Choose Us Section */
        .why-choose-us {
            text-align: center;
            margin: 50px 0;
        }

        .why-choose-us h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .why-choose-us hr {
            width: 20%;
            height: 4px;
            background-color: #02A237;
            border: none;
            margin: 20px auto;
        }

        .featured-outer {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: nowrap;         /* Prevent wrapping to force a single row */
    overflow-x: auto;          /* Allow scrolling on smaller screens */
    padding: 0 20px;           /* Add some padding for spacing */
}


        .featured-icon {
            text-align: center;
            max-width: 200px;
        }

        .featured-icon i {
            font-size: 3rem;
            color: #02A237;
            margin-bottom: 10px;
        }

        .featured-icon h6 {
            font-size: 1.5rem;
            color: #333;
        }

        /* Featured Food Section */
        .featured-food {
            text-align: center;
            margin: 50px 0;
        }

        .featured-food h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .featured-food hr {
            width: 25%;
            height: 4px;
            background-color: #02A237;
            border: none;
            margin: 20px auto;
        }

        .featured-outer1 {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .featured-img {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .featured-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .featured-img:hover img {
            transform: scale(1.1);
        }

        /* Footer Section */
        footer {
            background-color: #02A237;
            color: white;
            text-align: center;
            padding: 20px;
        }

        footer a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .btn1 {
    color: white;
    padding: 12px 24px;
    font-size: 1.1rem;
    font-weight: 500;
    text-decoration: none;
    border-radius: 50px;
    display: inline-block;
    border: none;
    cursor: pointer;
    transition: all 0.4s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* View More (Green Gradient) */
.btn1.view-more {
    background: linear-gradient(135deg, #02A237, #007b1c);
}

/* Order Now (Orange Gradient) */
.btn1.order-now {
    background: linear-gradient(135deg, #ff5e3a, #ff2a00);
}

/* Hover Effect - Works on both buttons */
.btn1:hover {
    filter: brightness(1.25);         /* Brighten the button */
    transform: scale(1.05);           /* Slight pop effect */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Deeper shadow */
}

/* Active Click */
.btn1:active {
    transform: scale(0.98);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Focus Style */
.btn1:focus {

        
        /* Responsive Design */
        @media (max-width: 768px) {
            header nav {
                display: block;
            }

            .featured-outer {
                flex-direction: column;
                align-items: center;
            }

            .featured-outer1 {
                flex-direction: column;
                align-items: center;
            }

            .featured-img {
                max-width: 80%;
                margin-bottom: 20px;
            }
        }
/* Preloader */
#preloader {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  transition: opacity 0.4s ease;
}

.loader {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #02A237;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
    </style>
    
</head>
<body>

<!-- Preloader HTML -->
<div id="preloader"><div class="loader"></div></div>
    <!-- Header -->
    <?php include("header.php"); ?>
<?php include('frontlayout.php');?>
    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
        <h1>Why Choose Us?</h1>
        <hr>
        <center>
        <div class="featured-outer">
            <div class="featured-icon">
                <i class="fa fa-cutlery"></i>
                <h6>Fresh Food</h6>
            </div>
            <div class="featured-icon">
                <i class="fa fa-clock-o"></i>
                <h6>24x7 Service</h6>
            </div>
            <div class="featured-icon">
                <i class="fa fa-motorcycle"></i>
                <h6>Fast Delivery</h6>
            </div>
            <div class="featured-icon">
                <i class="fa fa-birthday-cake"></i>
                <h6>Birthday Specials</h6>
            </div>
            <div class="featured-icon">
                <i class="fa fa-star"></i>
                <h6>259 Reviews</h6>
            </div>
        </div></center>
    </section>
<!-- Featured Food Section -->
<section class="featured-food">
    <h1>Featured Food Items</h1>
    <hr>
    <div class="slider-container">
        <div class="featured-outer1">
        <?php 
$mysqli = new mysqli('localhost','root','','resturant_project');
$table = 'featured_food';

$result = $mysqli->query("SELECT * FROM $table LIMIT 9") or die($mysqli->error);

while($data = $result->fetch_assoc()) {
    echo "<span class='featured-img' style='margin-left:25px; margin-right:25px;'>";
    echo "<a href='foods.php'><img src='http://localhost/php_training/resturant-project/server-side/imgsource/{$data['imgsource']}' height='200px' width='200px' alt='file not found'/></a>";
    echo "</span>";
}
?>

</section>

    <!-- View More Button -->
    <div class="view-more-container">
        <a href="foods.php" class="view-more-btn">View More</a>
    </div>
</section>
    <!-- Footer -->
    <?php include_once("footer.php"); ?>

</body>
</html>
