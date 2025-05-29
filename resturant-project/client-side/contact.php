<?php
session_name('USERSESSID');
session_start();
include("connection.php");

// Handle form submission
$feedback = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $cn = trim($_POST['cn']);
    $gender = $_POST['gender'];
    $message = trim($_POST['message']);

    // Basic validation (can be improved)
    if ($name && $email && $cn && $gender && $message) {
        // Use prepared statement for security
        $stmt = $conn->prepare("INSERT INTO `contact` (`name`, `email`, `contact`, `gender`, `message`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $cn, $gender, $message);

        if ($stmt->execute()) {
            $feedback = "<div style='text-align:center;padding:20px;border:2px solid green;border-radius:5px;background:lightgreen;color:#222;width:80%;margin:20px auto;'>Your contact has been submitted successfully!</div>";
        } else {
            $feedback = "<div style='text-align:center;padding:20px;border:2px solid red;border-radius:5px;background:#ffe6e6;color:#b00;width:80%;margin:20px auto;'>There was an error submitting your contact. Please try again.</div>";
        }
        $stmt->close();
    } else {
        $feedback = "<div style='text-align:center;padding:20px;border:2px solid orange;border-radius:5px;background:#fff8e1;color:#b8860b;width:80%;margin:20px auto;'>Please fill in all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home-1.css">
    <title>Contact Us</title>
    <style>
        .container {
            height: 90vh;
            display: flex;
            background-color: whitesmoke;
        }
        .container .container-left {
            width: 50%;
            margin-top: 7%;
            height: 93.5%;
            text-align: center;
        }
        .container .container-right {
            width: 50%;
            margin-top: 8%;
            height: 95%;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .btn1 {
            width: 260px;
            height: 70px;
            font-size: 30px;
            border-radius: 50px;
            color: white;
            outline: none;
            border: none;
            cursor: pointer;
            box-shadow: 5px 5px 10px grey;
        }
        .box {
            margin-top: 50px;
            background-color: whitesmoke;
            width: 100%;
            max-width: 400px;
            border: 1px solid #222;
            padding: 24px;
            color: #222;
            box-shadow: 5px 5px 10px 0px grey;
            border-radius: 8px;
        }
        .submit {
            margin-top: 20px;
            color: white;
            background: orange;
            width: 100%;
            border: none;
            height: 35px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        body {
            background: whitesmoke;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #bbb;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
        }
        @media (max-width: 900px) {
            .container { flex-direction: column; }
            .container-left, .container-right { width: 100%; margin-top: 2%; }
            .box { margin-top: 20px; }
        }
    </style>
</head>
<body>
    <?php include_once('header.php'); ?>
    <?php echo $feedback; ?>
    <div class="container">
        <div class="container-left">
            <h1>
                <span style="font-family:Curlz MT; font-size:80px;color:orange;"><i>Contact Now</i></span>
                <p style="font-family:Curlz MT; font-size:40px;color:red;"><i>The Best Restaurant</i></p>
            </h1>
            <a href="tel:+919876543210">
                <button class="btn1" style="background-color:green;margin-top:15px;">Call us</button>
            </a>
            <br/>
            <img src="photo/eating.png" width="35%" style="margin-top:3%">
        </div>
        <div class="container-right">
            <form method="post" action="" class="box" onsubmit="return checkConfirm();">
                <input type="text" name="name" required placeholder="Your name..." />
                <input type="email" name="email" required placeholder="Email address..." />
                <input type="text" name="cn" required placeholder="Contact number..." />
                <select name="gender" required>
                    <option value="">Select Gender...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <textarea name="message" placeholder="Type your message here..." required></textarea>
                <input type="submit" class="submit" value="Submit" name="submit" />
                <p style="color:grey; margin-top:20px;">
                    Address: 101, Gate no. 4, Jabalpur, Madhya Pradesh, India <br/>
                    Contact: +91 9876543210 <br/>
                    Email: example@example.com
                </p>
            </form>
        </div>
    </div>
    <script>
        function checkConfirm() {
            return window.confirm('Are you sure you want to contact us?');
        }
    </script>
</body>
</html>
