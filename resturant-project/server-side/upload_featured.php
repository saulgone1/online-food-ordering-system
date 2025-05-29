<?php
include_once("connection.php");

if (isset($_POST['submit'])) {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';

    if (!empty($_POST['new_category'])) {
        $category = trim($_POST['new_category']);
    } elseif (!empty($_POST['category'])) {
        $category = trim($_POST['category']);
    } else {
        echo "<script>alert('Please select a category or add a new one.');window.history.back();</script>";
        exit;
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = "./imgsource/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $target_file = $upload_dir . $file_name;

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            echo "<script>alert('Only JPG, PNG, GIF, and WEBP files are allowed.');window.history.back();</script>";
            exit;
        }

        if (move_uploaded_file($file_tmp, $target_file)) {
            $conn = mysqli_connect("localhost", "root", "", "resturant_project");
            if (!$conn) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            // Use the correct column name: imgsource
            $stmt = $conn->prepare("INSERT INTO `featured_food`(`imgsource`, `title`, `price`, `description`, `category`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $file_name, $title, $price, $description, $category);

            if ($stmt->execute()) {
                echo "<script>alert('Featured item uploaded successfully!');window.location.href='Show_featured.php';</script>";
            } else {
                echo "<script>alert('Database insert failed!');window.history.back();</script>";
            }
            $stmt->close();
            $conn->close();
        } else {
            echo "<script>alert('File upload failed!');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No image uploaded or upload error!');window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid form submission.');window.history.back();</script>";
}
?>
