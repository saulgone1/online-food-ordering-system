<?php
session_name('ADMINSESSID');
session_start();

// Only admins allowed
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_msg'] = "Access Denied!";
    header("Location: login.php");
    exit;
}

include_once("connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Fetch the user's image path before deleting
    $query = "SELECT image FROM user_signup WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userImage);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the user from database
    $deleteQuery = "DELETE FROM user_signup WHERE ID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, 'i', $userId);

    if (mysqli_stmt_execute($deleteStmt)) {
        // Delete the image file if it's not default and exists
        if (!empty($userImage) && $userImage !== 'default.jpg') {
            $imagePath = "server-side/imgsource/" . $userImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $_SESSION['success_msg'] = "User deleted successfully.";
    } else {
        $_SESSION['error_msg'] = "Failed to delete user.";
    }

    mysqli_stmt_close($deleteStmt);
} else {
    $_SESSION['error_msg'] = "Invalid user ID.";
}

header("Location:user-info.php");
exit;
?>
