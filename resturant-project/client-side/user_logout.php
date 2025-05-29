<?php
session_name('USERSESSID');
session_start();
session_unset();
session_destroy();
header('Location: user_login.php');
exit();
?>
